<?php

namespace app\controller;

use app\services\UserService;

class UserController
{

    private $userService;

    function __construct()
    {
        $this->userService = new UserService();
    }

    function fetchAllUsersByRoles(string $role)
    {
        return $this->userService->getUsersByRole($role);
    }

    function fetchNewestUser()
    {
        return $this->userService->getNewestUsers();
    }

    function createNewUserAccount(array $payload)
    {
        // return Response::json($userRequest->get(),201);
        return $this->userService->setUser($payload);
    }

    function userLoginAuthentication(string $email, string $password)
    {
        return $this->userService->userAuthentication($email, $password);
    }

    function userForgotPasswordAuthentication(string $username)
    {
        return $this->userService->userForgotPasswordAuth($username);
    }

    function logoutUserAuthentication(string $slug)
    {
        return $this->userService->userLogout($slug);
    }


    function getUserCounts()
    {
        return $this->userService->getTotalUsers();
    }

    function modifyUserPassword(array $payload)
    {
        return $this->userService->editUserPassword($payload);
    }


    function getSettingParams()
    {
        return $this->userService->getSettings();
    }

    function modifySetting(array $payload)
    {
        return $this->userService->editSetting($payload);
    }
}
