<?php

    public function onRenderEntityPost($e)
    {
        $entity = $e->getParam('entity');
        if (! $entity->entity instanceof Entity\Artist) {
            // do nothing
            return;
        }

        $payload = $e->getParam('payload');
        $payload['_computed'] = array(
            'performance' => array(
                'count' => 12345
            ),
        );
    }
