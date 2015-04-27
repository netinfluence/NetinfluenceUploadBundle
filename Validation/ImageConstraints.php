<?php

namespace Netinfluence\UploadBundle\Validation;

use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Mapping\Loader\AbstractLoader;
use Symfony\Component\Validator\Constraint;

/**
 * Class ImageConstraints
 */
class ImageConstraints
{
    /**
     * @var Constraint[]
     */
    private $constraints = array();

    /**
     * @var integer max file in MB
     */
    private $maxFileSize;

    /**
     * @var string[] accepted mimes
     */
    private $acceptedMimes;

    /**
     * From bundle config, will build constraint classes
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        foreach ($config as $className => $options) {
            // We allow constraints names to be fully qualified ones or short-ones
            if (strpos($className, '\\') !== false && class_exists($className)) {
                $className = (string) $className;
            } else {
                $className = AbstractLoader::DEFAULT_NAMESPACE.$className;
            }

            $constraintClass = new \ReflectionClass($className);

            // Because it is from a configuration file, any node will have a default "null" or "array()" value
            // Which will prevent default parameters
            if (empty($options)) {
                $constraint = $constraintClass->newInstance();
            } else {
                $constraint = $constraintClass->newInstance($options);
            }

            if ($constraint instanceof Image) {
                if (null !== $constraint->maxSize ) {
                    // We want it in MB - base10
                    $this->maxFileSize = $constraint->maxSize / 1000000;
                }

                $this->acceptedMimes = $constraint->mimeTypes;
            }

            $this->constraints[] = $constraint;
        }
    }

    /**
     * @return Constraint[]
     */
    public function getConstraints()
    {
        return $this->constraints;
    }

    /**
     * @return int size in MB
     */
    public function getMaxFileSize()
    {
        return $this->maxFileSize;
    }

    /**
     * @return string[]
     */
    public function getAcceptedMimes()
    {
        return $this->acceptedMimes;
    }
}
