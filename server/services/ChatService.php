<?php

namespace app\services;

use app\config\MysqlDBH;
use app\dto\ResponseDto;
use app\model\Chat;
use app\services\impl\ChatServiceImpl;

class ChatService implements ChatServiceImpl
{

    private $model;

    function __construct()
    {
        $mysqlConnector = new MysqlDBH();
        $this->model = new Chat($mysqlConnector);
    }

    function setNotification(array $data): string
    {
        $response = $this->model->createNotification($data);
        if ($response) {
            return ResponseDto::json("Notification creation was successful!", 201);
        }
        return ResponseDto::json( "An error was encountered while trying to create a notification. Please try again later.", 200);
    }

    function getNotifications(): string
    {
        $response = $this->model->fetchNotification();
        return ResponseDto::json($response, 200);
    }


    function getNotificationTotal(): int
    {
        $response = $this->model->fetchNotification();
        return count($response);
    }

    function editNotificationStatus(string $slug): string
    {
        $response = $this->model->updateNotificationStatus($slug);
        if ($response) {
            return ResponseDto::json("Notification was deleted successful!", 200);
        }
        return ResponseDto::json("An error was encountered while trying to update user deposit transaction...", 500);
    }


    
}
