<?php

namespace app\controller;

use app\services\ChatService;

class ChatController
{

    private $chatService;

    function __construct()
    {
        $this->chatService = new ChatService();
    }

    function fetchAllNotification()
    {
        return $this->chatService->getNotifications();
    }

    function getNotificationCount()
    {
        return $this->chatService->getNotificationTotal();
    }

    function createNewNotification(array $data)
    {
        return $this->chatService->setNotification($data);
    }

    function deleteNotification(string $slug)
    {
        return $this->chatService->editNotificationStatus($slug);
    }
}
