<?php

namespace app\controller;

use app\services\TransactionService;

class TransactionController
{

    private $service;

    function __construct()
    {
        $this->service = new TransactionService();
    }

    function fetchTransactions()
    {
        return $this->service->getTransactions();
    }
    function fetchTransactionByUserId(int $id)
    {
        return $this->service->getTransactionByUserId($id);
    }
    function markTransactionAsRead(string $slug)
    {
        return $this->service->updateTransactionStatus(1, $slug);
    }
    function deleteTransaction(string $slug)
    {
        return $this->service->updateTransactionStatus(4, $slug);
    }
    function newTransaction(array $request)
    {
        return $this->service->setTransaction($request);
    }

    function fetchWithdrawal()
    {
        return $this->service->getWithdrawals();
    }
    function fetchWithdrawalByUserId(int $id)
    {
        return $this->service->getWithdrawalByUserId($id);
    }

    function fetchWithdrawalByUserIdApp(int $id)
    {
        return $this->service->getWithdrawalByUserIdApp($id);
    }

    function markWithdrawalAsRead(string $slug)
    {
        return $this->service->updateWithdrawalStatus($slug, 1);
    }
    function deleteWithdrawal(string $slug)
    {
        return $this->service->updateWithdrawalStatus(4, $slug);
    }
    function newWithdrawal(array $request)
    {
        return $this->service->setWithdrawal($request);
    }
}
