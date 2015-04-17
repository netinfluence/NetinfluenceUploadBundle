<?php

namespace Netinfluence\UploadBundle\Validator;

use Netinfluence\UploadBundle\Model\TemporaryFile;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Mapping\Loader\AbstractLoader;
use Symfony\Component\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraint;
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
     * @var Constraint[]
     */
    private $imageConstraints;

    /**
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator        = $validator;
        $this->imageConstraints = array();
    }

    /**
     * From bundle config, will build constraint classes
     *
     * @param array $config
     */
    public function setImageValidationConstraints(array $config)
    {
        foreach ($config as $className => $options) {
            // We allow constraints names to be fully qualified ones or short-ones
            if (strpos($className, '\\') !== false && class_exists($className)) {
                $className = (string) $className;
            } else {
                $className = AbstractLoader::DEFAULT_NAMESPACE.$className;
            }

            $constraint = new \ReflectionClass($className);

            // Because it is from a configuration file, any node will have a default "null" or "array()" value
            // Which will prevent default parameters
            if (empty($options)) {
                $this->imageConstraints[] = $constraint->newInstance();
            } else {
                $this->imageConstraints[] = $constraint->newInstance($options);
            }
        }
    }

    /**
     * Validates a file, of the image type, with constraints from config
     *
     * @param TemporaryFile $file
     * @return ConstraintViolationListInterface
     */
    public function validateImage(TemporaryFile $file)
    {
        return $this->validator->validateValue($file->getFile(), $this->imageConstraints);
    }
}
