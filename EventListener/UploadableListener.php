<?php

namespace Netinfluence\UploadBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Netinfluence\UploadBundle\Manager\File\FileManagerInterface;
use Netinfluence\UploadBundle\Manager\Thumbnail\ThumbnailManagerInterface;
use Netinfluence\UploadBundle\Model\UploadableInterface;

/**
 * Class UploadableListener
 * Handle files upload from the sandbox to the final filesystem
 */
class UploadableListener implements EventSubscriber
{
    /**
     * @var FileManagerInterface
     */
    private $fileManager;

    /**
     * @var ThumbnailManagerInterface
     */
    private $thumbnailManager;

    public function __construct(FileManagerInterface $fileManager, ThumbnailManagerInterface $thumbnailManager)
    {
        $this->fileManager = $fileManager;
        $this->thumbnailManager = $thumbnailManager;
    }

    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            Events::postPersist,
            Events::postRemove,
            Events::postUpdate,
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

        if (!$entity instanceof UploadableInterface) {
            return;
        }

        $this->fileManager->save($entity);

        // We do not generate now thumbnail: we let LiipImagineBundle generate it later
    }

    /**
     * Called during flush of an entity
     * Will look for (new) temporary files and save those (only)
     *
     * @param LifecycleEventArgs $eventArgs
     * @throws \Exception in case of filesystem failure
     */
    public function postUpdate(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();

        if (!$entity instanceof UploadableInterface) {
            return;
        }

        $this->fileManager->save($entity);
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     * @throws \Exception in case of filesystem failure
     */
    public function postRemove(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();

        if (!$entity instanceof UploadableInterface) {
            return;
        }

        $this->fileManager->remove($entity);

        $this->thumbnailManager->removeThumbnails($entity);
    }
}
