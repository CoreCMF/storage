<?php

namespace CoreCMF\Storage\Http\Models;

use Schema;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    public $table = 'storage_configs';

    protected $fillable = ['driver', 'access_id', 'access_key', 'bucket', 'endpoint'];
    /**
     * [findForUser 根据用户名或者邮箱、手机找到用户信息]
     */
    /**
     * [findForDisks 根据disks查找数据]
     * @param  [type] $username [description]
     * @return [type]           [description]
     */
    public function findForDisks($disks){
        return $this->where('disks', $disks)->first();
    }
    public function check($request){
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
    public function configRegister()
    {
        config(['filesystems.disks.oss' => [
              'driver'			=> 'oss',
              'accessKeyId'		=> 'LTAI4Nty9m99e5YU',
              'accessKeySecret' 	=> 'I2TNc80H8sHwyFyXeSoHJGpZCdgnxs',
              'endpoint'			=> 'oss-cn-hongkong.aliyuncs.com',
              'isCName'			=> false,
              'securityToken'		=> null,
              'bucket'            => 'corecmf',
              'timeout'           => '5184000',
              'connectTimeout'    => '10',
              'transport'     	=> 'http',//如果支持https，请填写https，如果不支持请填写http
              'max_keys'          => 1000,//max-keys用于限定此次返回object的最大数，如果不设定，默认为100，max-keys取值不能大于1000
        ]]);
        config(['filesystems.disks.qiniu' => [
            'driver'        => 'qiniu',
            'domain'        => 'ow5kqpthl.bkt.clouddn.com',//你的七牛域名
            'access_key'    => 'oM24UseQ3srHQSNJnoCZ37N-F_w3eLlhjIfEhuJQ',//AccessKey
            'secret_key'    => 's9-dGL8DjfOZpUlfrffJP_jY070wzdzFTWdg_gZ-',//SecretKey
            'bucket'        => 'corecmf',//Bucket名字
            'transport'     => 'http',//如果支持https，请填写https，如果不支持请填写http
        ]]);
        config(['filesystems.disks.cos' => [
          'driver'			=> 'cos',
          'domain'            => 'corecmf-1251006149.cosbj.myqcloud.com',      // 你的 COS 域名
          'app_id'            => '1251006149',
          'secret_id'         => 'AKIDGw9p6wwDh0S3PPgBcf8BHNGTckLdmTCU',
          'secret_key'        => 'zZLsc0sF24QE8lZ2rRhG0h6F79H7LF4V',
          'region'            => 'bj',        // 设置COS所在的区域
          'transport'     	=> 'http',      // 如果支持 https，请填写 https，如果不支持请填写 http
          'timeout'           => 60,          // 超时时间
          'bucket'            => 'corecmf',
        ]]);
        config(['filesystems.disks.upyun' => [
          'driver'        => 'upyun',
          'domain'        => 'corecmf.b0.aicdn.com',//你的upyun域名
          'username'      => 'corecmf',//UserName
          'password'      => '001pengjie',//Password
          'bucket'        => 'corecmf',//Bucket名字
          'timeout'       => 130,//超时时间
          'endpoint'      => null,//线路
          'transport'     => 'http',//如果支持https，请填写https，如果不支持请填写http
        ]]);
        config(['filesystems.default' => 'oss']);
    }
}
