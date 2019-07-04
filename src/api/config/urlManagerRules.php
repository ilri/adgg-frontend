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
            'users' => 'v1/user',
            'settings' => 'v1/settings',
            'odk-json' => 'v1/odk-json',
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
            'auth' => 'v1/auth',
        ],
        'extraPatterns' => [
            'POST authorize' => 'authorize',
            'POST change-password' => 'change-password',
            'POST reset-password/begin' => 'begin-reset-password',
            'POST reset-password/finish' => 'complete-reset-password',
            'POST reset-password/finish/{token}' => 'complete-reset-password',
            'OPTIONS <action>' => 'options',
        ],
    ],
];