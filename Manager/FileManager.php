<?php

/*
 *
 */

namespace Netinfluence\UploadBundle\Manager;

use Gaufrette\Filesystem;
use Netinfluence\UploadBundle\Model\UploadableInterface;

/**
 * Class FileManager
 * @author Romaric Drigon <romaric.drigon@gmail.com>
 */
class FileManager
{
    /**
     * @var Filesystem the storage for temporary files
     */
    private $sandboxFilesystem;

    /**
     * @var Filesystem the storage for final files
     */
    private $targetFilesystem;

    /**
     * @var bool
     */
    private $overwrite;

    /**
     * @var bool
     */
    private $ignoreDeleteError;

    /**
     * @param Filesystem $sandboxFilesystem
     * @param Filesystem $targetFilesystem
     * @param bool $overwrite
     * @param bool $ignoreDeleteError
     */
    public function __construct(Filesystem $sandboxFilesystem, Filesystem $targetFilesystem, $overwrite = true, $ignoreDeleteError = true)
    {
        $this->sandboxFilesystem = $sandboxFilesystem;
        $this->targetFilesystem = $targetFilesystem;
        $this->overwrite = $overwrite;
        $this->ignoreDeleteError = $ignoreDeleteError;
    }

    /**
     * Persist a File
     * If it was a temporary file, save it to final filesystem and remove it from sandbox one.
     *
     * @param UploadableInterface $file
     * @throws \Exception in case of filesystem failure
     */
    public function save(UploadableInterface $file)
    {
        $path = $file->getPath();

        // If for some reason that happen, we skip
        if (! $path) {
            return;
        }

        // Only temporary files need to be moved
        // Skip also "null" values...
        if (true !== $file->isTemporary()) {
            return;
        }

        // We add a more precise exception message
        try {
            $content = $this->sandboxFilesystem->read($path);
        } catch (\RuntimeException $e) {
            throw new \Exception(sprintf('Unable to read file "%s" from sandbox. Maybe sandbox was cleared?', $path));
        }

        try {
            $this->targetFilesystem->write($path, $content, $this->overwrite);
        } catch (\RuntimeException $e) {
            throw new \Exception(sprintf('Unable to not write file "%s" to final filesystem', $path));
        }

        try {
            $this->sandboxFilesystem->delete($path);
        } catch (\RuntimeException $e) {
            if (false === $this->ignoreDeleteError) {
                throw new \Exception(sprintf('Could not remove file "%s" from sandbox filesystem. But it was successfully saved to final filesystem before.', $path));
            }
        }
    }

    /**
     * Remove a file from its filesystem (sandbox or final)
     *
     * @param UploadableInterface $file
     * @throws \Exception in case of filesystem failure
     */
    public function remove(UploadableInterface $file)
    {
        $path = $file->getPath();

        // If for some reason that happen, we skip
        if (! $path) {
            return;
        }

        // That case is not part of our workflow, but could happen!
        if (true === $file->isTemporary()) {
            try {
                $this->sandboxFilesystem->delete($path);
            } catch (\RuntimeException $e) {
                if (false === $this->ignoreDeleteError) {
                    throw new \Exception(sprintf('Could not remove file "%s" from sandbox filesystem.', $path));
                }
            }

            return;
        }

        try {
            $this->targetFilesystem->delete($path);
        } catch (\RuntimeException $e) {
            if (false === $this->ignoreDeleteError) {
                throw new \Exception(sprintf('Could not remove file "%s" from final filesystem.', $path));
            }
        }
    }
}
