<?php

return [
    'name' => 'Storage',
    'title' => '云存储',
    'description' => '第三方云存储磁盘管理工具包括阿里云oss、七牛云存储、腾讯云cos、又拍云存储upyun',
    'author' => 'BigRocs',
    'version' => 'v1.1.6',
    'serviceProvider' => CoreCMF\Storage\StorageServiceProvider::class,
    'install' => 'corecmf:storage:install',//安装artisan命令
    'providers' => [
        Yangyifan\Upload\UploadServiceProvider::class,//驱动服务
        CoreCMF\Storage\Providers\EventServiceProvider::class,//事件服务
    ],
];
