<?php

namespace Netinfluence\UploadBundle\Manager\Thumbnail;

use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Netinfluence\UploadBundle\Model\UploadableInterface;
use Psr\Log\LoggerInterface;

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

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(CacheManager $cacheManager, LoggerInterface $logger)
    {
        $this->cacheManager = $cacheManager;
        $this->logger       = $logger;
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

    /**
     * {@inheritdoc}
     */
    public function removeThumbnails(UploadableInterface $file)
    {
        // Path in cache is the same than in filesystem!
        $this->cacheManager->remove($file->getPath());

        $this->logger->info(sprintf('Removed thumbnails for file "%s"', $file->getPath()));
    }

    /**
     * {@inheritdoc}
     */
    public function removeThumbnailsByPath($path)
    {
        // Path in cache is the same than in filesystem!
        $this->cacheManager->remove($path);

        $this->logger->info(sprintf('Removed thumbnails for path "%s"', $path));
    }
}
