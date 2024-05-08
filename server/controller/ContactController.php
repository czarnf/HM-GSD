<?php

namespace app\controller;

use app\services\ContactService;

class ContactController
{

    private $contactService;

    function __construct()
    {
        $this->contactService = new ContactService();
    }

    function fetchAllContacts()
    {
        return $this->contactService->getContacts();
    }

    function createNewContact(array $data)
    {
        return $this->contactService->setContact($data);
    }


    function deleteContact(string $slug)
    {
        return $this->contactService->removeContact($slug);
    }

    // function updateDepositStatus(int $status, string $slug){
    //     return $this->depositService->updateUserDeposit($status, $slug);
    // }
}
