// Disabling autoDiscover, otherwise Dropzone will try to attach twice.
Dropzone.autoDiscover = false;

$(function() {
    $('.ni-ub').each(function() {
        var $form = $(this);
        var $dropzone = $form.find('.ni-ub-dz');

        // we go read options from HTML data-* attributes
        var options = $dropzone.data();

        // But we force some
        $.extend(options, {
            fallback: null,
            paramName: 'file'
        });

        var dropzone = new Dropzone('#'+$dropzone.attr('id'), options);

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

        var $collection = $form.find('[data-collection]');
        var successHandler;
        var removeHandler;

        if ($collection) {
            successHandler = function(file, response) {
                var prototype = $collection.data('prototype');
                var number = $collection.children().length;
                var newChild = prototype.replace(/__name__/g, number);

                var $newChild = $(newChild).appendTo($collection);

                $newChild.find('input[data-path]').val(response.path);
                $newChild.find('input[data-temporary]').val(1);

                // Store some file properties we will reuse later
                file.ub = {
                    formId: $newChild.attr('id'),
                    number: response.number,
                    path: response.path,
                    temporary: true
                }
            };

            removeHandler = function(file) {
                // Watch out file properties may not have been added, for instance if sending was unsuccessful
                if (typeof file.ub !== "undefined") {
                    return;
                }

                // Temporary files must be removed from server sandbox
                if (file.ub.temporary) {
                    $.ajax(options.removeUrl, {
                        data: {
                            path: file.ub.path
                        }
                    });
                }
                // Non temporary will be removed via traditional form workflow

                $form.find('#'+file.ub.formId).remove();
            };
        } else {
            successHandler = function(file, response) {
                $form.find('input[data-path]').val(response.path);

                // Anyway a temporary file was stored
                $form.find('input[data-temporary]').val(1);

                // Store some file properties we will reuse later
                file.ub = {
                    path: response.path,
                    temporary: true
                }
            };

            removeHandler = function(file) {
                // Watch out file properties may not have been added, for instance if sending was unsuccessful
                if (typeof file.ub !== "undefined") {
                    return;
                }

                // Temporary files must be removed from server sandbox
                if (file.ub.temporary) {
                    $.ajax(options.removeUrl, {
                        data: {
                            path: file.ub.path
                        }
                    });
                }
                // Non temporary will be removed via traditional form workflow

                $form.find('input[data-path]').val('');
                $form.find('input[data-temporary]').val('');
            };
        }

        dropzone.on('success', successHandler);
        dropzone.on('removedfile', removeHandler);

        /*
         Dropzone.js provides no way to handle already uploaded files
         but manually!
         */

        var addExistingFile = function($form) {
            var mockFile = {
                name: 'file', // needed by Dropzone though unused in UI
                size: 0, // needed by Dropzone though unused in UI
                ub: {
                    formId: $form.attr('id'),
                    path: $form.find('input[data-path]').val(),
                    temporary: false, // they were already persisted
                    thumbnailUrl: $form.find('span[data-thumbnail]').data('url')
                }
            };

            dropzone.emit('addedfile', mockFile);

            dropzone.emit('thumbnail', mockFile, mockFile.ub.thumbnailUrl);

            dropzone.emit('complete', mockFile);

            // And we even have to update that one
            dropzone.options.maxFiles = dropzone.options.maxFiles--;
        };

        $form.find('.ni-ub-dz-image').each(function() {
            addExistingFile($(this));
        });
    });
});
