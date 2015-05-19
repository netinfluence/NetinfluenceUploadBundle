# Storing and removing files

## When using Doctrine + UploadableInterface

If you are using Doctrine ORM, and you entity implements `Netinfluence\UploadBundle\Model\UploadableInterface`, 
a listener provided by this bundle will automatically takes care of all of uploading the file to the final filesystem and 
of removing it from the sandbox filesystem on success.

In the same fashion, file removal is automatic after entity removal.

## Manually

If you don't use Doctrine ORM, but want to save `Netinfluence\UploadBundle\Model\UploadableInterface` file, call the `netinfluence_upload.file_manager` service:
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
            
            // FileManager will remove temporary file from sandbox,
            // and save it to the final one
            $this->get('netinfluence_upload.file_manager')->save($file);
            
            // And if you want to remove:
            $this->get('netinfluence_upload.file_manager')->remove($file);
            
         }
        
        // ...
    }
}
```
