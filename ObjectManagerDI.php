<?php

namespace Application\Service;

use Database\Persistence\ObjectManagerAwareInterface;
use Database\Persistence\ObjectManagerAwareTrait;

class ServiceName implements
    ObjectManagerAwareInterface
{
    use ObjectManagerAwareTrait;
}
