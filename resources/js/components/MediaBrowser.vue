<template>
	<div :id="'media-browser-'+name" class="modal fade media-browser in show"  tabindex="-1" role="dialog" style="overflow-y:inherit; max-height:inherit;">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				
				<div class="modal-header">
					
					<div class="col-xs-6">
						<h4 class="modal-title">Media</h4>
					</div>

					<div class="col-xs-6">
						<input type="text" class="form-control" v-on:keyup="reloadItems" placeholder="Search for..." v-model="keyword">
					</div>

				</div>

				<div class="modal-body" style="max-height: 320px;	overflow-y: auto;">

					<div v-if="showMessage" v-html="errorMessage" class="alert alert-danger"></div>

					<div class="row">
						<media-dropzone :name="name" :files.sync="items" clickable=".media-browser-upload" :path="controller" @file-upload-error="showUploadError"></media-dropzone>
					</div>
	      	
					<div class="row">
						<template v-if="items.length">
							<table class="table table-hover" v-if="tableView">
								<thead>
									<tr>
										<th>Name</th>
										<th>Type</th>
										<th>Size</th>
									</tr>
								</thead>
								<tbody>
									<tr v-for="item in items">
										<td>
											<a href="#" v-bind:class="{'active':item.selected}" v-on:click.prevent="selectItem(item)" v-bind:title="item.name"  data-toggle="tooltip">
												{{ item.name }}
											</a>
										</td>
										<td v-text="item.type"></td>
										<td v-text="item.sizeFormatted"></td>
									</tr>
								</tbody>
							</table>
							<div class="col-xs-4 col-md-2" v-for="item in items" v-if="!tableView">
								<a href="#" class="thumbnail" v-bind:class="{'active':item.selected}" v-on:click.prevent="selectItem(item)" v-bind:title="item.name"  data-toggle="tooltip" data-placement="bottom">
									<img v-bind:src="item.type.substr(0,5) == 'image' ? item.thumbnail : 'http://placehold.it/150x150?text='+item.name" v-bind:alt="item.name">
								</a>
							</div>
						</template>
						<p class="text-center" v-else>No results found</p>
					</div>

					<div class="row text-center">
						<button class="btn btn-primary more" v-show="showmore" v-on:click.prevent="loadItems">Show more</button>
					</div>

				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-info" v-text="tableView ? 'Tiles' : 'Table'" @click.prevent="tableView = !tableView"></button>
					<button type="button" class="btn btn-primary media-browser-upload">upload</button>
					<button type="button" class="btn btn-default" @click="$emit('close')">close</button>
					<button type="button" class="btn btn-primary confirm" :disabled="!hasSelection" v-on:click.prevent="confirmSelection">confirm</button>
				</div>
				
			</div>
		</div>
	</div>
</template>

<script>

    export default {

		data() {
			return {tableView:true, search:null, keyword:'', showmore:false, items:[], next_page:1, showMessage:false};
		},

		mounted()
		{
			this.loadItems();
		},

		props : {
			controller: {
				default: '/admin/media/ajax'
			},

			name: {
				default: 'media_id'
			},

            selected : {
                default : null
			},
			
            multiple : {
                default : false
            }
		},

		computed: {
			hasSelection: function()
			{
				let selected = this.selectedItems();

				return selected.length ? true : false;
			}
		},

		methods: {

			reloadItems: _.debounce(function()
			{
				this.items = [];
				this.next_page = 1;

				this.loadItems();
			}, 500),

			loadItems: function()
			{
				axios({url:this.controller,method:'get', params:{s:this.keyword, page:this.next_page}}).then(function(response)
				{
					this.search = response.data;

					this.showmore = this.search.current_page < this.search.last_page;
					this.next_page = this.showmore ? this.search.current_page + 1 : 1;

					for (let i in this.search.data)
					{
						this.items.push(Object.assign({}, this.search.data[i], { selected: false}));
					}

					this.$nextTick(() => {
            			$('[data-toggle="tooltip"]').tooltip()
        			});

				}.bind(this));
			},

			selectItem: function(item)
			{
				if (typeof this.multiple === 'undefined' || !this.multiple)
				{
					for (let i in this.items)
					{
						if (this.items[i].selected) this.items[i].selected = false;
					}
				}

				item.selected = !item.selected;
			},

			selectedItems: function()
			{
				let selection = this.items.filter(function (item)
				{
  					return item.selected;
				});

				return selection;
			},

			confirmSelection: function()
			{
				let selection = null;

				if (selection = this.selectedItems())
				{
					this.$emit('update', selection.shift());
					this.$emit('close');
				}
			},

			showUploadError: function(args){
				this.showMessage = true;
				this.errorMessage = args.errorMessage.message;
			}
		}
    }

</script>
