
```php
    public function onBootstrap(MvcEvent $e) {
        $serviceManager = $e->getParam('application')->getServiceManager()
            ->get('oauth2.doctrineadapter.second')->bootstrap($e);
    }
    public function getServiceConfig()
    {
        return [
            'factories' => [
                'oauth2.doctrineadapter.default' => function($serviceManager) {
                    $globalConfig = $serviceManager->get('Config');
                    $config = new Config($globalConfig['zf-oauth2-doctrine']['second']);
                    $factory = $serviceManager->get('ZF\OAuth2\Doctrine\Adapter\DoctrineAdapterFactory');
                    $factory->setConfig($config);
                    $adapter = $factory->createService($serviceManager);
                    return $adapter;
                }
            ],
        ];
    }
```
