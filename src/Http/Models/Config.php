<?php

namespace CoreCMF\Storage\Http\Models;

use Schema;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    public $table = 'storage_configs';

    protected $fillable = ['driver', 'access_id', 'access_key', 'bucket', 'endpoint'];

    public function configRegister()
    {
        config(['filesystems.disks.oss' => [
              'driver'        => 'oss',
              'access_id'     => 'LTAI4Nty9m99e5YU',
              'access_key'    => 'I2TNc80H8sHwyFyXeSoHJGpZCdgnxs',
              'bucket'        => 'corecmf',
              'endpoint'      => 'oss-cn-hongkong.aliyuncs.com', // OSS 外网节点或自定义外部域名
              //'endpoint_internal' => '<internal endpoint [OSS内网节点] 如：oss-cn-shenzhen-internal.aliyuncs.com>', // v2.0.4 新增配置属性，如果为空，则默认使用 endpoint 配置(由于内网上传有点小问题未解决，请大家暂时不要使用内网节点上传，正在与阿里技术沟通中)
              'cdnDomain'     => '', // 如果isCName为true, getUrl会判断cdnDomain是否设定来决定返回的url，如果cdnDomain未设置，则使用endpoint来生成url，否则使用cdn
              'ssl'           => true, // true to use 'https://' and false to use 'http://'. default is false,
              'isCName'       => false, // 是否使用自定义域名,true: 则Storage.url()会使用自定义的cdn或域名生成文件url， false: 则使用外部节点生成url
              'debug'         => false,
        ]]);
    }
}
