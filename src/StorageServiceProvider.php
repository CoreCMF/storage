<?php

namespace CoreCMF\Storage;

use Illuminate\Support\ServiceProvider;

class StorageServiceProvider extends ServiceProvider
{
    protected $commands = [
        \CoreCMF\Storage\Http\Console\InstallCommand::class,
        \CoreCMF\Storage\Http\Console\UninstallCommand::class,
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
        $this->loadRoutesFrom(__DIR__.'/Routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/Routes/api.php');
        //迁移文件配置
        $this->loadMigrationsFrom(__DIR__.'/Databases/migrations');
        // 加载配置
        $this->mergeConfigFrom(__DIR__.'/Config/config.php', 'storage');
        //设置发布前端文件
        $this->publishes([
            __DIR__.'/../resources/vendor/' => public_path('vendor'),
        ], 'storage');
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
        //注册providers服务
        $this->registerProviders();
    }
    /**
     * 注册引用服务
     */
    public function registerProviders()
    {
        $providers = config('storage.providers');
        foreach ($providers as $provider) {
            $this->app->register($provider);
        }
    }
}
