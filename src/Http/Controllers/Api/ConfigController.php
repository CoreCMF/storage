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
                    ->column(['prop' => 'disks',      'label'=> '磁盘',   'minWidth'=> '100'])
                    ->column(['prop' => 'driver',     'label'=> '驱动',   'minWidth'=> '80'])
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
                    ->searchSelect(['disks'=>'磁盘','id'=>'ID','driver'=>'驱动'])
                    ;
        return resolve('builderHtml')->title('云存储管理器')->item($table)->response();
    }
    public function update(Request $request)
    {
        return [];
    }

}
