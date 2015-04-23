<?php

namespace Netinfluence\UploadBundle\EventListener;

use Gaufrette\Filesystem;
use Netinfluence\UploadBundle\Event\Events;
use Netinfluence\UploadBundle\Event\TemporaryFileDeletedEvent;
use Netinfluence\UploadBundle\Event\TemporaryFileEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class TemporaryFileListener
 * Get file-related events
 */
class TemporaryFileListener implements  EventSubscriberInterface
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
            Events::TEMPORARY_FILE_VALIDATED_EVENT => array('onFileValidated', 50),
            Events::TEMPORARY_FILE_DELETED_EVENT => array('onFileDeleted', 50)
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

        $this->filesystem->write($file->getTargetPath(), $content);
    }

    /**
     * Delete a file
     *
     * @param TemporaryFileDeletedEvent $event
     * @throws \Exception
     */
    public function onFileDeleted(TemporaryFileDeletedEvent $event)
    {
        $path = $event->getPath();

        if (! $path) {
            throw new \RuntimeException('An invalid path was provided');
        }

        $this->filesystem->delete($path);
    }
}
