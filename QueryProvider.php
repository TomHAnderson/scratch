<?php

namespace Db\Query\Provider;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZF\Apigility\Doctrine\Server\Collection\Query\FetchAllOrmQuery;

/**
 * Helper for fetching ContentManagement Pages
 */
class DataPointQueryProvider extends FetchAllOrmQuery implements ServiceLocatorAwareInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator = null;

    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

        return $this;
    }

    /**
     * Get service locator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * Create a filtered query with required parameters
     */
    public function createQuery($entityClass, $parameters)
    {
        $objectManager = $this->getServiceLocator()->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $queryBuilder = $this->getObjectManager()->createQueryBuilder();

        $queryBuilder->select('row')
            ->from($entityClass, 'row')
            ->andwhere('row.conversion = :conversion')
            ->andwhere('row.columnDef = :column')
            ->setParameter('conversion', $parameters['conversion'])
            ->setParameter('column', $parameters['column'])
            ;

        return $queryBuilder;
    }
}

