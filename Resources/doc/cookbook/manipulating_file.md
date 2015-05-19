# Modifying uploaded file

You can manipulate the file about to be uploaded listening to the `FILE_VALIDATED_EVENT`, which is raised when the file sent by Ajax is successfully validated.
A listener with a priority of 50 will store file in sandbox, so you have to set an higher priority to be called before.

```php
<?php

namespace Netinfluence\DemoBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Netinfluence\UploadBundle\Event\Events;
use Netinfluence\UploadBundle\Event\TemporaryFileEvent;

/**
 * Class UploadFilter
 */
class UploadFilter implements EventSubscriberInterface
{
    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            // We are called BEFORE the temporary file is stored
            Events::TEMPORARY_FILE_VALIDATED_EVENT => ['onFileValidated', 100]
        ];
    }

    /**
     * Store a file when it is validated
     *
     * @param TemporaryFileEvent $event
     * @throws \Exception
     */
    public function onFileValidated(TemporaryFileEvent $event)
    {
        $temporaryFile = $event->getTemporaryFile();
        $symfonyFile = $temporaryFile->getFile();
        $path = $symfonyFile->getPathname();

        // Here you can modify file at $path...
    }
}
```

```yml
# src/Netinfluence/DemoBundle/Resources/config/services.yml
services:
    netinfluence_storage.upload_filter:
        class: Netinfluence\DemoBundle\EventListener\UploadFilter
        tags:
            - { name: kernel.event_subscriber }
```
