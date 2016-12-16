<template>
	<div>

	<div class="panel panel-default">
		<div class="panel-heading">{{ obj ? obj.name : 'No selection made' }}</div>
		<div class="panel-body">
			<input type="hidden" v-bind:name="name" v-bind:value="obj ? obj.id : ''" />
			<div v-if="obj">
				<div class="col-sm-4">
					<img v-bind:src="obj ? obj.thumbnail : 'http://placehold.it/150x150'" />
				</div>
				<div class="col-sm-8">
					<p>
						{{ obj ? obj.created_at : '-' }}<br />
						{{ obj ? obj.sizeFormatted : '-' }}<br />
						{{ obj ? obj.type : '-' }}
					</p>

					<button class="btn btn-primary" v-on:click.prevent="showBrowser=true">{{ obj ? 'wijzig' : 'voeg toe' }}</button>
					<a v-if="obj" v-bind:href="obj.url" target="_blank" class="btn btn-default">Bekijk</a>
					<button v-if="obj" class="btn btn-danger" v-on:click.prevent="obj=null">Verwijder</button>

				</div>
			</div>
			<div v-else>
				<button class="btn btn-primary" v-on:click.prevent="showBrowser=true">voeg afbeelding toe</button>
			</div>
		</div>
	</div>
	<media-browser v-if="showBrowser" :name="name" :selected="obj" :multiple="false" :controller="controller" @update="updateSelected" @close="showBrowser=false"></media-browser>
</div>
</template>

<script>
    export default {

		data()
		{
			return {obj:null, showBrowser:false}
		},

		mounted()
		{
			this.obj = this.item;

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
		},

		methods: {

			updateSelected(item)
			{
				this.obj = item;
			}
		}
    }
</script>
