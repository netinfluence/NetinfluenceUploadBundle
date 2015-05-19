# Usage

## Getting started

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

*Of course you could also be using a Form class. Here we stick to a simple inline form example.* 

On your form page, include provided JS and CSS:
```jinja
{# if you don't have jQuery, you will need it too #}
<script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>

<link href="{{ asset('bundles/netinfluenceupload/dropzone/dropzone.min.css') }}" rel="stylesheet" media="screen" />
<script type="text/javascript" src="{{ asset('bundles/netinfluenceupload/dropzone/dropzone.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('bundles/netinfluenceupload/dropzone/uadapter.js') }}"></script>
```

Congrats! A nice javascript and AJAX-powered picker for one file only will be displayed.


## Fetching data back from the Form

### Default setting

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

### Using your own objects (preferred way)

Given that your objects implements `Netinfluence\UploadBundle\Model\UploadableInterface`, you can map those directly to the form:
```php
<?php
namespace Netinfluence\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Netinfluence\UploadBundle\Model\UploadableInterface;

class MyFile implements UploadableInterface
{
    /**
     * @var string path to file, as used by Gaufrette FS
     * As an example, we mapped that field to DB
     * @ORM\Column(type="string", nullable=true)
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

If you are using PHP >= 5.4, to simplify you could also be using `Netinfluence\UploadBundle\Model\MaybeTemporaryFileTrait`:
```php
<?php
namespace Netinfluence\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Netinfluence\UploadBundle\Model\UploadableInterface;
use Netinfluence\UploadBundle\Model\MaybeTemporaryFileTrait;

class MyFile implements UploadableInterface
{
    use MaybeTemporaryFileTrait;

    /**
     * @var string path to file, as used by Gaufrette FS
     * As an example, we mapped that field to DB
     * @ORM\Column(type="string", nullable=true)
     */
    protected $path;

    public function getPath()
    {
        return $this->path;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }
}
```

And finally the controller example code:
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
