<?php

namespace Netinfluence\QuickerUploadBundle\Controller;

use Netinfluence\QuickerUploadBundle\Event\Events;
use Netinfluence\QuickerUploadBundle\Event\TemporaryFileEvent;
use Netinfluence\QuickerUploadBundle\Model\TemporaryFile;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class APIController
 */
class APIController extends Controller
{
    public function uploadImageAction(Request $request)
    {
        /** @var UploadedFile $file */
        $uploadedFile = $request->files->get('file');

        $file = new TemporaryFile($uploadedFile);

        /** @var EventDispatcher $eventDispatcher */
        $eventDispatcher = $this->get('event_dispatcher');

        $eventDispatcher->dispatch(Events::FILE_RECEIVED_EVENT, new TemporaryFileEvent($file));

        /** @var ConstraintViolationListInterface $violations */
        $violations = $this->get('netinfluence_quicker_upload.temporary_file_validator')->validateImage($file);

        if (count($violations)) {
            $response = array(
                'success'   => false,
                'errors'    => $this->serializeViolations($violations)
            );

            return new JsonResponse($response);
        }

        // On this event, a listener will store file
        $eventDispatcher->dispatch(Events::FILE_VALIDATED_EVENT, new TemporaryFileEvent($file));

        return new JsonResponse(array(
            'success'   => true,
            'thumbnail_url' => '',
            'full_url'  => ''
        ));
    }

    /**
     * Helper to have an array of all messages
     *
     * @param ConstraintViolationListInterface $violations
     * @return array
     */
    protected function serializeViolations(ConstraintViolationListInterface $violations)
    {
        $messages = array();

        foreach ($violations as $violation) {
            $messages[] = $violation->getMessage();
        }

        return $messages;
    }
}
