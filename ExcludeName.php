<?php

namespace DbApi\Hydrator\Filter;

use Zend\Stdlib\Hydrator\Filter\FilterInterface;

class ExcludeName implements FilterInterface
{
    public function filter($field)
    {
        if ($field == 'name') {
            return false;
        }

        return true;
    }
}
