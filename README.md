# NetinfluenceUploadBundle

User and developer friendly file upload.

Features:

 - [x] a nice AJAX-powered async upload powered by [DropzoneJS](http://www.dropzonejs.com/)
 - [x] **totally integrated with Symfony2 forms** and transparent to use!
 - [x] of course files are properly validated
 - [ ] multiple files upload too
 - [ ] files are uploaded to any storage of your choice (local filesystem, Amazon...) using [Gaufrette](https://github.com/KnpLabs/KnpGaufretteBundle)
 - [x] files are stored in a sandbox first
 - [x] integrates with Doctrine ORM: files are moved to sandbox upon entity save
 - [ ] when coming back to the form, files can be removed
 - [ ] files are served and thumbnails generated using [LiipImagineBundle](https://github.com/liip/LiipImagineBundle)
 - [x] very easily overridable and customizable, if you don"t want any of the above
 
 
## Getting started

### Installing the bundle

Fetch this bundle using composer:
`composer.phar require netinfluence/upload-bundle`

Enable it and its vendors in `app/AppKernel.php`:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            
            new Netinfluence\UploadBundle\NetinfluenceUploadBundle(),
            new Knp\Bundle\GaufretteBundle\KnpGaufretteBundle(),
        );

        // ...
    }
}
```

Also import its routing file, edit `app/config/routing.yml`:
```yml
# app/config/routing.yml
# ...
netinfluence_upload:
    resource: "@NetinfluenceUploadBundle/Resources/config/routing.xml"
```

### Setting Gaufrette filesystems

You have to set up where files are stored. This bundle relies on Gaufrette filesystems abstractions. In doubt refer to its [documentation](https://github.com/KnpLabs/KnpGaufretteBundle)  

You need 2 differents filesystems:

 * `sandbox` will be used for temorary files - ie. files corresponding to uploads on forms not yet validated. Typically this is a local folder, as data is removed after being uploaded to final filesystem, only limited space is required.
 * `final` is the ultimate files destination. You could be using another folder, or Amazon S3...

You need to provide UploadBundle ID of valids `filesystem`. 
Note that those ID are generated in the form `gaufrette.ADAPTER_NAME_filesystem`.

Here is a full working example:
```yml
# app/config/config.yml
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
            
netinfluence_upload:
    filesystems:
        sandbox: gaufrette.sandbox_filesystem
        final: gaufrette.storage_filesystem
```

### Getting started

Just use `netinfluence_upload_image` form type: 
```php
<?php
namespace Netinfluence\DemoBundle\Controller;

// ...

