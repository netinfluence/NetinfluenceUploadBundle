<?php

namespace Netinfluence\UploadBundle\Generator;

use Netinfluence\UploadBundle\Model\UploadableInterface;

/**
 * Interface ThumbnailGeneratorInterface
 */
interface ThumbnailGeneratorInterface
{
    /**
     * @param UploadableInterface $file
     * @param array $size [ width, height ]
     * @return string URL or browser path
     */
    public function getUrl(UploadableInterface $file, array $size);
}
