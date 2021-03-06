<?php

namespace CoreCMF\Storage\App\Listeners;

use CoreCMF\Storage\App\Models\Config;

/**
 * [SocialiteEventSubscriber 社会登录事件订阅者]
 */
class StorageEventSubscriber
{
    private $configModel;

    public function __construct(Config $configPro)
    {
        $this->configModel = $configPro;
    }
    /**
     * [onBuilderTablePackage 后台模型table渲染处理]
     * @param  [type] $event [description]
     * @return [type]        [description]
     */
    public function onBuilderTablePackage($event)
    {
        $table = $event->table;
        if ($table->event == 'adminPackage') {
            $table->data->transform(function ($item, $key) {
                if ($item->name == 'Storage') {
                    $item->rightButton = [
                        [
                            'title'=>'云存储管理',
                            'apiUrl'=> route('api.storage.config.index'),
                            'type'=>'info',
                            'icon'=>'fa fa-edit',
                            'width'=> '1236px',
                            'method'=>'dialog'
                        ]
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
            'CoreCMF\Core\Support\Events\BuilderTable',
            'CoreCMF\Storage\App\Listeners\StorageEventSubscriber@onBuilderTablePackage'
        );
    }
}
