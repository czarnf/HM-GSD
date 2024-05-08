<?php

namespace app\services;

use app\config\MysqlDBH;
use app\dto\ResponseDto;
use app\model\Contact;
use app\services\impl\ContactServiceImpl;

class ContactService implements ContactServiceImpl
{

    private $model;

    function __construct()
    {
        $mysqlConnector = new MysqlDBH();
        $this->model = new Contact($mysqlConnector);
    }


    function setContact(array $data): string
    {
        $response = $this->model->createContact($data);
        if ($response) {
            return ResponseDto::json("Support message was sent. Thank you for reaching out to us. We truly appreciate.", 201);
        }
        return ResponseDto::json("An error was encountered while trying to submit support. Please try again later.");
    }


    function getContacts(): string
    {
        $response = $this->model->fetchContacts();
        return ResponseDto::json("Fetched all contacts", 200, $response);
    }


    function removeContact(string $slug): string
    {
        $response = $this->model->deleteContact($slug);
        if ($response) {
            return ResponseDto::json("Contacts has been deleted successfully!", 200);
        }
        return ResponseDto::json("The contact with this request paramter has been deleted previously!", 500);
    }

    function updateContactStatus(int $status, string $depositSlug): string
    {
        // $response = $this->model->confirmDeposit($status, $depositSlug);
        // if ($response) {
        //     return ResponseDto::json("Deposit status update was successful!", 200);
        // }
        return ResponseDto::json("An error was encountered while trying to update user deposit transaction...", 500);
    }
}
