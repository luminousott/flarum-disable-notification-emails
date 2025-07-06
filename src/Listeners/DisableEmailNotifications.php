<?php

namespace Just404\DisableEmailNotifications\Listeners;

use Flarum\Notification\Event\Sending;
use Flarum\Notification\Driver\EmailNotificationDriver;
use Psr\Log\LoggerInterface; // 用于调试日志

class DisableEmailNotifications
{
    protected LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function handle(Sending $event)
    {
        $notification = $event->notification;
        $drivers = $event->drivers;

        // 这是我们明确允许通过邮件发送的通知类型白名单
        $emailWhitelist = [
            'welcome',           // 新用户注册欢迎邮件
            'userEmailVerified', // 邮箱验证邮件
            'passwordReset',     // 密码重置邮件
            // 如果未来 Flarum 核心或关键扩展有其他绝对不能禁用的系统邮件，请添加到这里
        ];

        // 调试日志：记录当前通知类型和用户ID
        $this->logger->info("Email Blocker: Sending Event Triggered. Type: {$notification->type} for User ID: {$notification->user->id}");

        // 调试日志：记录处理前的驱动列表
        $driverNamesBefore = [];
        foreach ($drivers as $driver) {
            $driverNamesBefore[] = get_class($driver);
        }
        $this->logger->info("  Drivers before processing: " . implode(', ', $driverNamesBefore));

        // 核心逻辑：如果通知类型不在白名单中，则移除邮件驱动
        if (!in_array($notification->type, $emailWhitelist)) {
            $this->logger->info("  Notification type '{$notification->type}' is NOT in the email whitelist. Attempting to remove email driver.");

            $modifiedDrivers = [];
            foreach ($drivers as $driver) {
                if ($driver instanceof EmailNotificationDriver) {
                    // 这是邮件驱动，我们不希望它发送，所以不把它添加到 modifiedDrivers
                    $this->logger->info("  EmailNotificationDriver REMOVED for '{$notification->type}'.");
                } else {
                    // 其他驱动（如 'alert'）保留
                    $modifiedDrivers[] = $driver;
                }
            }
            // 更新事件的驱动列表
            $event->drivers = array_values($modifiedDrivers); // 重新索引数组

            // 调试日志：记录处理后的驱动列表
            $driverNamesAfter = [];
            foreach ($event->drivers as $driver) {
                $driverNamesAfter[] = get_class($driver);
            }
            $this->logger->info("  Remaining drivers after processing: " . (empty($driverNamesAfter) ? "None" : implode(', ', $driverNamesAfter)));

        } else {
            $this->logger->info("  Notification type '{$notification->type}' IS in the email whitelist. Email WILL be sent if driver exists.");
        }
    }
}