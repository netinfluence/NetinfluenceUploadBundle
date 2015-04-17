<?php

namespace Netinfluence\UploadBundle\Event;

/**
 * Class Events
 * Directory of all events we use
 */
final class Events
{
    /**
     * Event that occurs after a file is successfully received
     * Though it was not validated nor stored
     *
     * The event listener receives a Netinfluence\UploadBundle\Event\TemporaryFileEvent
     *
     * @var string
     */
    const FILE_RECEIVED_EVENT = 'netinfluence_quicker_upload.file_received';

    /**
     * Event that occurs after a file is successfully validated
     * Most importantly it will trigger its storage
     *
     * The event listener receives a Netinfluence\UploadBundle\Event\TemporaryFileEvent
     *
     * @var string
     */
    const FILE_VALIDATED_EVENT = 'netinfluence_quicker_upload.file_validated';
}
