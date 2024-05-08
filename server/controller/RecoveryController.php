<?php

namespace app\controller;

use app\services\RecoveryService;


class RecoveryController
{

    private $recoveryService;

    function __construct()
    {
        $this->recoveryService = new RecoveryService();
    }

    function fetchRecoveryRequests()
    {
        return $this->recoveryService->getRecoveries();
    }

    function fetchOnRecoveryRequests()
    {
        return $this->recoveryService->getOnRecoveries();
    }

    function fetchOnRecoveryRequestsBySlug(string $slug)
    {
        return $this->recoveryService->getOnRecoveryBySlug($slug);
    }

    function createNewRecovery(array $data)
    {
        return $this->recoveryService->setRecovery($data);
    }

    function updateStatus(int $status, string $slug)
    {
        return $this->recoveryService->updateRecoveryStatus($status, $slug);
    }



    // Client Auth

    // Register
    function userActivateAccount(string $email, string $code)
    {
        return $this->recoveryService->registerUser($email, $code);
    }

    // Login
    function userAuth(string $email, string $password)
    {
        return $this->recoveryService->userAuthentication($email, $password);
    }


    // set Password
    function setPassword(int $user_id, string $password)
    {
        return $this->recoveryService->changePassword($user_id, $password);
    }

    // change Password
    function updatePassword(string $email, string $password, string $oldPassword)
    {
        return $this->recoveryService->editPassword($email, $password, $oldPassword);
    }


    // update amount 
    function changeCollectedAmount(string $slug, string $amount)
    {
        return $this->recoveryService->editCollectedAmount($slug, $amount);
    }


    // Delete Account

    function deleteUserAccount(string $slug)
    {
        return $this->recoveryService->deleteUser($slug);
    }



    function openWR(array $data)
    {
        return $this->recoveryService->setWR($data);
    }

    // getWRRejected
    function fetchRejected()
    {
        return $this->recoveryService->getWRRejected();
    }

    // getWRAccepted
    function fetchAccepted()
    {
        return $this->recoveryService->getWRAccepted();
    }

    // getWRNew
    function fetchNewWR()
    {
        return $this->recoveryService->getWRNew();
    }

    // getForUser
    function fetchUserWR(int $id)
    {
        return $this->recoveryService->getUserWR($id);
    }

    // editWR
    function editRejectWR(string $message, string $slug)
    {
        return $this->recoveryService->rejectWR($message, $slug);
    }
    // acceptRequest
    function editAcceptWR(int $user_id, string $slug, string $amount)
    {
        return $this->recoveryService->acceptWR($user_id, $slug, $amount);
    }
}
