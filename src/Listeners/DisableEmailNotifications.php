<?php

namespace Just404\DisableEmailNotifications\Listeners;

use Flarum\Notification\Event\Sending;
use Flarum\Notification\Driver\EmailNotificationDriver;
// 如果需要调试，可以取消下面这行的注释
// use Psr\Log\LoggerInterface;

class DisableEmailNotifications
{
    // 如果需要调试，可以取消下面这几行的注释
    // protected LoggerInterface $logger;
    // public function __construct(LoggerInterface $logger)
    // {
    //     $this->logger = $logger;
    // }

    public function handle(Sending $event)
    {
        // 白名单：这些类型的邮件通知将被允许发送。
        // 其他所有邮件通知都将被拦截。
        $emailWhitelist = [
            'welcome',           // 新用户注册欢迎邮件
            'userEmailVerified', // 邮箱验证邮件
            'passwordReset',     // 密码重置邮件
        ];

        // 获取通知类型
        $notificationType = $event->notification->type;

        // 核心逻辑：如果当前通知类型不在白名单中，则移除邮件驱动
        if (!in_array($notificationType, $emailWhitelist)) {

            // // 调试日志 (需要时取消注释)
            // $this->logger->info("[Email Blocker] Type '{$notificationType}' is NOT in whitelist. Blocking email.");

            $drivers = $event->drivers;
            $modifiedDrivers = [];

            foreach ($drivers as $driver) {
                // 如果驱动不是 EmailNotificationDriver，就保留它
                if (!($driver instanceof EmailNotificationDriver)) {
                    $modifiedDrivers[] = $driver;
                }
            }
            // 更新事件的驱动列表
            $event->drivers = $modifiedDrivers;
        } 
        // else {
        //     // 调试日志 (需要时取消注释)
        //     $this->logger->info("[Email Blocker] Type '{$notificationType}' IS in whitelist. Allowing email.");
        // }
    }
}
