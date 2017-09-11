# corecmf

## Structure

```     
src/
```

## Install

Via Composer

```bash
$ composer require corecmf/storage
```

## Usage
安装完成后需要在config/app.php中注册服务提供者到providers数组：
```
CoreCMF\Storage\StorageServiceProvider::class,
```
##install   
```
php artisan corecmf:storage:install
```
