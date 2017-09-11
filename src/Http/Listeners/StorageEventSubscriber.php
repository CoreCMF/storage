<?php

namespace CoreCMF\Storage\Http\Listeners;

use CoreCMF\Storage\Http\Models\Config;
/**
 * [SocialiteEventSubscriber 社会登录事件订阅者]
 */
class StorageEventSubscriber
{
    private $configModel;

    public function __construct(Config $configPro){
       $this->configModel = $configPro;
    }
    /**
     * 处理BuilderForm登录页面渲染
     * 监听BuilderForm事件下的login事件
     * @translator laravelacademy.org
     */
    public function onBuilderFormLogin($event)
    {
        $form = $event->form;
        if ($form->event == 'login') {
            $html = null;
            $redirect = array_key_exists('redirect',$form->config)? encrypt($form->config['redirect']): null;

            $configs = $this->configModel->where('status', 1)->get();
            foreach ($configs as $key => $config) {
                $url = '/OAuth/service/';
                $url = str_replace("service",$config['service'],$url); //驱动替换后期放到model里面处理
                if ($redirect) {
                    $url .= $redirect;
                }
                $html .= '<a href="'.$url.'">
                            <svg class="icon" aria-hidden="true">
                                <use xlink:href="#icon-'.$config['service'].'"></use>
                            </svg>
                          </a>';
            }
            $form->htmlEnd('
                    <div class="socialite">
                      '.$html.'
                    </div>
                  ');
        }
    }
    /**
     * [onBuilderTablePackage 后台模型table渲染处理]
     * @param  [type] $event [description]
     * @return [type]        [description]
     */
    public function onBuilderTablePackage($event)
    {
        $table = $event->table;
        if ($table->event == 'package') {
            $table->data->transform(function ($item, $key) {
                if ($item->name == 'Socialite') {
                    $item->rightButton = [
                        ['title'=>'配置编辑','apiUrl'=> route('api.socialite.config.index'),'type'=>'info', 'icon'=>'fa fa-edit']
                    ];
                }
                return $item;
            });
        }
    }
    /**
     * 为订阅者注册监听器.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'CoreCMF\Core\Events\BuilderForm',
            'CoreCMF\Storage\Http\Listeners\StorageEventSubscriber@onBuilderFormLogin'
        );
        $events->listen(
            'CoreCMF\Core\Events\BuilderTable',
            'CoreCMF\Storage\Http\Listeners\StorageEventSubscriber@onBuilderTablePackage'
        );
    }

}
