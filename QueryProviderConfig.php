<?php

return array(
    'zf-collection-query' => array(
        'invokables' => array(
            'data-point' => 'Db\\Query\\Provider\\DataPointQueryProvider',
        ),
    ),
    'zf-apigility' => array(
        'doctrine-connected' => array(
            'DatabaseApi\\V1\\Rest\\DataPoint\\DataPointResource' => array(
                'object_manager' => 'doctrine.entitymanager.orm_default',
                'hydrator' => 'DatabaseApi\\V1\\Rest\\DataPoint\\DataPointHydrator',
                'query_provider' => 'data-point',
            ),
        ),
    ),
...
