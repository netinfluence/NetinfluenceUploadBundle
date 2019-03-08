# Changing thumbnails size

Because changing images thumbnails size has a lot of consequences (most notably changing Imagine `ni_ub_thumbnail` filter), it must be done through form options:

```php
<?php

namespace Netinfluence\DemoBundle\Controller;

use Netinfluence\UploadBundle\Form\Type\ImageCollectionType;

// ...

class MyController extends Controller
{
    public function formAction(Request $request)
    {
        // ...

        $form = $this->createFormBuilder()
            ->add('photo', ImageCollectionType::class, array(
                'thumbnail_height' => 120, // default value
                'thumbnail_width' => 120 // default value
            ))
            ->getForm()
        ;
             
        // ...
    }
}
```
