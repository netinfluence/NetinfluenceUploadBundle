# Installation

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
            new Liip\ImagineBundle\LiipImagineBundle(),
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
    
_liip_imagine:
    resource: "@LiipImagineBundle/Resources/config/routing.xml"
```
