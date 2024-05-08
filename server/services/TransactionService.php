<?php

namespace app\services;

use app\config\MysqlDBH;
use app\dto\ResponseDto;
use app\model\Transaction;
use app\services\impl\TransactionServiceImpl;
use app\utils\PasswordEncoder;

class TransactionService implements TransactionServiceImpl
{

    private $model;
    private $mail;

    function __construct()
    {
        $mysqlConnector = new MysqlDBH();
        $this->model = new Transaction($mysqlConnector);
    }


    function setTransaction(array $request): string
    {
        $response = $this->model->createTransaction($request);
        if (is_bool($response)) {
            if ($response) {
                return ResponseDto::json("Transaction has been created successfully!", 201);
            }
            return ResponseDto::json("Unable to create this transaction at this point. Please contact the developer is issue persist!", 200);
        }
        return ResponseDto::json("This exact transaction has been created. Please try charging the transaction message.", 302);
    }
    function getTransactionByUserId(int $id): string
    {
        $response = $this->model->fetchTransactionsByUserId($id);
        return ResponseDto::json($response, 200);
    }

    function getTransactions(): string
    {
        $response = $this->model->fetchTransactions();
        return ResponseDto::json($response, 200);
    }
    function updateTransactionStatus(int $status, string $slug): string
    {
        $response = $this->model->updateTransactionStatus($slug, $status);
        if ($response) {
            return ResponseDto::json("This recovery process status has been successfully updated!", 200);
        }
        return ResponseDto::json("An error was encountered while trying to update transaction status...", 500);
    }

    function setWithdrawal(array $request): string
    {
        $response = $this->model->createWithdrawal($request);
        if (is_bool($response)) {
            if ($response) {
                return ResponseDto::json("Withdrawal message has been created successfully!", 201);
            }
            return ResponseDto::json("Unable to process this withdrawal at this point. Please contact the developer if issue persist!", 200);
        }
        return ResponseDto::json("This exact withdrawal message has been created. Please try charging the withdrawal message.", 302);
    }

    function getWithdrawalByUserId(int $id): string
    {
        $response = $this->model->fetchWithdrawalByUserId($id);
        return ResponseDto::json($response, 200);
    }

    function getWithdrawalByUserIdApp(int $id): string
    {
        $response = $this->model->fetchWithdrawalByUserIdApp($id);
        return ResponseDto::json($response, 200);
    }

    function getWithdrawals(): string
    {
        $response = $this->model->fetchWithdrawals();
        return ResponseDto::json($response, 200);
    }
    function updateWithdrawalStatus(string $slug, int $status): string
    {
        $response = $this->model->updateWithdrawalStatus($slug, $status);
        if ($response) {
            return ResponseDto::json("This transaction status has been successfully updated!", 200);
        }
        return ResponseDto::json("An error was encountered while trying to update transaction status...", 500);
    }
}
