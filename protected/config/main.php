<?php

return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'GGF Group Guests',
    'language' => null,
    'timeZone' => 'Europe/Rome',
    'preload' => array('log'),
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.components.framework.*',
        'application.extensions.bootstrap.*',
        'application.extensions.behaviors.*',
        'application.extensions.validators.*',
    ),
    // application components
    'components' => array(
        'authManager' => array(
            'class' => 'CPhpAuthManager',
        ),
        'db' => require(dirname(__FILE__) . '/db.php'),
        'errorHandler' => array(
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CProfileLogRoute',
                    'levels' => '',
                    'enabled' => YII_DEBUG,
                ),
                array(
                    'class' => 'CWebLogRoute',
                    'enabled' => YII_DEBUG,
                ),
            ),
        ),
        'user' => array(
            'allowAutoLogin' => false,
            'loginUrl' => array('site/index'),
        ),
        'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => false,
            'rules' => require(dirname(__FILE__) . '/rules.php'),
        ),
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => require(dirname(__FILE__) . '/params.php'),
);
