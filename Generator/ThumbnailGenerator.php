<?php

namespace Netinfluence\UploadBundle\Generator;

use Netinfluence\UploadBundle\Model\UploadableInterface;

/**
 * Interface ThumbnailGenerator
 */
interface ThumbnailGenerator
{
    /**
     * @param UploadableInterface $file
     * @return string URL or browser path
     */
    public function getUrl(UploadableInterface $file);
}