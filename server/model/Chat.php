<?php

namespace app\model;

use app\config\DatabaseHandler;


class Chat extends BaseModel
{
    private $table_name = 'chats_tb';
    private $notification_table_name = 'notifications_tb';

    function __construct(DatabaseHandler $databaseHandler)
    {
        parent::__construct($databaseHandler);
    }

    function createChat(array $payload)
    {
        if ($this->isNotification($payload["chat_message"]) == true) {
            $sql = "INSERT INTO $this->table_name(chat_slug, chat_sender_id, chat_reciever_id, chat_message, chat_is_read) VALUES(:chat_slug, :chat_sender_id, :chat_reciever_id, :chat_message, :chat_is_read)";
            $response = $this->insert($sql, $payload, "chat_slug");
            return $response;
        }
        return "exist";
    }
    function deleteChat(array $payload)
    {
    }
    function fetchChatTotal()
    {
        $sql = "SELECT * FROM $this->table_name";
        $response = $this->count($sql, []);
        return $response;
    }
    function fetchChats(array $payload)
    {
        $sql = "SELECT * FROM $this->table_name";
        $response = $this->fetchMany($sql);
        return $response;
    }


    function createNotification(array $payload)
    {
        if ($this->isNotification($payload["note_title"]) == true) {
            $sql = "INSERT INTO $this->notification_table_name(note_slug, note_title, note_desc) VALUES(:note_slug, :note_title, :note_desc)";
            $response = $this->insert($sql, $payload, "note_slug");
            return $response;
        }
        return "exist";
    }
    function fetchNotification()
    {
        $sql = "SELECT * FROM $this->notification_table_name WHERE note_status=0";
        $response = $this->fetchMany($sql);
        return $response;
    }

    function updateNotificationStatus(string $slug)
    {
        $update = ["note_status" => 1, "note_slug" => $slug];
        $sql = "UPDATE $this->notification_table_name SET note_status = :note_status, updatedAt = :updatedAt WHERE note_slug = :note_slug";
        return $this->update($sql, $update);
    }

    function isNotification(string $message)
    {
        $sql = "SELECT note_slug from $this->notification_table_name WHERE note_title = ? AND note_status = ?";
        $stmt = $this->query($sql, [$message, 0]);
        return $stmt->rowCount() == 0;
    }

    function isChat(string $message)
    {
        $sql = "SELECT chat_slug from $this->table_name WHERE chat_message = ? AND chat_is_read = ?";
        $stmt = $this->query($sql, [$message, 0]);
        return $stmt->rowCount() == 0;
    }
}
