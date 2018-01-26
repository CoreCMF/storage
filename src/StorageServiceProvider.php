<?php

namespace CoreCMF\Storage;

use Illuminate\Support\ServiceProvider;
use CoreCMF\Storage\App\Models\Config;

class StorageServiceProvider extends ServiceProvider
{
    protected $commands = [
        \CoreCMF\Storage\App\Console\InstallCommand::class,
        \CoreCMF\Storage\App\Console\UninstallCommand::class,
    ];
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        //加载artisan commands
        $this->commands($this->commands);
        //配置路由
        $this->loadRoutesFrom(__DIR__.'/Routes/api.php');
        //迁移文件配置
        $this->loadMigrationsFrom(__DIR__.'/Databases/migrations');
        $this->initService();
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
    }
    /**
     * 初始化服务
     */
    public function initService()
    {
        $config = new Config();
        $config->configRegister();//注册配置信息
    }
}
