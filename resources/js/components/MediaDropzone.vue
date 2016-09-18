<template>

    <div v-el:dropzone>
        <slot name="dropzone-container">
			<div class="col-xs-12">
				<div class="progress" v-if="progress">
					<div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" v-bind:style="{width:progress+'%'}">
						<span class="sr-only">70% Complete</span>
					</div>
				</div>
			</div>
        </slot>
    </div>

</template>

<script>

import Dropzone from 'dropzone';

export default {

	data() {
		return {progress:0}
	},

    props : {

            multiple : {
                default : false
            },

            path : {
                default : '/admin/upload'
            },
            file : {
                default : 'file'
            },
            files : {
                default : []
            },
            target : {
                default : 'dropzone'
            },
            clickable : {
                default : false
            },
            previewTemplate : {
                default : '<div style="display:none"></div>'
            },
            createImageThumbnails : {
                default : false
            }
    },

    computed: {
        multipleUploads() {
            return this.multiple ? true : false;
        }
    },
    ready() {
        Dropzone.autoDiscover = false;
        let params = {
            url: this.path,
            paramName: this.file,
            createImageThumbnails: this.createImageThumbnails,
            clickable: this.clickable,
            previewTemplate: this.previewTemplate
        };
        // let dz = new Dropzone(this.target, params);
        let dz = new Dropzone($('.media-browser .modal-body')[0], params);

        dz.on("sending", (file, xhr, formData) => {
            this.$dispatch('file-sending', file);

			formData.append('_token', Laravel.csrfToken);
        });

        dz.on("addedfile", (file) => {
			this.progress = true;
            this.$dispatch('file-added', file);
        });

		dz.on("totaluploadprogress", (uploadProgress,totalBytes,totalBytesSent) => {
			this.progress = uploadProgress;
		});



        dz.on("success", (file, response) => {
            this.$dispatch('file-upload-success', response);

            if (typeof response === 'string') {
                response = JSON.parse(response);
            }
			this.progress = 0;
            this.files.unshift(Object.assign({}, response, { selected: false}));
        });

        dz.on("error", (file, errorMessage, xhr) => {
			this.progress = 0;

            this.$dispatch('file-upload-error', {
                file: file,
                response: response,
                xhr: xhr
            });
        });

        dz.on("queuecomplete", (file) => {
            this.$dispatch('file-upload-queue-completed', file);

        });
    }
}

</script>
