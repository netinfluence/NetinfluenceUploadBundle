<?php

namespace Netinfluence\UploadBundle\Model;

use Symfony\Component\HttpFoundation\File\File;

/**
 * Class TemporaryFile
 * Represents a temporary file, received via Ajax, that should be stored on the server
 */
class TemporaryFile
{
    /**
     * @var string an unique ID which will be used as file name
     */
    private $id;

    /**
     * @var File
     */
    private $file;

    public function __construct(File $file)
    {
        $this->file = $file;
        $this->id = uniqid('nu_');
    }

    /**
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @return string path where to store the file
     */
    public function getTargetPath()
    {
        // If we can't guess extension, use the original one
        $extension = $this->file->guessExtension() ?: $this->file->getExtension();

        return sprintf('%s/%s.%s', date('Y-m-d'), $this->id, $extension);
    }
}
