// Disabling autoDiscover, otherwise Dropzone will try to attach twice.
Dropzone.autoDiscover = false;

$(function() {
    $('.ni-ub-dz').each(function() {
       var $form = $(this);

        var dropzone = $form.dropzone({
            paramName: 'file',
            maxFiles: $form.data('max-files'),
            url: $form.data('url')
        });

        dropzone.on('removedfile', function(file) {
            // call ajax
        });

        /*dropzone.on('error', function(file) {
            // show error better?
        });*/

        dropzone.on('success', function(file) {
            // update form
        });
    });
});