<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'language' => 'ru_RU',
    'components' => [

        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'rules' => [
                [
                    'pattern' => '',
                    'route' => '',
                    'suffix' => '',
                ],
                [
                    'pattern' => '<controller>/<action>/<id:\d+>',
                    'route' => '<controller>/<action>',
                    'suffix' => '',
                ],
                [
                    'pattern' => '<controller>/<action>',
                    'route' => '<controller>/<action>',
                    'suffix' => '',
                ],
                [
                    'pattern' => '<module>/<controller>/<action>/<id:\d+>',
                    'route' => '<controller>/<action>',
                    'suffix' => '',
                ],
                [
                    'pattern' => '<module>/<controller>/<action>',
                    'route' => '<controller>/<action>',
                    'suffix' => '',
                ],

            ],
        ],

        'mail' => [
            'class'            => 'yii\swiftmailer\Mailer',
            'viewPath'         => '@common/mail',
            //'useFileTransport' => false,
            //'config'           => [
            'transport' => [
                //'mailer'     => 'smtp',
                'class' => 'Swift_SmtpTransport',
                'host'       => 'smtp.jino.ru',
                'port'       => '465',
                //'smtpsecure' => 'ssl',
                'encryption' => 'ssl',
                //'smtpauth'   => true,
                'username'   => 'iChat@amarstd.myjino.ru',
                'password'   => '123454321qwe',
            ],
        ],

        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    ],


















];
