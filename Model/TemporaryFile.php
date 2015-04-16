<?php

namespace Netinfluence\QuickerUploadBundle\Model;

use Symfony\Component\HttpFoundation\File\File;

/**
 * Class TemporaryFile
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
        $this->id   = uniqid('ntf_', true);
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

        return printf('%s/%s/%s/%s.%s', date('Y'), date('m'), date('d'), $this->id, $extension);
    }
}
