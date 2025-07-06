<?php

namespace Just404\DisableEmailNotifications;

use Flarum\Extend;

return [
    (new Extend\Frontend('forum'))
        ->js(__DIR__ . '/../js/dist/forum.js') // 如果扩展有前端JS
        ->css(__DIR__ . '/../less/forum.less'), // 如果扩展有前端CSS

    (new Extend\Frontend('admin'))
        ->js(__DIR__ . '/../js/dist/admin.js') // 如果扩展有后台JS
        ->css(__DIR__ . '/../less/admin.less'), // 如果扩展有后台CSS

    new Extend\Locales(__DIR__ . '/../locale'), // 如果有语言文件

    // 注册事件监听器
    (new Extend\Event())
        ->listen(
            \Flarum\Notification\Event\Sending::class,
            Listeners\DisableEmailNotifications::class
        ),
];