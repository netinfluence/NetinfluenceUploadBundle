<?php

namespace Netinfluence\UploadBundle\Manager\Thumbnail;

use Netinfluence\UploadBundle\Model\UploadableInterface;

/**
 * Interface ThumbnailManagerInterface
 */
interface ThumbnailManagerInterface
{
    /**
     * @param UploadableInterface $file
     * @param array $size [ width, height ]
     * @return string URL or browser path
     */
    public function getThumbnailUrl(UploadableInterface $file, array $size);

    /**
     * Removed thumbnails generated for this file
     *
     * @param UploadableInterface $file
     */
    public function removeThumbnails(UploadableInterface $file);
}
