<?php

    namespace app\services\impl;
    
    interface UserServiceImpl{
        function setUser(array $data): string;
        function getUsersByRole(string $role = "user"): string;
        function getNewestUsers(): string;
        function userAuthentication(string $username, string $password): string;
        function userLogout(string $slug): string;
        function getTotalUsers(): int;
        function editUserPassword(array $password): string;

    }

?>