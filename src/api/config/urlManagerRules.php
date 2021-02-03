<?php
/**
 * Created by PhpStorm.
 * @author: Fred <fred@btimillman.com>
 * Date & Time: 2017-04-08 1:00 PM
 */
return [
//    Sms Feedback Endpoint
    [
        'class' => \yii\rest\UrlRule::class,
        'pluralize' => false,
        'controller' => [
            'smsfeedback' => 'v1/sms-feedback',
        ],
        'tokens' => [
            '{id}' => '<id:\\w+>',
            '{code}' => '<code:\\w+>',
        ],
        'extraPatterns' => [
        ]
    ],
//    Landing page end point not token required
    [
        'class' => \yii\rest\UrlRule::class,
        'pluralize' => false,
        'controller' => [
            'landingpageaggregates' => 'v1/landing-page-aggregates',
        ],
        'except' => ['delete', 'update', 'create'],
    ],
    [
        'class' => \yii\rest\UrlRule::class,
        'pluralize' => false,
        'controller' => [
            'farms' => 'v1/farmers',
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
            'stats' => 'v1/animal-stats',
        ],
        'tokens' => [
            '{id}' => '<id:\\w+>',
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
            'list-types' => 'v1/list-types',
            'event-types' => 'v1/event-types',
            'animal-types' => 'v1/animal-types',
            'farm-types' => 'v1/farm-types',
            'breeds' => 'v1/breeds',
            'users' => 'v1/user',
            'androidVersion' => 'v1/android-app-version',
            'countries' => 'v1/countries',
        ],
        'except' => ['delete'],
    ],
    [
        'class' => \yii\rest\UrlRule::class,
        'pluralize' => false,
        'controller' => [
            'auth' => 'v1/auth',
        ],
        'extraPatterns' => [
            'POST login' => 'login',
            //'POST change-password' => 'change-password',
            //'POST reset-password/begin' => 'begin-reset-password',
            //'POST reset-password/finish' => 'complete-reset-password',
            //'POST reset-password/finish/{token}' => 'complete-reset-password',
            'GET activation-code' => 'activation-code',
            'POST new-password' => 'new-password',
            'OPTIONS <action>' => 'options',
        ],
    ],
    [
        'class' => \yii\rest\UrlRule::class,
        'pluralize' => false,
        'controller' => [
            'dashboard' => 'v1/countries-stats',
        ],
        'extraPatterns' => [
            'GET landing' => 'landing',
            'GET countries/list' => 'countries-list',
            'GET orgs' => 'organizations',
            'GET clients' => 'clients',
            'GET country-report' => 'country-report',
            'OPTIONS <action>' => 'options',
        ],
    ],
    [
        'class' => \yii\rest\UrlRule::class,
        'pluralize' => false,
        'controller' => [
            'country-units' => 'v1/country-units',
        ],
        'extraPatterns' => [
            'GET dependent-lists' => 'dependent-lists',
            'OPTIONS <action>' => 'options',
        ],
    ],
    [
        'class' => \yii\rest\UrlRule::class,
        'pluralize' => false,
        'controller' => [
            'odk' => 'v1/odk',
        ],
        'extraPatterns' => [
            'POST,GET receive' => 'receive',
            'OPTIONS <action>' => 'options',
        ],
    ],
];