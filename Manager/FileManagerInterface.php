<?php

namespace Netinfluence\UploadBundle\Manager;

use Netinfluence\UploadBundle\Model\UploadableInterface;

/**
 * Class FileManagerInterface
 * @author Romaric Drigon <romaric.drigon@gmail.com>
 */
interface FileManagerInterface
{
    /**
     * Persist a File
     *
     * @param UploadableInterface $file
     * @throws \Exception in case of filesystem failure
     */
    public function save(UploadableInterface $file);

    /**
     * Remove a file from its filesystem
     *
     * @param UploadableInterface $file
     * @throws \Exception in case of filesystem failure
     */
    public function remove(UploadableInterface $file);
}
