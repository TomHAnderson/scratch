<?php

use Doctrine\ORM\EntityRepository;
use Db\Entity;

class TeamRepository extends EntityRepository
{
    public oMemberCount(Entity\Team $team)
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



use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Db\Entity;

class ReportController extends AbstractActionController
{
    public function updateTeamNameAction()
    {
        $objectManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');

        $team = $objectManager->getRepository('Blitzy\Entity\Team')->find($this->params()->fromRoute('team_id'));

        $team->setTeamName($objectManager->getRepository('Blitzy\Entity\Team')->calculateTeamName($team));

        $objectManager->flush();
    }
}

