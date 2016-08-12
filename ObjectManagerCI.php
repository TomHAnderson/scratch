<?php

namespace Application\Service;

use Doctrine\Common\Persistence\ObjectManager;

class ServiceName 
{
    private $objectManager;

    public function __construct(ObjectManager $objectManager)
    {   
        $this->objectManager = $objectManager;
    }
}
