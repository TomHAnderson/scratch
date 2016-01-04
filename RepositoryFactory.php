<?php

return array(
    'doctrine' => array(
        'configuration' => array(
            'orm_default' => array(
                'repository_factory' => 'Db\Repository\RepositoryFactory',
            ),
        ),
    ),

    'service_manager' => array(
        'invokables' => array(
            'Db\Repository\RepositoryFactory' => 'Db\Repository\RepositoryFactory',
        ),
    ),
);


namespace Db\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Repository\RepositoryFactory as ORMRepositoryFactory;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Command\CommandManagerAwareInterface;

final class RepositoryFactory implements
    ORMRepositoryFactory,
    ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    private $repositoryList = array();

    public function getRepository(EntityManagerInterface $entityManager, $entityName)
    {
        $repositoryHash = $entityManager->getClassMetadata($entityName)->getName() . spl_object_hash($entityManager);

        if (isset($this->repositoryList[$repositoryHash])) {
            return $this->repositoryList[$repositoryHash];
        }

        return $this->repositoryList[$repositoryHash] = $this->createRepository($entityManager, $entityName);
    }

    private function createRepository(EntityManagerInterface $entityManager, $entityName)
    {
        /* @var $metadata \Doctrine\ORM\Mapping\ClassMetadata */
        $metadata            = $entityManager->getClassMetadata($entityName);
        $repositoryClassName = $metadata->customRepositoryClassName
            ?: $entityManager->getConfiguration()->getDefaultRepositoryClassName();

        $repository = new $repositoryClassName($entityManager, $metadata);

        // Inject services into repository based on interfaces; similar to a Zend service manager
        if ($repository instanceof CommandManagerAwareInterface) {
            $repository->setCommandManager(
                $this->getServiceLocator()->get('Application\Command\CommandManager')
            );
        }

        return $repository;
    }
}
