<?php
$config = [
    'id' => 'basic',
    'name' => 'SalesDrive',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['app\config\settings'],
    'language' => 'en',
    'sourceLanguage' => 'en',
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Module',
        ],
        'gridview' =>  [
            'class' => '\kartik\grid\Module'
        ]
    ],
    'components' => [
        'request' => [
            'cookieValidationKey' => 'BpFsddFeU0Qaf9oYQ6Fv80iLa9ebcndv',
            'baseUrl' => ''
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'class' => 'app\components\User',
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['site/index']
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'transport' => [
                'plugins' => [
                    [
                        'class' => 'Swift_Plugins_ThrottlerPlugin',
                        'constructArgs' => [20]
                    ]     
                ]
            ]
        ],
        'db' => require(__DIR__ . '/db.php'),
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',
                '<action:\w+>/<id:\d+>' => 'site/<action>',
                '' => 'site/index',
                '<action>'=>'site/<action>'
            ]

        ],
        'formatter' => [
            'timeZone' => 'Europe/Kiev'
        ],
    ]
];

if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module'
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
                'generators' => [
            'controller'   => [
                'class'     => 'yii\gii\generators\controller\Generator',
                'templates' => [
                    'actions' => '@app/components/gii/controller'
                ]
            ],
            'crud'   => [
                'class'     => 'yii\gii\generators\crud\Generator',
                'templates' => ['actions' => '@app/components/gii/crud']
            ]
        ]
    ];
}

return $config;