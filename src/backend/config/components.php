<?php

return [

    // here you can set theme used for your backend application
    // - template comes with: 'default', 'slate', 'spacelab' and 'cerulean'
    'view' => [
        'theme' => [
            'pathMap' => ['@app/views' => '@webroot/themes/keen/views'],
            'baseUrl' => '@web/themes/keen',
            'basePath' => '@webroot/themes/keen',
        ],
    ],
    'user' => [
        'class' => \common\components\User::class,
        'identityClass' => \backend\modules\auth\models\Users::class,
        'enableAutoLogin' => false,
        'autoRenewCookie' => false,
        'loginUrl' => ['auth/auth/login'],
        'authTimeout' => 60 * 60,
    ],
    'errorHandler' => [
        'errorAction' => 'error/index',
    ],
    'request' => [
        'enableCookieValidation' => true,
        'enableCsrfValidation' => true,
        'cookieValidationKey' => 'DhkVRexpSUIeBA1uQg+ibVkubJ0msDOUdjToAjNZvXc=',
    ],
];