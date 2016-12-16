<template>
	<div :id="'media-browser-'+name" class="modal fade media-browser in show"  tabindex="-1" role="dialog" style="overflow-y:inherit; max-height:inherit;">
	  <div class="modal-dialog modal-lg">
	    <div class="modal-content">
	      <div class="modal-header">
		  	<div class="col-xs-6">
				<h4 class="modal-title">Bestanden</h4>
			</div>
			<div class="col-xs-6">
				  <input type="text" class="form-control" v-on:keyup="reloadItems" placeholder="Search for..." v-model="keyword">
			</div>
	      </div>

	      <div class="modal-body" style="max-height: 320px;	overflow-y: auto;">
			<div class="row">
				<media-dropzone :name="name" :files.sync="items" clickable=".media-browser-upload" :path="controller"></media-dropzone>
			</div>
	      	<div class="row">
		  		<span v-if="items.length">
					<div class="col-xs-4 col-md-2" v-for="item in items">
						<a href="#" class="thumbnail" v-bind:class="{'active':item.selected}" v-on:click.prevent="selectItem(item)" v-bind:title="item.name">
							<img v-bind:src="item.thumbnail" v-bind:alt="item.name">
						</a>
					</div>
				</span>
				<p class="text-center" v-else>Geen resultaten gevonden</p>
	      	</div>
	      	<div class="row text-center">
	      		<button class="btn btn-primary more" v-show="showmore" v-on:click.prevent="loadItems">Laad meer</button>
	      	</div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-primary media-browser-upload">Upload</button>
	        <button type="button" class="btn btn-default" data-dismiss="modal" @click="$emit('close')">Sluit</button>
	        <button type="button" class="btn btn-primary confirm" :disabled="!hasSelection" v-on:click.prevent="confirmSelection">Selecteer</button>
	      </div>
	    </div>
	  </div>
	</div>
</template>

<script>
    export default {

		data() {
			return {search:null, keyword:'', showmore:false, items:[], next_page:1};
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
				this.$http({url:this.controller,method:'get', params:{s:this.keyword, page:this.next_page}}).then(function(response)
				{
					this.search = response.data;

					this.showmore = this.search.current_page < this.search.last_page;
					this.next_page = this.showmore ? this.search.current_page + 1 : 1;

					for (let i in this.search.data)
					{
						this.items.push(Object.assign({}, this.search.data[i], { selected: false}));
					}
				});
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
			}
		}
    }
</script>
