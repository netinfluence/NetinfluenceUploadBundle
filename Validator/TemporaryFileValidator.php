<?php

namespace Netinfluence\QuickerUploadBundle\Validator;

use Netinfluence\QuickerUploadBundle\Model\TemporaryFile;
use Symfony\Component\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class TemporaryFileValidator
 */
class TemporaryFileValidator
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Validates a file, of the image type, with constraints from config
     *
     * @param TemporaryFile $file
     * @return ConstraintViolationListInterface
     */
    public function validateImage(TemporaryFile $file)
    {
        return $this->validator->validateValue($file, new Assert\Image(array(
            'mimeTypes' => array("image/jpeg", "image/jpg", "image/png", "image/gif"),
            'minWidth'  => 1024,
            'minHeight' => 768,
            'maxSize'   => 10240000
        )));
    }
}
