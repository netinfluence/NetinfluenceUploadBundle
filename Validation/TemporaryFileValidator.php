<?php

namespace Netinfluence\UploadBundle\Validation;

use Netinfluence\UploadBundle\Model\TemporaryFile;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class TemporaryFileValidator
 */
class TemporaryFileValidator
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var ImageConstraints
     */
    private $imageConstraints;

    public function __construct(ValidatorInterface $validator, ImageConstraints $imageConstraints)
    {
        $this->validator = $validator;
        $this->imageConstraints = $imageConstraints;
    }

    /**
     * Validates a file, of the image type, with constraints from config
     *
     * @param TemporaryFile $file
     * @return ConstraintViolationListInterface
     */
    public function validateImage(TemporaryFile $file)
    {
        return $this->validator->validate($file->getFile(), $this->imageConstraints->getConstraints());
    }
}
