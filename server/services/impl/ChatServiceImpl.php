<?php

namespace app\services\impl;

interface ChatServiceImpl
{

    function setNotification(array $data): string;
    function getNotifications(): string;
    function editNotificationStatus(string $slug): string;
}
