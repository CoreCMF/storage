<?php

namespace CoreCMF\Storage\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Container\Container;

use App\Http\Controllers\Controller;
use CoreCMF\Admin\Http\Models\Config as adminConfig;
use CoreCMF\Storage\Http\Models\Config;
use CoreCMF\Storage\Http\Validator\ConfigRules;

class ConfigController extends Controller
{
    private $configModel;
    private $rules;
    private $builderForm;

    public function __construct(
      Config $configPro,
      ConfigRules $rules,
      adminConfig $adminConfigPro
    ){
         $this->configModel = $configPro;
         $this->rules = $rules;
         $this->adminConfigModel = $adminConfigPro;
         $this->builderForm = resolve('builderForm');
    }
    public function publicForm($apiUrl)
    {
        $driver = ['oss' => '阿里云Oss','qiniu' => '七牛云QiNiu','upyun' => '又拍云UpYun','cos' => '腾讯云Cos'];
        $this->builderForm->item(['name' => 'transport', 'type' => 'switch',    'label' => 'Https',       'placeholder' => 'SSl Https'])
                ->item(['name' => 'disks',     'type' => 'text',     'label' => '磁盘',         'placeholder' => '磁盘名称'])
                ->item(['name' => 'driver',    'type' => 'select',   'label' => '驱动',         'placeholder' => '驱动',
                  'options'=>$driver, 'apiUrl'=>$apiUrl])
                ->item(['name' => 'bucket',    'type' => 'text',     'label' => 'Bucket',       'placeholder' => 'Bucket名字'])
                ->rules($this->rules->index())
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
    /**
     * [status 磁盘启动]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function status(Request $request){
        $this->configModel->where('status', 1)->update(['status' => 0]);//每次只能开启一个磁盘
        foreach ($request->all() as $id => $value) {
            $config = $this->configModel->where('id', '=', $id)->update(['status' => $value]);
        }
        $message = [
                    'message'   => '云存储磁盘更换成功!',
                    'type'      => 'success',
                ];
        return resolve('builderHtml')->message($message)->response();
    }
    /**
     * [delete 删除磁盘]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function delete(Request $request){
        foreach ($request->all() as $id => $value) {
            $response = $this->configModel->find($id)->forceDelete();
        }
        $message = [
                    'message'   => '磁盘删除成功!',
                    'type'      => 'success',
                ];
        return resolve('builderHtml')->message($message)->response();
    }
    /**
     * [add 新增磁盘]
     */
    public function add(Request $request){
        $driver = $request->driver? $request->driver: 'oss';
        $this->publicForm(route('api.storage.config.add'));//添加公共form item
        $this->publicDriverForm($driver);//根据不同驱动添加不同 form item
        $this->builderForm->apiUrl('submit',route('api.storage.config.store'))
                  ->itemData(['driver'=>'oss']);// 增加默认驱动
        if ($request->driver) {
            return $this->builderForm->response();
        }else{
          $layout = ['xs' => 24, 'sm' => 20, 'md' => 18, 'lg' => 16];
          return resolve('builderHtml')->title('新增磁盘')->item($this->builderForm)->config('layout',$layout)->response();
        }
    }
    public function store(Request $request)
    {
        $config = $this->configModel->create($request->all());
        $message = [
                    'message'   => '添加磁盘成功！!',
                    'type'      => 'success',
                ];

        return resolve('builderHtml')->message($message)->response();
    }
    /**
     * [edit 编辑云磁盘配置]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function edit(Request $request)
    {
        $config = $this->configModel->find($request->id);
        $this->builderForm->item(['name' => 'id',      	 'type' => 'hidden',     'label' => 'ID' ]);//添加id
        $this->publicForm(route('api.storage.config.edit'));//添加公共form item
        $driver = $request->driver? $request->driver: $config->driver;
        $this->publicDriverForm($driver);//根据不同驱动添加不同 form item
        $this->builderForm->apiUrl('submit',route('api.storage.config.update'));
        if ($request->driver) {
            return $this->builderForm->response();
        }else{
          $this->builderForm->itemData($config->toArray());// 添加数据
          $layout = ['xs' => 24, 'sm' => 20, 'md' => 18, 'lg' => 16];
          return resolve('builderHtml')->title('编辑磁盘')->item($this->builderForm)->config('layout',$layout)->response();
        }
    }
    public function update(Request $request)
    {
        $input = $request->all();
        $response = $this->configModel->find($input['id'])->fill($input)->save();
        if ($response) {
            $message = [
                            'message'   => '编辑云磁盘成功！!',
                            'type'      => 'success',
                        ];
        }else{
            $message = [
                            'message'   => '编辑云磁盘失败！!',
                            'type'      => 'error',
                        ];
        }

        return resolve('builderHtml')->message($message)->response();
    }
    /**
     * [driverForm 根据驱动渲染form]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function driverForm($driver)
    {
        return $this->publicDriverForm($driver)->response();
    }
    /**
     * [publicDriverForm 根据驱动渲染不同form item]
     * @param  [type] $request [description]
     * @return [type]          [description]
     */
    public function publicDriverForm($driver)
    {
        switch ($driver) {
          case 'qiniu':
            return $this->builderForm->item(['name' => 'domain', 'type' => 'text',    'label' => 'domain',          'placeholder' => '你的七牛域名'])
                    ->item(['name' => 'access_id', 'type' => 'text',     'label' => 'AccessKey',     'placeholder' => 'AccessKey'])
                    ->item(['name' => 'access_key','type' => 'password',     'label' => 'SecretKey',    'placeholder' => 'SecretKey']);
            break;
          case 'upyun':
            return $this->builderForm->item(['name' => 'domain', 'type' => 'text',    'label' => 'domain',          'placeholder' => '你的upyun域名'])
                    ->item(['name' => 'access_id', 'type' => 'text',     'label' => '用户名',     'placeholder' => '授权用户名'])
                    ->item(['name' => 'access_key','type' => 'password',     'label' => '用户密码',    'placeholder' => '授权用户密码']);
            break;
          case 'cos':
            return $this->builderForm->item(['name' => 'domain', 'type' => 'text',    'label' => 'domain',          'placeholder' => '你的 COS 域名'])
                    ->item(['name' => 'app_id',   'type' => 'text',     'label' => 'AppId',     'placeholder' => 'app_id'])
                    ->item(['name' => 'access_id', 'type' => 'text',     'label' => 'AccessId',     'placeholder' => 'access_id'])
                    ->item(['name' => 'access_key','type' => 'password',     'label' => 'AccessKey',    'placeholder' => 'access_key'])
                    ->item(['name' => 'region',     'type' => 'text',     'label' => '区域',        'placeholder' => '设置COS所在的区域如：北京->bj']);
            break;
          case 'oss':
            return $this->builderForm->item(['name' => 'domain', 'type' => 'text',    'label' => 'endpoint',    'placeholder' => '你的阿里云独立oss域名或者去掉bucket.外网域名'])
                    ->item(['name' => 'access_id', 'type' => 'text',     'label' => 'accessKeyId',     'placeholder' => 'accessKeyId'])
                    ->item(['name' => 'access_key','type' => 'password',     'label' => 'accessKeySecret',    'placeholder' => 'accessKeySecret']);
            break;
        }
    }
    /**
     * [check 检查磁盘]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function check(Request $request){
        return $this->configModel->check($request);
    }

}
