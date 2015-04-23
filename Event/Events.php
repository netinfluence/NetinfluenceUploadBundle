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
    const TEMPORARY_FILE_RECEIVED_EVENT = 'netinfluence_upload.temporary_file_received';

    /**
     * Event that occurs after a file is successfully validated
     * Most importantly it will trigger its storage
     *
     * The event listener receives a Netinfluence\UploadBundle\Event\TemporaryFileEvent
     *
     * @var string
     */
    const TEMPORARY_FILE_VALIDATED_EVENT = 'netinfluence_upload.temporary_file_validated';

    /**
     * Event that occurs after a temporary file is deleted
     * Most importantly it will trigger its deletion from sandbox
     * Note that it is not raised when a file already put in final storage was deleted
     *
     * The event listener receives a Netinfluence\UploadBundle\Event\TemporaryFileDeletedEvent
     *
     * @var string
     */
    const TEMPORARY_FILE_DELETED_EVENT = 'netinfluence_upload.temporary_file_deleted';
}
