<?php

namespace Netinfluence\UploadBundle\Controller;

use Netinfluence\UploadBundle\Event\Events;
use Netinfluence\UploadBundle\Event\TemporaryFileEvent;
use Netinfluence\UploadBundle\Event\TemporaryFileDeletedEvent;
use Netinfluence\UploadBundle\Model\TemporaryFile;
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
    /**
     * Upload a new image to sandbox
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadImageAction(Request $request)
    {
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $request->files->get('file');

        $file = new TemporaryFile($uploadedFile);

        /** @var EventDispatcher $eventDispatcher */
        $eventDispatcher = $this->get('event_dispatcher');

        $eventDispatcher->dispatch(Events::TEMPORARY_FILE_RECEIVED_EVENT, new TemporaryFileEvent($file));

        /** @var ConstraintViolationListInterface $violations */
        $violations = $this->get('netinfluence_upload.temporary_file_validator')->validateImage($file);

        if (count($violations)) {
            $messages = $this->serializeViolations($violations);

            return new JsonResponse(array(
                'error'         => implode(' - ', $messages),
                'raw_errors'    => $messages
            ), 400);
        }

        try {
            // On this event, a listener will store file
            // It is allowed to throw exceptions, relative to FS issues
            $eventDispatcher->dispatch(Events::TEMPORARY_FILE_VALIDATED_EVENT, new TemporaryFileEvent($file));
        } catch (\Exception $e) {
            return new JsonResponse(array(
                'errors'    => $e->getMessage()
            ), 400);
        }

        return new JsonResponse(array(
            'path'      => $file->getTargetPath(),
            'temporary' => true
        ));
    }

    /**
     * Delete a temporary file from sandbox - and only sandbox
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteAction(Request $request)
    {
        $path = $request->query->get('path');

        try {
            $this->get('event_dispatcher')->dispatch(Events::TEMPORARY_FILE_DELETED_EVENT, new TemporaryFileDeletedEvent($path));
        } catch (\Exception $e) {
            return new JsonResponse(array(
                'errors'    => $e->getMessage()
            ), 400);
        }

        return new JsonResponse();
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
