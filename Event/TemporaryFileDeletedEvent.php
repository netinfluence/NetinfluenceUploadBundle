<?php

namespace Netinfluence\UploadBundle\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class TemporaryFileDeletedEvent
 */
class TemporaryFileDeletedEvent extends Event
{
    /**
     * @var string path to file
     */
    private $path;

    /**
     * @param string $path
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }
}
