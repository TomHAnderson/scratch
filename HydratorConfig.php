<?php

return array(
    'doctrine-hydrator' => array(
        'DbApi\\V1\\Rest\\Artist\\ArtistHydrator' => array(
            'entity_class' => 'Db\\Entity\\Artist',
            'object_manager' => 'doctrine.entitymanager.orm_default',
            'by_value' => true,
            'strategies' => array(
                'album' => 'hydrator_strategy_include_album',
            ),
            'use_generated_hydrator' => true,
            'filters' => array(
                'exclude_name' => array(
                    'condition' => 'and',
                    'filter' => 'hydrator_filter_exclude_name',
                ),
            ),
        ),
    ),

    'service_manager' => array(
        'invokables' => array(
            'hydrator_filter_exclude_name' => 'DbApi\\Hydrator\\Filter\\ExcludeName',
            'hydrator_strategy_include_album' => 'DbApi\\Hydrator\\Strategy\\Artist\\Album',
        ),
    ),
);
