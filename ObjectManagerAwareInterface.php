<?php

namespace Database\Persistence;

use Doctrine\Common\Persistence\ObjectManager;

interface ObjectManagerAwareInterface
{
    public function setObjectManager(ObjectManager $objectManager);
    public function getObjectManager();
}
