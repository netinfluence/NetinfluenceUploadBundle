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

        /*
         Some handling is different depending whether under we have a single file
         or a collection
         */
        var isCollection = ($form.find('[data-collection]') !== []);
        var successHandler;

        if (isCollection) {
            successHandler = function(file, response) {
                var $collection = $form.find('[data-collection]');

                var prototype = $collection.data('prototype');
                var newChild = prototype.replace(/__name__/g, $collection.length);

                var $newChild = $(newChild).appendTo($collection);

                $newChild.find('input[data-path]').val(response.path);
                $newChild.find('input[data-temporary]').val(1);
            };
        } else {
            successHandler = function(file, response) {
                $form.find('input[data-path]').val(response.path);

                // Anyway a temporary file was stored
                $form.find('input[data-temporary]').val(1);
            };
        }

        dropzone.on('success', successHandler);
    });
});
