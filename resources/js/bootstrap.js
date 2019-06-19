import MediaItem from './components/MediaItem.vue';
Vue.component('media-item', MediaItem);

import MediaBrowser from './components/MediaBrowser.vue';
Vue.component('media-browser', MediaBrowser);

import MediaDropzone from './components/MediaDropzone.vue';
Vue.component('media-dropzone', MediaDropzone);

if (typeof window.VueHub === 'undefined') {
    window.VueHub = new Vue();
}

(function () {

    $(function () {
        //	Check-all
        $("input.check-all").on('change', function () {
            let scope = $(this).data('scope');

            if (!scope) return;

            let status = $(this).prop('checked');

            $('input[type=checkbox]' + scope).prop('checked', status);
        });

        //	Linkable table row
        $("tr[data-link] td:not(:has(*))").on('click', function () {
            window.location = $(this).parents('tr').data('link');
        });

    });

})($);
