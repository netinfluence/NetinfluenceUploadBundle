# Multiple files upload

Use `netinfluence_upload_image_collection` form type instead:

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
            ->add('photos', 'netinfluence_upload_image_collection')
            ->getForm()
        ;
        
         if ($form->handleRequest($request) && $form->isValid()) {
            $files = $form->getData()['photos'];
            
            // this in an array
            var_dump(is_array($files));
            
            // Netinfluence\UploadBundle\Model\FormFile
            var_dump(get_class($files[0]));
            
            // ...
         }
    }
}
```

You can set a maximum number of files through `max_files`. By default it's 0 (unlimited).
It mimics Symfony2 `collection` type, the child being very similar to a `netinfluence_upload_image` type field, you can pass options to it: 

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
            ->add('photo', 'netinfluence_upload_image_collection', array(
                'max_files' => 3,
                'options'   => array(
                    'data_class' => 'Netinfluence\DemoBundle\Entity\MyFile'
                )
            ))
            ->getForm()
        ;
        
        if ($form->handleRequest($request) && $form->isValid()) {
            $files = $form->getData()['photos'];
        
            // this in an array of max 3 items
            var_dump(is_array($files));
        
            // Netinfluence\DemoBundle\Entity\MyFile
            var_dump(get_class($files[0]));
        
            // ...
        }
    }
}
```
