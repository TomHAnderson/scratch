<?php

    'zf-apigility' => array(
        'doctrine-connected' => array(
            'Api\\V1\\Rest\\OAuth2Client\\OAuth2ClientResource' => array(
                ...
                'listeners' => array(
                    'oauth2_client_create_listener',
                ),
            ),
        ),
    ),

?>
