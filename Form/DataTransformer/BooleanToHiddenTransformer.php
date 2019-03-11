<?php

namespace Netinfluence\UploadBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class BooleanToHiddenTransformer
 */
class BooleanToHiddenTransformer implements DataTransformerInterface
{
    /**
     * Transforms a boolean to an integer for our hidden field
     *
     * @param  null|bool $value
     * @return int
     */
    public function transform($value)
    {
        if (true === $value) {
            return 1;
        }

        return 0;
    }

    /**
     * Transforms an integer or string (from our hidden field) to a boolean
     *
     * @param  int|string $fieldValue
     * @return bool
     */
    public function reverseTransform($fieldValue)
    {
        // String tolerance in case of Javascript mess...
        if (1 === $fieldValue || '1' === $fieldValue) {
            return true;
        }

        return false;
    }
}
