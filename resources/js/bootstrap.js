Vue.component('media-item', require('./components/MediaItem.vue'));
Vue.component('media-browser', require('./components/MediaBrowser.vue'));
Vue.component('media-dropzone', require('./components/MediaDropzone.vue'));

(function() {

	$(function()
	{
		//	Check-all
	   	$("input.check-all").on('change', function()
	   	{
		  	let scope = $(this).data('scope');

		  	if (!scope) return;

			let status = $(this).prop('checked');

		  	$('input[type=checkbox]'+scope).prop('checked', status);
	   });

	   //	Linkable table row
	   $("tr[data-link] td:not(:has(*))").on('click', function()
	   {
		   window.location = $(this).parents('tr').data('link');
	   });
		
	});

})($);
