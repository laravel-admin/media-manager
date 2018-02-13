<template>
	<div class="panel panel-default" style="margin-bottom:0;">
		<div class="panel-heading">{{ obj ? obj.name : 'No selection made' }}</div>
		<div class="panel-body">
			<input type="hidden" v-bind:name="name" v-bind:value="obj ? obj.id : ''" />
			<div v-if="obj">
				<div class="col-sm-4">
					<a :href="obj.url ? obj.url : null" target="_blank">
						<img v-bind:src="obj && obj.type.substr(0,5) == 'image' ? obj.thumbnail : 'http://placehold.it/150x150?text='+obj.name" />
					</a>
				</div>
				<div class="col-sm-8">
					<p>
						{{ obj ? obj.created_at : '-' }}<br />
						{{ obj ? obj.sizeFormatted : '-' }}<br />
						{{ obj ? obj.type : '-' }}
					</p>
					<button class="btn btn-primary" v-on:click.prevent="showBrowser=true">{{ obj ? 'edit' : 'add' }}</button>
					<a v-if="obj" v-bind:href="obj.url" target="_blank" class="btn btn-default">view</a>
					<button v-if="obj" class="btn btn-danger" v-on:click.prevent="deleteItem">delete</button>
				</div>
			</div>
			<div v-else>
				<button class="btn btn-primary" v-on:click.prevent="showBrowser=true">add media</button>
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

		watch : {
			item : function (value) {
				this.updateSelected(value);
			}
		},

		methods: {
			updateSelected(item)
			{
				this.obj = item;
				this.$emit('update-media-item', this.obj);
			},

			deleteItem() {
				this.obj = null;
				this.$emit('update-media-item', null);
			}
		}
	}

</script>
