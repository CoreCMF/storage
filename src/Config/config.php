<?php

return [
    'name' => 'Storage',
    'title' => '社会第三方登录插件',
    'description' => '第三方文件存储工具包括阿里云oss、七牛云存储、等等',
    'author' => 'BigRocs',
    'version' => 'v1.1.6',
    'serviceProvider' => CoreCMF\Storage\StorageServiceProvider::class,
    'install' => 'corecmf:storage:install',//安装artisan命令
    'providers' => [
        Yangyifan\Upload\UploadServiceProvider::class,
        CoreCMF\Storage\Providers\EventServiceProvider::class,//事件服务
    ],
];
