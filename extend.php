<?php

namespace Just404\DisableEmailNotifications;

use Flarum\Extend;
use Flarum\Notification\Event\Sending;
use Just404\DisableEmailNotifications\Listeners\DisableEmailNotifications;

return [
    (new Extend\Event())
        ->listen(Sending::class, DisableEmailNotifications::class)
];