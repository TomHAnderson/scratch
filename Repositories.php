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


namespace Application\EventSubscriber;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Db\Entity;

class TeamEventSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return array(
            Events::prePersist,
        );
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        if (! $args->getObject() instanceof Entity\Team) {
            return;
        }

        $args->getObjectManager()
            ->getRepository('Db\Entity\Schedule')
            ->updateTeam($args->getObject())
            ;
    }
}
