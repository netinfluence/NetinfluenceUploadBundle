<?php

namespace Netinfluence\UploadBundle\EventListener;

use Gaufrette\Filesystem;
use Netinfluence\UploadBundle\Event\Events;
use Netinfluence\UploadBundle\Event\TemporaryFileDeletedEvent;
use Netinfluence\UploadBundle\Event\TemporaryFileEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class TemporaryFileListener
 * Get file-related events
 */
class TemporaryFileListener implements EventSubscriberInterface
{
    /**
     * @var Filesystem the storage for temporary files
     */
    private $filesystem;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(Filesystem $filesystem, LoggerInterface $logger)
    {
        $this->filesystem = $filesystem;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            Events::TEMPORARY_FILE_VALIDATED_EVENT => array('onFileValidated', 50),
            Events::TEMPORARY_FILE_DELETED_EVENT => array('onFileDeleted', 50),
        );
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

        $tmpPath = $file->getFile()->getPathname();

        $content = file_get_contents($tmpPath);

        if (false === $content) {
            $this->logger->critical(sprintf('Unable to open file "%s" from its PHP temporary storage', $tmpPath));

            throw new \RuntimeException('An exception occurred while opening uploaded file');
        }

        $targetPath = $file->getTargetPath();

        // In sandbox, we will always overwrite
        $number = $this->filesystem->write($targetPath, $content, true);

        $this->logger->info(sprintf('Written %d bytes to "%s" file on final filesystem', $number, $targetPath));
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

        if (!$path) {
            throw new \RuntimeException('An invalid path was provided');
        }

        $this->filesystem->delete($path);

        $this->logger->info(sprintf('Removed file "%s" from sandbox filesystem', $path));
    }
}
