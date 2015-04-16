<?php

namespace Netinfluence\QuickerUploadBundle\EventListener;

use Gaufrette\Filesystem;
use Netinfluence\QuickerUploadBundle\Event\Events;
use Netinfluence\QuickerUploadBundle\Event\TemporaryFileEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class FileListener
 * Get file-related events
 */
class FileListener implements  EventSubscriberInterface
{
    /**
     * @var Filesystem the storage for temporary files
     */
    private $filesystem;

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return array(
            Events::FILE_VALIDATED_EVENT => array('onFileValidated', 50)
        );
    }

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Store a file when it is validated
     *
     * @param TemporaryFileEvent $event
     * @throws \Exception
     */
    public function onFileValidated(TemporaryFileEvent $event)
    {
        $file = $event->getTemporaryFile();

        $content = file_get_contents($file->getFile()->getPathname());

        if (false === $content) {
            throw new \RuntimeException('An exception occurred while opening uploaded file');
        }

        $this->filesystem->write($file->getTargetPath(), $file->getFile());
    }
}