class MyController extends Controller
{
    public function formAction(Request $request)
    {
        // ...

        $form = $this->createFormBuilder()
            ->add('photo', 'netinfluence_upload_image')
            ->getForm()
        ;
        
        // ...
    }
}
```

On your form page, include provided JS and CSS:
```jinja
{# if you don't have jQuery, you will need it too #}
<script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>

<link href="{{ asset('bundles/netinfluenceupload/dropzone/dropzone.min.css') }}" rel="stylesheet" media="screen" />
<script type="text/javascript" src="{{ asset('bundles/netinfluenceupload/dropzone/dropzone.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('bundles/netinfluenceupload/dropzone/uadapter.js') }}"></script>
```

Congrats! A nice javascript and AJAX-powered picker for one file only will be displayed.

### Fetching data back from the Form

#### Default setting

By default, you receive an instance of `Netinfluence\UploadBundle\Model\FormFile`: 
```php
<?php

// ...
class MyController extends Controller
{
    public function formAction(Request $request)
    {
        // ...

        $form = $this->createFormBuilder()
            ->add('photo', 'netinfluence_upload_image')
            ->getForm()
        ;
        
         if ($form->handleRequest($request) && $form->isValid()) {
            $file = $form->getData()['photo'];
            
            // Netinfluence\UploadBundle\Model\FormFile
            var_dump(get_class($file));
            
            // ...
         }
        
        // ...
    }
}
```

#### Using your own objects (preferred way)

Given that your objects implements `Netinfluence\UploadBundle\Model\UploadableInterface`, you can map those directly to the form:
```php
<?php
namespace Netinfluence\DemoBundle\Entity;

use Netinfluence\UploadBundle\Model\UploadableInterface;

class MyFile implements UploadableInterface
{
    /**
     * @var string path to file, as used by Gaufrette FS
     */
    protected $path;

    /**
     * @var boolean is this path temporary (to sandbox) or not
     * Note that this field does not need to be persisted to a DB or anything
     */
    protected $temporary;

    public function getPath()
    {
        return $this->path;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function isTemporary()
    {
        return $this->temporary;
    }

    public function setTemporary($temporary)
    {
        $this->temporary = $temporary;
    }
}
```

```php
<?php
namespace Netinfluence\DemoBundle\Controller;

// ...
class MyController extends Controller
{
    public function formAction(Request $request)
    {
        // ...

        $form = $this->createFormBuilder()
            ->add('photo', 'netinfluence_upload_image', array(
                'data_class' => 'Netinfluence\DemoBundle\Entity\MyFile'
            ))
            ->getForm()
        ;
        
         if ($form->handleRequest($request) && $form->isValid()) {
            $file = $form->getData()['photo'];
            
            // Netinfluence\DemoBundle\Entity\MyFile
            var_dump(get_class($file));
            
            // ...
         }
        
        // ...
    }
}
```

### Storing and removing files

#### When using Doctrine + UploadableInterface

If you are using Doctrine ORM, and you entity implements `Netinfluence\UploadBundle\Model\UploadableInterface`, 
a listener provided by this bundle will automatically takes care of all of uploading teh file to the final filesystem and 
of removing from the sandbox filesystem on success.

In the same fashion, file removal is automatic after entity removal.

#### Manually

If you don't use Doctrine ORM, but want to persist `Netinfluence\UploadBundle\Model\UploadableInterface` file, call the `netinfluence_upload.file_manager` service:
```php
<?php
namespace Netinfluence\DemoBundle\Controller;

// ...
class MyController extends Controller
{
    public function formAction(Request $request)
    {
        // ...

        $form = $this->createFormBuilder()
            ->add('photo', 'netinfluence_upload_image', array(
                'data_class' => 'Netinfluence\DemoBundle\Entity\FormFile'
            ))
            ->getForm()
        ;
        
         if ($form->handleRequest($request) && $form->isValid()) {
            $file = $form->getData()['photo'];
            
            // FileManager will remove temporary file from sandbox,
            // and save it to the final one
            $this->get('netinfluence_upload.file_manager')->persist($file);
            
            // And if you want to remove:
            $this->get('netinfluence_upload.file_manager')->remove($file);
            
         }
        
        // ...
    }
}
```

## Configuration reference

Here is the full bundle configuration, filled with the default values:
```yml
# app/config/config.yml
netinfluence_upload:
    filesystems:
        sandbox: # you should provide here a valid Gaufrette filesystem ID
        final: # you should provide here a valid Gaufrette filesystem ID too
    validation:
        # validations rules applied to uploaded images
        image:
            # here you can use any Symfony2 constraint or custom one
            NotNull: ~
            Image:
                maxSize: 10M
                mimeTypes: ['image/gif', 'image/jpg', 'image/jpeg', 'image/png', 'image/bmp', 'image/x-windows-bmp']
```
 
## Internals: File upload workflow

The whole bundle workflow can be summarised as this:

 * user is presented with a javascript upload input
 * when picking one or many files, those are sent to the server via AJAX
 * files are validated using rules from bundle config
 * if successful, they are stored in the `sandbox` filesystem
 * some data is sent back and placed in the form
 * when the form is finally submitted and valid, you will receive `FormFile` objects
 * you can now handle those yourself 
