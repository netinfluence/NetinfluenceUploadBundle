<?php

namespace Netinfluence\QuickerUploadBundle\Event;

use Netinfluence\QuickerUploadBundle\Model\TemporaryFile;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class TemporaryFileEvent
 */
class TemporaryFileEvent extends Event
{


    /**
     * @var TemporaryFile
     */
    private $temporaryFile;

    /**
     * @param TemporaryFile $temporaryFile
     */
    public function __construct(TemporaryFile $temporaryFile)
    {
        $this->temporaryFile = $temporaryFile;
    }

    /**
     * @return TemporaryFile
     */
    public function getTemporaryFile()
    {
        return $this->temporaryFile;
    }
}
