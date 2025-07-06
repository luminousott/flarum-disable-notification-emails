<?php

namespace Just404\DisableEmailNotifications;

use Flarum\Extend\ExtenderInterface;
use Flarum\Extension\Extension as FlarumExtension;
use Flarum\Notification\Event\Sending;
use Just404\DisableEmailNotifications\Listeners\DisableEmailNotifications;
use Illuminate\Contracts\Container\Container;

class Extension implements ExtenderInterface
{
    public function extend(Container $container, FlarumExtension $extension = null)
    {
        $container->make('events')->listen(
            Sending::class,
            DisableEmailNotifications::class
        );
    }
}
