<?php

namespace Netinfluence\UploadBundle\Model;

/**
 * Interface UploadableInterface
 * Interface that objects mapped to "ImageType" form type must implement
 * If you are using PHP >= 5.4, consider using "MaybeTemporaryFileTrait"
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
     * @return bool whether the file is in sandbox or was already persisted
     */
    public function isTemporary();

    /**
     * @param bool $temporary
     */
    public function setTemporary($temporary);
}
