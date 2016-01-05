<?php

0 => array(
    'name' => 'Zend\\I18n\\Validator\\DateTime',
    'options' => array(
        'pattern' => 'yyyy-MM-dd\'T\'HH:mm:ss.SSS\'Z\'',
    ),
),


$pattern = "yyyy-MM-dd'T'HH:mm:ss.SSS'Z'";

$intl = new IntlDateFormatter(null, null, null);
$intl->setPattern($pattern);

$data->startAt = DateTime::createFromFormat('U', $intl->parse($data->startAt));
$data->endAt = DateTime::createFromFormat('U', $intl->parse($data->endAt));
