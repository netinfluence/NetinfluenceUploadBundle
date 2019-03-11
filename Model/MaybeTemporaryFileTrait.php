<?php

namespace Netinfluence\UploadBundle\Model;

/**
 * Trait MaybeTemporaryFileTrait
 * Shortcut for implementing UploadableInterface if you are using PHP >= 5.4+
 */
trait MaybeTemporaryFileTrait
{
    /**
     * @var bool is this path temporary (to sandbox) or not
     * Note that this field does not need to be persisted to a DB or anything
     */
    protected $temporary;

    /**
     * @return bool whether the file is in sandbox or was already persisted
     */
    public function isTemporary()
    {
        return $this->temporary;
    }

    /**
     * @param bool $temporary
     */
    public function setTemporary($temporary)
    {
        $this->temporary = $temporary;
    }
}
