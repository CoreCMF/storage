<?php

namespace CoreCMF\Storage\App\Models;

use Schema;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    public $table = 'storage_configs';

    protected $fillable = ['disks', 'driver', 'access_id', 'access_key', 'bucket', 'domain', 'app_id', 'region', 'transport'];

    public function getTransportAttribute($value)
    {
        return (boolean)$value;
    }
    /**
     * [findForDisks 根据disks查找数据]
     * @param  [type] $username [description]
     * @return [type]           [description]
     */
    public function findForDisks($disks)
    {
        return $this->where('disks', $disks)->first();
    }
    public function check($request)
    {
        if ($request->disks) {
            $config = $this->findForDisks($request->disks);
            $callback = '磁盘已经存在!';
        }
        if ($config) {
            if ($config->id != $request->id) {
                return resolve('builderHtml')
                      ->withCode(422)
                      ->callback($callback)
                      ->response();
            }
        }
        return;
    }
    /**
     * [configRegister 加载磁盘配置 并且挂载默认启用磁盘]
     * @return [type] [description]
     */
    public function configRegister()
    {
        if (Schema::hasTable('storage_configs')) {
            $this->filesystemsRegister();
        }
    }
    /**
     * [filesystemsRegister filesystems驱动注册]
     * @return [type] [description]
     */
    public function filesystemsRegister()
    {
        /**
         * [加载云磁盘配置]
         * @var [type]
         */
        $this->all()->map(function ($disks) {
            $transport = $disks->transport? 'https': 'http';
            switch ($disks->driver) {
              case 'oss':
                  $isCName = strstr($disks->domain, "aliyuncs.com")? false: true;//不包含阿里云自动启用自定义域名
                  config(['filesystems.disks.'.$disks->disks => [
                        'driver'            => 'oss',
                        'accessKeyId'        => $disks->access_id,
                        'accessKeySecret'    => $disks->access_key,
                        'endpoint'            => $disks->domain,
                        'isCName'            => $isCName,
                        'securityToken'        => null,
                        'bucket'            => $disks->bucket,
                        'timeout'           => '5184000',
                        'connectTimeout'    => '10',
                        'transport'         => $transport,//如果支持https，请填写https，如果不支持请填写http
                        'max_keys'          => 1000,//max-keys用于限定此次返回object的最大数，如果不设定，默认为100，max-keys取值不能大于1000
                  ]]);
                break;
              case 'qiniu':
                config(['filesystems.disks.'.$disks->disks => [
                    'driver'        => 'qiniu',
                    'domain'        => $disks->domain,//你的七牛域名
                    'access_key'    => $disks->access_id,//AccessKey
                    'secret_key'    => $disks->access_key,//SecretKey
                    'bucket'        => $disks->bucket,//Bucket名字
                    'transport'     => $transport,//如果支持https，请填写https，如果不支持请填写http
                ]]);
                break;
              case 'upyun':
                config(['filesystems.disks.'.$disks->disks => [
                  'driver'        => 'upyun',
                  'domain'        => $disks->domain,//你的upyun域名
                  'username'      => $disks->access_id,//UserName
                  'password'      => $disks->access_key,//Password
                  'bucket'        => $disks->bucket,//Bucket名字
                  'timeout'       => 130,//超时时间
                  'endpoint'      => null,//线路
                  'transport'     => $transport,//如果支持https，请填写https，如果不支持请填写http
                ]]);
                break;
              case 'cos':
                config(['filesystems.disks.'.$disks->disks => [
                  'driver'            => 'cos',
                  'domain'            => $disks->domain,      // 你的 COS 域名
                  'app_id'            => $disks->app_id,
                  'secret_id'         => $disks->access_id,
                  'secret_key'        => $disks->access_key,
                  'region'            => $disks->region,        // 设置COS所在的区域
                  'transport'           => $transport,      // 如果支持 https，请填写 https，如果不支持请填写 http
                  'timeout'           => 60,          // 超时时间
                  'bucket'            => $disks->bucket,
                ]]);
                break;
            }
        });
        /**
         * [$defaultDisks 挂载默认磁盘]
         * @var [type]
         */
        $default = $this->where('status', 'open')->first();
        if ($default) {
            config(['filesystems.default' => $default->disks]);
        }
    }
}
