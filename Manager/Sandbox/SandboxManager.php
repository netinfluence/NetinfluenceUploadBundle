<?php

namespace Netinfluence\UploadBundle\Manager\Sandbox;

use Gaufrette\Filesystem;
use Netinfluence\UploadBundle\Manager\Thumbnail\ThumbnailManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class SandboxManager
 */
class SandboxManager
{
    /**
     * @var Filesystem Gaufrette sandbox filesystem
     */
    private $filesystem;

    /**
     * @var ThumbnailManagerInterface
     */
    private $thumbnailManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(Filesystem $filesystem, ThumbnailManagerInterface $thumbnailManager, LoggerInterface $logger)
    {
        $this->filesystem = $filesystem;
        $this->thumbnailManager = $thumbnailManager;
        $this->logger = $logger;
    }

    /**
     * Clear temporary files form sandbox filesystem & thumbnails cache
     *
     * @param int $gracePeriod "grace period", days not to remove. By default 2, today and yesterday. 0 will remove everything.
     * @return int number of temporary files removed
     */
    public function clear($gracePeriod = 2)
    {
        $keysToClear = $this->getFilesToClear($gracePeriod);

        // We synchronize files and thumbnails removal, so if one fails everything is consistent
        try {
            foreach ($keysToClear as $key) {
                $removedFromSandbox = false;

                $this->filesystem->delete($key);

                $this->logger->debug(sprintf('Removed file "%s" from sandbox', $key));

                $removedFromSandbox = true;

                $this->thumbnailManager->removeThumbnailsByPath($key);

                $this->logger->debug(sprintf('Removed thumbnails for file "%s" from cache', $key));
            }
        } catch (\Exception $e) {
            $message = sprintf('Error while removing file "%s" from %s', $key, $removedFromSandbox ? 'thumbnails cache' : 'sandbox');

            $this->logger->critical($message);

            throw new \RuntimeException($message);
        }

        return count($keysToClear);
    }

    /**
     * @param int $gracePeriod
     * @return array keys of files to be deleted
     */
    private function getFilesToClear($gracePeriod)
    {
        $files = $this->filesystem->listKeys();

        $ignoredDirs = array();
        $date = new \DateTime();
        $interval = new \DateInterval('P1D');

        // PHP \DatePeriod does not work with past dates :(

        for ($i = 0; $i < $gracePeriod; $i++) {
            $ignoredDirs[] = $date->format('Y-m-d');

            $date->sub($interval);
        }

        // Everything to clear!
        if (empty($ignoredDirs)) {
            return $files['keys'];
        }

        // Optimization: build one fat regex
        $regex = sprintf('#^(%s)/.+#', implode('|', $ignoredDirs));

        $filesToDelete = array();

        foreach ($files['keys'] as $key) {
            if (1 === preg_match($regex, $key)) {
                continue;
            }

            $filesToDelete[] = $key;
        }

        return $filesToDelete;
    }
}
