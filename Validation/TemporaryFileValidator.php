<?php

namespace Netinfluence\UploadBundle\Validation;

use Netinfluence\UploadBundle\Model\TemporaryFile;
use Symfony\Component\Validator\ValidatorInterface;
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

    /**
     * @var ImageConstraints
     */
    private $imageConstraints;

    /**
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator, ImageConstraints $imageConstraints)
    {
        $this->validator        = $validator;
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
        return $this->validator->validateValue($file->getFile(), $this->imageConstraints->getConstraints());
    }
}
