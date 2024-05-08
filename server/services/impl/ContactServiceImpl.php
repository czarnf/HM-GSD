<?php

    namespace app\services\impl;
    
    interface ContactServiceImpl{
        function setContact(array $data): string;
        function getContacts(): string;
        function updateContactStatus(int $status, string $depositSlug): string;
        function removeContact(string $slug): string;

    }

?>