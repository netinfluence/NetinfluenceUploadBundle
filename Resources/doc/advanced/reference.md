# Reference
 
## Full Configuration

Here is the full bundle configuration, filled with the default values:
```yml
# app/config/config.yml
netinfluence_upload:
    filesystems:
        sandbox: # you should provide here a valid Gaufrette filesystem ID
        final: # you should provide here a valid Gaufrette filesystem ID too
    validation:
        # Validations rules applied to uploaded images
        image:
            # here you can use any Symfony2 constraint or custom one
            # Watch out, anything set here will override all default values so you may want to recopy those
            NotNull: ~
            Image:
                maxSize: 10M
                mimeTypes: ['image/gif', 'image/jpg', 'image/jpeg', 'image/png', 'image/bmp', 'image/x-windows-bmp']
    # Whether to ignore filesystems errors happening during removal. True is advised, usually permission issues should not prevent users from deleting objects.
    ignore_delete_error: true
    # Whether to overwrite or not files in Final filesystem. True is advised, as you may have files sent twice.
    overwrite: true
```
