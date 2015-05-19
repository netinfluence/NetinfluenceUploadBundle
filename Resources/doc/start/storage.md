# Setting Gaufrette filesystems

You have to set up where files are stored. This bundle relies on Gaufrette filesystems abstractions. In doubt refer to its [documentation](https://github.com/KnpLabs/KnpGaufretteBundle)  

You need 2 different filesystems:

 * `sandbox` will be used for temporary files - ie. files corresponding to uploads on forms not yet validated. Typically this is a local folder, as data is removed after being uploaded to final filesystem, only limited space is required.
 * `final` is the ultimate files destination. You could be using another folder, or Amazon S3...

You need to provide UploadBundle ID of valid `filesystem`. 
Note that those ID are generated in the form `gaufrette.ADAPTER_NAME_filesystem`.

Last thing, you need to configure LiipImagine bundle.
In our example, we use their default configuration, which is compatible with having images files stored in `web/` and cache thumbnails will be stored in `web/media/cache`.
Otherwise, for instance for using Amazon S3, make sure to check their [documentation](http://symfony.com/doc/master/bundles/LiipImagineBundle/index.html).

Here is a full working example:
```yml
# app/config/config.yml
# Setting up storage options
knp_gaufrette:
    adapters:
        temporary_folder:
            # here we use a local folder
            # be sure to have permission set up correctly!
            local:
                directory: "%kernel.root_dir%/../web/tmp"
                create: true
        storage_folder:
            # and we use another local folder
            local:
                directory: "%kernel.root_dir%/../web/uploads"
                create: true
    filesystems:
        sandbox:
            adapter:    temporary_folder
        storage:
            adapter:    storage_folder
   
# Passing those to our bundle
netinfluence_upload:
    filesystems:
        sandbox: gaufrette.sandbox_filesystem
        final: gaufrette.storage_filesystem
    validation: ~ # mandatory node

# Using default LiipImagine bundle config
liip_imagine:
    filter_sets:
        # you need to define the filter we will use to generate thumbnails
        # you can optionally customize it
        ni_ub_thumbnail: ~
```
