<template>
	<div class="panel panel-default">
		<div class="panel-heading">{{ item ? item.name : 'No selection made' }}</div>
		<div class="panel-body">
			<input type="hidden" v-bind:name="name" v-bind:value="item ? item.id : ''" />
			<div v-if="item">
				<div class="col-sm-4">
					<img v-bind:src="item ? item.thumbnail : 'http://placehold.it/150x150'" />
				</div>
				<div class="col-sm-8">
					<p>
						{{ item ? item.created_at : '-' }}<br />
						{{ item ? item.sizeFormatted : '-' }}<br />
						{{ item ? item.type : '-' }}
					</p>

					<button class="btn btn-primary" v-on:click="showBrowser=true">{{ item ? 'wijzig' : 'voeg toe' }}</button>
					<a v-if="item" v-bind:href="item.url" target="_blank" class="btn btn-default">Bekijk</a>
					<button v-if="item" class="btn btn-danger" v-on:click="item=null">Verwijder</button>

				</div>
			</div>
			<div v-else>
				<button class="btn btn-primary" v-on:click="showBrowser=true">voeg afbeelding toe</button>
			</div>
		</div>
	</div>
	<media-browser :show.sync="showBrowser" :selected.sync="item" :multiple="false" :controller="controller"></media-browser>
</template>

<script>
    export default {

		data() {
			return {
				showBrowser:false
			};
		},

		props : {
			controller: {
					default: '/admin/media/ajax',
			},

            name : {
                default : 'media_id'
            },

            item : {
                default : null
            }
		}
    }
</script>
