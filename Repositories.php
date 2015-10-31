<?php

use Doctrine\ORM\EntityRepository;
use Db\Entity;

class TeamRepository extends EntityRepository
{
    public function getMemberCount(Entity\Team $team)
    {
        $queryBuilder = $this->_em->createQueryBuilder();

        $queryBuilder->select('count(m)')
            ->from('Db\Entity\Member', 'm')
            ->where('m.team = :team')
            ->addParameter('team', $team)
            ;

        return $queryBuilder->getQuery()->getSingleScalarResult();
    }
