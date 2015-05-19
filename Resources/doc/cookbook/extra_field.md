# Add some extra fields to Form

You can modify the `FormBuilder` by providing a callable as `extra_fields` option:
```php
<?php
namespace Netinfluence\DemoBundle\Controller;

use Symfony\Component\Form\FormBuilderInterface;

// ...
class MyController extends Controller
{
    public function formAction(Request $request)
    {
        // ...

        $form = $this->createFormBuilder()
            ->add('photo', 'netinfluence_upload_image', array(
                'data_class'    => 'Netinfluence\DemoBundle\Entity\MyFile',
                'extra_fields   => function(FormBuilderInterface $builder, array $options) {
                    // Let's imagine your "MyFile" entity has a "number" field
                    $builder->add('number', 'hidden');
                }
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
