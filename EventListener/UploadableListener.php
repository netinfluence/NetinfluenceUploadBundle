<?php

namespace Netinfluence\UploadBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Gaufrette\Filesystem;
use Netinfluence\UploadBundle\Model\UploadableInterface;

/**
 * Class UploadableListener
 * Handle files upload from teh sandbox to the final filesystem
 */
class UploadableListener implements EventSubscriber
{
    /**
     * @var Filesystem the storage for temporary files
     */
    private $sandboxFilesystem;

    /**
     * @var Filesystem the storage for final files
     */
    private $targetFilesystem;

    public function __construct(Filesystem $sandboxFilesystem, Filesystem $targetFilesystem)
    {
        $this->sandboxFilesystem = $sandboxFilesystem;
        $this->targetFilesystem = $targetFilesystem;
    }

    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            'postPersist',
            'postRemove'
        );
    }

    /**
     * Called during flush of a new entity
     * Save file
     *
     * @param LifecycleEventArgs $eventArgs
     * @throws \Exception in case of filesystem failure
     */
    public function postPersist(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();

        if (! $entity instanceof UploadableInterface) {
            return;
        }

        $path = $entity->getPath();

        // If for some reason that happen, we skip
        if (! $path) {
            return;
        }

        // Only temporary files need to be moved
        // Skip also "null" values...
        if (true !== $entity->isTemporary()) {
            return;
        }

        // We add a more precise exception message
        try {
            $content = $this->sandboxFilesystem->read($path);
        } catch (\RuntimeException $e) {
            throw new \Exception(sprintf('Unable to read file "%s" from sandbox. Maybe sandbox was cleared?', $path));
        }

        try {
            $this->targetFilesystem->write($path, $content);
        } catch (\RuntimeException $e) {
            throw new \Exception(sprintf('Unable to not write file "%s" to final filesystem', $path));
        }

        try {
            $this->sandboxFilesystem->delete($path);
        } catch (\RuntimeException $e) {
            throw new \Exception(sprintf('Could not remove file "%s" from sandbox filesystem. But it was successfully saved to final filesystem before.', $path));
        }
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     * @throws \Exception in case of filesystem failure
     */
    public function postRemove(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();

        if (! $entity instanceof UploadableInterface) {
            return;
        }

        $path = $entity->getPath();

        // If for some reason that happen, we skip
        if (! $path) {
            return;
        }

        // That case is not part of our workflow, but could happen!
        if (true === $entity->isTemporary()) {
            try {
                $this->sandboxFilesystem->delete($path);
            } catch (\RuntimeException $e) {
                throw new \Exception(sprintf('Could not remove file "%s" from sandbox filesystem.', $path));
            }

            return;
        }

        try {
            $this->targetFilesystem->delete($path);
        } catch (\RuntimeException $e) {
            throw new \Exception(sprintf('Could not remove file "%s" from final filesystem.', $path));
        }
    }
}
