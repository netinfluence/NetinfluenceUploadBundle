// Disabling autoDiscover, otherwise Dropzone will try to attach twice.
Dropzone.autoDiscover = false;

$(function() {
    $('.ni-ub').each(function() {
        var $form = $(this);

        var dropzone = new Dropzone('#'+this.id+' > .ni-ub-dz', {
            paramName: 'file',
            maxFiles: $form.data('max-files'),
            url: $form.data('url')
        });

        dropzone.on('removedfile', function(file) {
            // call ajax
        });

        dropzone.on('error', function(file, errorMessage) {
            // Small inconsistency within Dropzone!
            if (typeof errorMessage === "object") {
                errorMessage = errorMessage.error;
            }

            $form.find('.ni-ub-error').html(errorMessage);

            dropzone.removeFile(file);
        });

        dropzone.on('addedfile', function() {
            $form.find('.ni-ub-error').html('');
        });

        dropzone.on('success', function(file, response) {
            $form.find('input[data-path]').val(response.path);

            // Anyway a temporary file was stored
            $form.find('input[data-temporary]').val(1);
        });
    });
});
