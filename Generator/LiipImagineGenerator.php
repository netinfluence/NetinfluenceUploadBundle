<?php

namespace Netinfluence\UploadBundle\Generator;

use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Netinfluence\UploadBundle\Model\UploadableInterface;

/**
 * Class LiipImagineGenerator
 * Generate thumbnails using LiipImagine bundle
 */
class LiipImagineGenerator implements ThumbnailGeneratorInterface
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
    public function getUrl(UploadableInterface $file)
    {
        return $this->cacheManager->getBrowserPath($file->getPath(), 'ni_ub_thumbnail', array(
            'thumbnail' => array(
                'mode'  => 'outbound',
                'size'  => array(120, 120)
            )
        ));
    }
}
