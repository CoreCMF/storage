<?php

namespace CoreCMF\Storage\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Container\Container;

use App\Http\Controllers\Controller;
use CoreCMF\Admin\Models\Config as adminConfig;
use CoreCMF\Storage\Http\Models\Config;

class ConfigController extends Controller
{
    private $configModel;

    public function __construct(Config $configPro,adminConfig $adminConfigPro){
         $this->configModel = $configPro;
         $this->adminConfigModel = $adminConfigPro;
    }
    public function builderForm($apiUrl)
    {
        $driver = ['oss' => '阿里云Oss','qiniu' => '七牛云QiNiu','upyun' => '又拍云UpYun','cos' => '腾讯云Cos'];
        return resolve('builderForm')
                ->item(['name' => 'transport', 'type' => 'switch',    'label' => 'Https',       'placeholder' => 'SSl Https'])
                ->item(['name' => 'disks',     'type' => 'text',     'label' => '磁盘',         'placeholder' => '磁盘名称'])
                ->item(['name' => 'driver',    'type' => 'select',   'label' => '驱动',         'placeholder' => '驱动',
                  'options'=>$driver,       'value'=>'oss', 'apiUrl'=>$apiUrl])
                ->item(['name' => 'bucket',    'type' => 'text',     'label' => 'Bucket',       'placeholder' => 'Bucket名字'])
                ->config('labelWidth','120px');
    }
    public function index(Request $request)
    {
        $pageSizes = $this->adminConfigModel->getPageSizes();
        $config = resolve('builderModel')
                            ->request($request)
                            ->pageSize($this->adminConfigModel->getPageSize())
                            ->getData($this->configModel);
        $table = resolve('builderTable')
                    ->data($config['model'])
                    ->column(['prop' => 'id',         'label'=> 'ID',     'width'=> '55'])
                    ->column(['prop' => 'disks',      'label'=> '磁盘',   'minWidth'=> '120'])
                    ->column(['prop' => 'driver',     'label'=> '驱动',   'minWidth'=> '150'])
                    ->column(['prop' => 'bucket',     'label'=> 'Bucket', 'minWidth'=> '120'])
                    ->column(['prop' => 'domain',     'label'=> '域名',   'minWidth'=> '270'])
                    ->column(['prop' => 'status',     'label'=> '状态',   'minWidth'=> '90','type' => 'status'])
                    ->column(['prop' => 'rightButton','label'=> '操作',   'minWidth'=> '220','type' => 'btn'])
                    ->topButton(['buttonType'=>'add',       'apiUrl'=> route('api.storage.config.add'),'title'=>'添加磁盘'])                         // 添加新增按钮
                    ->topButton(['buttonType'=>'delete',    'apiUrl'=> route('api.storage.config.delete')])                         // 添加删除按钮
                    ->rightButton(['buttonType'=>'edit',    'apiUrl'=> route('api.storage.config.edit')])                           // 添加编辑按钮
                    ->rightButton(['buttonType'=>'forbid',  'apiUrl'=> route('api.storage.config.status')])                         // 添加禁用/启用按钮
                    ->rightButton(['buttonType'=>'delete',  'apiUrl'=> route('api.storage.config.delete')])                         // 添加删除按钮
                    ->pagination(['total'=>$config['total'], 'pageSize'=>$config['pageSize'], 'pageSizes'=>$pageSizes])
                    ->searchTitle('请输入搜索内容')
                    ->searchSelect(['disks'=>'磁盘','driver'=>'驱动','id'=>'ID'])
                    ;
        return resolve('builderHtml')->title('云存储管理器')->item($table)->response();
    }
    public function status(Request $request){
        foreach ($request->all() as $id => $value) {
            $config = $this->configModel->where('id', '=', $id)->update(['status' => $value]);
        }
        $message = [
                    'message'   => '云存储磁盘状态更改成功!',
                    'type'      => 'success',
                ];
        return resolve('builderHtml')->message($message)->response();
    }
    public function add(){
        $builderForm = $this->builderForm(route('api.storage.config.driver'))
                      ->apiUrl('submit',route('api.admin.system.config.store'));

        $form = $builderForm->item(['name' => 'domain', 'type' => 'text',    'label' => 'endpoint',    'placeholder' => '你的阿里云独立oss域名或者去掉bucket.外网域名'])
                ->item(['name' => 'access_id', 'type' => 'text',     'label' => 'accessKeyId',     'placeholder' => 'accessKeyId'])
                ->item(['name' => 'access_key','type' => 'text',     'label' => 'accessKeySecret',    'placeholder' => 'accessKeySecret']);
        $layout = ['xs' => 24, 'sm' => 20, 'md' => 18, 'lg' => 16];
        return resolve('builderHtml')->title('新增磁盘')->item($form)->config('layout',$layout)->response();
    }
    public function update(Request $request)
    {
        return [];
    }
    /**
     * [driverRendering 根据驱动回执不同form表单]
     * @param  [type] $request [description]
     * @return [type]          [description]
     */
    public function driverForm(Request $request)
    {
        $builderForm = $this->builderForm(route('api.storage.config.driver'))
                    ->apiUrl('submit',route('api.admin.system.config.store'));
        switch ($request->driver) {
          case 'qiniu':
            return $builderForm->item(['name' => 'domain', 'type' => 'text',    'label' => 'domain',          'placeholder' => '你的七牛域名'])
                    ->item(['name' => 'access_id', 'type' => 'text',     'label' => 'AccessKey',     'placeholder' => 'AccessKey'])
                    ->item(['name' => 'access_key','type' => 'text',     'label' => 'SecretKey',    'placeholder' => 'SecretKey'])
                    ->response();
            break;
          case 'upyun':
            return $builderForm->item(['name' => 'domain', 'type' => 'text',    'label' => 'domain',          'placeholder' => '你的upyun域名'])
                    ->item(['name' => 'access_id', 'type' => 'text',     'label' => '用户名',     'placeholder' => '授权用户名'])
                    ->item(['name' => 'access_key','type' => 'text',     'label' => '用户密码',    'placeholder' => '授权用户密码'])
                    ->response();
            break;
          case 'cos':
            return $builderForm->item(['name' => 'domain', 'type' => 'text',    'label' => 'domain',          'placeholder' => '你的 COS 域名'])
                    ->item(['name' => 'app_id',   'type' => 'text',     'label' => 'AppId',     'placeholder' => 'app_id'])
                    ->item(['name' => 'access_id', 'type' => 'text',     'label' => 'AccessId',     'placeholder' => 'access_id'])
                    ->item(['name' => 'access_key','type' => 'text',     'label' => 'AccessKey',    'placeholder' => 'access_key'])
                    ->item(['name' => 'region',     'type' => 'text',     'label' => '区域',        'placeholder' => '设置COS所在的区域如：北京->bj'])
                    ->response();
            break;
          case 'oss':
            return $builderForm->item(['name' => 'domain', 'type' => 'text',    'label' => 'endpoint',    'placeholder' => '你的阿里云独立oss域名或者去掉bucket.外网域名'])
                    ->item(['name' => 'access_id', 'type' => 'text',     'label' => 'accessKeyId',     'placeholder' => 'accessKeyId'])
                    ->item(['name' => 'access_key','type' => 'text',     'label' => 'accessKeySecret',    'placeholder' => 'accessKeySecret'])
                    ->response();
            break;
        }
    }

}
