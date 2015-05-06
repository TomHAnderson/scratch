```php
<?php

namespace Api\EventListener\OAuth2\Client;

use ZF\Apigility\Doctrine\Server\Event\DoctrineResourceEvent;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventManagerInterface;

class CreateListener implements ListenerAggregateInterface
{
    protected $listeners = array();

    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(
            DoctrineResourceEvent::EVENT_CREATE_POST,
            array($this, 'createPost')
        );
    }

    // When a user adds a new client set the default scopes
    public function createPost(DoctrineResourceEvent $event)
    {
        $objectManager = $event->getObjectManager();

        foreach (array('data-create', 'data-read', 'data-update', 'data-delete') as $scopeName) {
            $scope = $objectManager->getRepository('ZF\OAuth2\Doctrine\Entity\Scope')->findOneBy(array(
                'scope' => $scopeName,
            ));

            $event->getEntity()->addScope($scope);
            $scope->addClient($event->getEntity());
        }

        $objectManager->flush();
    }

    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }
}
```
