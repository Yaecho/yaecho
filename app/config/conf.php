<?php
return [
    'debug'             => true,  // 是否开启调试模式
    'defaultController' => 'site', //默认控制器
    'defaultAction'     => 'index', //默认方法
    'appPath'           => 'app', //应用文件夹
    'controllerPath'    => 'controller', //控制器文件夹
    'templatePath'      => 'view', //视图文件夹
    'modelPath'         => 'model', //模型文件夹
    'db' => [
        'type' => 'mysql',
        'db' => 'test_yaecho',
        'host' => 'localhost',
        'port'     => '3306',
        'username' => 'test',
        'password' => 'test',
        'charset' => 'utf8',
        'prefix' => '',
    ]
];