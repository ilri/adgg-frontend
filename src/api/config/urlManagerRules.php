<?php
/**
 * Created by PhpStorm.
 * @author: Fred <fred@btimillman.com>
 * Date & Time: 2017-04-08 1:00 PM
 */
return [
    [
        'class' => \yii\rest\UrlRule::class,
        'pluralize' => false,
        'controller' => [
            'farmers' => 'v1/farmers',
        ],
        'tokens' => [
            '{id}' => '<id:\\w+>',
            '{code}' => '<code:\\w+>',
        ],
        'extraPatterns' => [
        ]
    ],
    [
        'class' => \yii\rest\UrlRule::class,
        'pluralize' => false,
        'controller' => [
            'animals' => 'v1/animals',
            'events' => 'v1/animal-event',
        ],
        'except' => ['delete', 'update', 'create'],
    ],
    [
        'class' => \yii\rest\UrlRule::class,
        'pluralize' => false,
        'controller' => [
            'auth' => 'v1/auth',
        ],
        'extraPatterns' => [
            'POST login' => 'login',
            'POST change-password' => 'change-password',
            'POST reset-password/begin' => 'begin-reset-password',
            'POST reset-password/finish' => 'complete-reset-password',
            'POST reset-password/finish/{token}' => 'complete-reset-password',
            'OPTIONS <action>' => 'options',
        ],
    ],
    [
        'class' => \yii\rest\UrlRule::class,
        'pluralize' => false,
        'controller' => [
            'odk-web-hook' => 'v1/odk-web-hook',
        ],
        'extraPatterns' => [
            'GET receive' => 'receive',
            'POST receive' => 'receive',
            'OPTIONS <action>' => 'options',
        ],
    ],
];