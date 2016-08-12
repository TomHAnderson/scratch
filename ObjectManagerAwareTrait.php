<?php

namespace Database\Persistence;

use Doctrine\Common\Persistence\ObjectManager;

trait ObjectManagerAwareTrait
{
    protected $objectManager;

    public function setObjectManager(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;

        return $this;
    }

    public function getObjectManager()
    {
        return $this->objectManager;
    }
}
