<?php
use yii\web\Request;
$baseUrl = str_replace('/frontend/web', '', (new Request)->getBaseUrl());
/**
 * $config[0] - site configuration
 * $config[1] - console application, migration, e.g configuration
 *
 * @var array $config
 */
$config = [
    [
        'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
        'components' => [
            'request' => [
                'baseUrl' => $baseUrl,
            ],
            'cache' => [
                'class' => 'yii\caching\FileCache',
            ],
            'authManager' => [
                'class' => 'yii\rbac\DbManager',
            ],
            'urlManager' => [
                'baseUrl' => $baseUrl,
                'enablePrettyUrl' => true,
                'showScriptName' => false,
                'rules' => [

                ]
            ],
        ],
        'modules' => [
            'admin' => [
                'class' => 'mdm\admin\Module',
            ],
        ],
        'as access' => [
            'class' => 'mdm\admin\components\AccessControl',
            'allowActions' => [
                'site/*',
                'order/*',
                'admin/*',
                'some-controller/some-action',
                // The actions listed here will be allowed to everyone including guests.
                // So, 'admin/*' should not appear here in the production, of course.
                // But in the earlier stages of your development, you may probably want to
                // add a lot of actions here until you finally completed setting up rbac,
                // otherwise you may not even take a first step.
            ]
        ]
    ],
    [
        'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
        'components' => [
            'cache' => [
                'class' => 'yii\caching\FileCache',
            ],
        ],
    ],
];
return (array) $config[0];