<?php

namespace CoreCMF\Socialite\Http\Controllers\Api;

use Illuminate\Http\Request;
use CoreCMF\Core\Support\Http\Request as CoreRequest;
use Illuminate\Container\Container;

use App\Http\Controllers\Controller;
use CoreCMF\Socialite\Http\Models\Config;

class ConfigController extends Controller
{
    private $configModel;

    public function __construct(Config $configPro){
       $this->configModel = $configPro;
    }
    public function index(CoreRequest $request)
    {
        $service  = $request->get('tabIndex','wechat');
        $configs = $this->configModel->where('service', '=', $service)->first();

        $configAll = $this->configModel->all();
        foreach ($configAll as $key => $item) {
            $tabs[$item->service] = $item->name;
        }
        $form = resolve('builderForm')
                  ->tabs($tabs)
                  ->item(['name' => 'status',       'type' => 'switch',   'label' => '开关',        'value'=> $configs->status])
                  ->item(['name' => 'service',      'type' => 'text',     'label' => '驱动标识',     'value'=> $configs->service,'disabled'=>true])
                  ->item(['name' => 'client_id',    'type' => 'text',     'label' => '客户ID',      'value'=> $configs->client_id])
                  ->item(['name' => 'client_secret','type' => 'text',     'label' => '客户密钥',     'value'=> $configs->client_secret])
                  ->item(['name' => 'redirect',     'type' => 'text',     'label' => '回调地址',     'value'=> $configs->redirect,'disabled'=>true])
                  ->apiUrl('submit',route('api.socialite.config.update'))
                  ->config('labelWidth','100px');
        $html = resolve('builderHtml')
                  ->title('Socialite配置')
                  ->item($form)
                  ->response();
        return $html;
    }
    public function update(Request $request)
    {
        $config = $this->configModel->where('service', $request->service)->update($request->all());
        if ($config) {
            $message = [
              'message'   => '配置保存成功!',
              'type'      => 'success',
            ];
        }else{
            $message = [
              'message'   => '配置保存失败!',
              'type'      => 'error',
            ];
        }
        return resolve('builderHtml')->message($message)->response();
    }

}
