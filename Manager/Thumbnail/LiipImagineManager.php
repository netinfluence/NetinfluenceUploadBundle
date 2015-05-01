<?php

namespace Netinfluence\UploadBundle\Manager\Thumbnail;

use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Netinfluence\UploadBundle\Model\UploadableInterface;

/**
 * Class LiipImagineManager
 * Generate thumbnails using LiipImagine bundle
 */
class LiipImagineManager implements ThumbnailManagerInterface
{
    /**
     * @var CacheManager
     */
    private $cacheManager;

    public function __construct(CacheManager $cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getThumbnailUrl(UploadableInterface $file, array $size)
    {
        return $this->cacheManager->getBrowserPath($file->getPath(), 'ni_ub_thumbnail', array(
            'thumbnail' => array(
                'mode'  => 'outbound',
                'size'  => $size
            )
        ));
    }
}
