<?php

namespace app\model;

use app\config\DatabaseHandler;


class Contact extends BaseModel
{
    private $table_name = 'contacts_tb';

    function __construct(DatabaseHandler $databaseHandler)
    {
        parent::__construct($databaseHandler);
    }

    function createContact(array $payload)
    {
        $sql = "INSERT INTO $this->table_name(contact_slug, contact_name, contact_email, contact_subject, contact_message) 
            VALUES(:contact_slug, :contact_name, :contact_email, :contact_subject, :contact_message)";
        $response = $this->insert($sql, $payload, "contact_slug");
        return $response;
    }


    function deleteContact(string $slug)
    {
        $updateUser = ["contact_status" => 1, "contact_slug" => $slug];
        $sql = "UPDATE $this->table_name SET contact_status = :contact_status, updatedAt = :updatedAt WHERE contact_slug = :contact_slug";
        return $this->update($sql, $updateUser);
    }

    function fetchContacts()
    {
        $sql = "SELECT * FROM $this->table_name WHERE contact_status = 0";
        $response = $this->fetchMany($sql);
        return $response;
    }
}
