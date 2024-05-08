<?php

namespace app\model;

use app\config\DatabaseHandler;


class Transaction extends BaseModel
{
    private $table_name = 'transaction_tb';
    private $table_with = 'withdraw_tb';

    function __construct(DatabaseHandler $databaseHandler)
    {
        parent::__construct($databaseHandler);
    }


    // Status 0 = Not seen, Status = 1, Read, Status = 2, Confirmed, Status , status 4 - Delete


    function createTransaction(array $payload)
    {
        if ($this->isTransaction($payload["transaction_message"], $payload["transaction_user_id"]) == true) {
            $sql = "INSERT INTO $this->table_name(transaction_slug, transaction_message, transaction_user_id) 
            VALUES( :transaction_slug, :transaction_message, :transaction_user_id)";
            $response = $this->insert($sql, $payload, "transaction_slug");
            return $response;
        }
        return "exist";
    }

    function updateTransactionStatus(string $slug, int $status)
    {
        $updatePayload = ["transaction_status" => $status, "transaction_slug" => $slug];
        $sql = "UPDATE $this->table_name SET transaction_status = :transaction_status, updatedAt = :updatedAt WHERE transaction_slug = :transaction_slug";
        return $this->update($sql, $updatePayload);
    }

    function fetchTransactionsByUserId(int $userId)
    {
        $sql = "SELECT * FROM $this->table_name WHERE transaction_user_id = ? ORDER BY createdAt DESC";
        $response = $this->fetchMany($sql, [$userId]);
        return $response;
    }

    function fetchTransactions()
    {
        $sql = "SELECT * FROM $this->table_name";
        $response = $this->fetchMany($sql);
        return $response;
    }

    function createWithdrawal(array $payload)
    {
        if ($this->isWithdraw($payload["withdraw_message"], $payload["withdraw_user_id"]) == true) {

            $sql = "INSERT INTO $this->table_with(withdraw_slug, withdraw_message, withdraw_user_id) 
            VALUES( :withdraw_slug, :withdraw_message, :withdraw_user_id)";
            $response = $this->insert($sql, $payload, "withdraw_slug");
            return $response;
        }
        return "exist";
    }

    function updateWithdrawalStatus(string $slug, int $status)
    {
        $updatePayload = ["withdraw_status" => $status, "withdraw_slug" => $slug];
        $sql = "UPDATE $this->table_with SET withdraw_status = :withdraw_status, updatedAt = :updatedAt WHERE withdraw_slug = :withdraw_slug";
        return $this->update($sql, $updatePayload);
    }

    function fetchWithdrawalByUserId(string $userId)
    {
        $sql = "SELECT * FROM $this->table_with WHERE withdraw_user_id = ? AND withdraw_status != ? ORDER BY createdAt";
        $response = $this->fetchMany($sql, [$userId, 4]);

        return $response;
    }

    function fetchWithdrawalByUserIdApp(string $userId)
    {
        $sql = "SELECT * FROM $this->table_with WHERE withdraw_user_id = ? AND withdraw_status = ?";
        $response = $this->fetchMany($sql, [$userId, 0]);

        return $response;
    }


    function fetchWithdrawals()
    {
        $sql = "SELECT * FROM $this->table_with";
        $response = $this->fetchMany($sql);
        return $response;
    }

    function isWithdraw(string $message, int $userId)
    {
        $sql = "SELECT withdraw_slug FROM $this->table_with WHERE withdraw_message = ?  AND withdraw_user_id = ? AND withdraw_status = ?";
        return $this->count($sql, [$message, $userId, 0]) == 0;
    }

    function isTransaction(string $message, int $userId)
    {
        $sql = "SELECT transaction_slug FROM $this->table_name WHERE transaction_message = ?  AND transaction_user_id = ?";
        return $this->count($sql, [$message, $userId]) == 0;
    }
}
