<template>

    <div>
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
            return {progress:0};
        },

        props : {
            name: {
                default: 'media_id'
            },
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

        mounted() {
            Dropzone.autoDiscover = false;
            let params = {
                url: this.path,
                paramName: this.file,
                createImageThumbnails: this.createImageThumbnails,
                clickable: '#media-browser-'+this.name+' '+this.clickable,
                previewTemplate: this.previewTemplate
            };

            let dz = new Dropzone(document.querySelector('#media-browser-'+this.name), params);

            dz.on("sending", (file, xhr, formData) => {
                this.$emit('file-sending', file);

                formData.append('_token', Laravel.csrfToken);
            });

            dz.on("addedfile", (file) => {
                this.progress = true;
                this.$emit('file-added', file);
            });

            dz.on("totaluploadprogress", (uploadProgress,totalBytes,totalBytesSent) => {
                this.progress = uploadProgress;
            });

            dz.on("success", (file, response) => {
                this.$emit('file-upload-success', response);

                if (typeof response === 'string') {
                    response = JSON.parse(response);
                }
                this.progress = 0;
                this.files.unshift(Object.assign({}, response, { selected: false}));
            });

            dz.on("error", (file, errorMessage, xhr) => {
                this.progress = 0;

                this.$emit('file-upload-error', {
                    file: file,
                    errorMessage: errorMessage,
                    xhr: xhr
                });
            });

            dz.on("queuecomplete", (file) => {
                this.$emit('file-upload-queue-completed', file);
            });
        }
    }

</script>
