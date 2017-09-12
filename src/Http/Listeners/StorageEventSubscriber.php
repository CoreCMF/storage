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
