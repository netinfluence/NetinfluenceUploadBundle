<?php

namespace Netinfluence\UploadBundle\Model;

/**
 * Interface UploadableInterface
 * Interface that objects mapped to "netinfluence_upload_file" form type must implement
 */
interface UploadableInterface
{
    /**
     * @return string path to the file in storage
     */
    public function getPath();

    /**
     * @param string $path
     */
    public function setPath($path);

    /**
     * @return boolean whether the file is in sandbox or was already persisted
     */
    public function isTemporary();

    /**
     * @param boolean $temporary
     */
    public function setTemporary($temporary);
}
