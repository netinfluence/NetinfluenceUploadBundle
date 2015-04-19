<?php

namespace Netinfluence\UploadBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Netinfluence\UploadBundle\Manager\FileManager;
use Netinfluence\UploadBundle\Model\UploadableInterface;

/**
 * Class UploadableListener
 * Handle files upload from teh sandbox to the final filesystem
 */
class UploadableListener implements EventSubscriber
{
    /**
     * @var FileManager
     */
    private $manager;

    public function __construct(FileManager $manager)
    {
        $this->manager = $manager;
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

        $this->manager->persist($entity);
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

        $this->manager->remove($entity);
    }
}
