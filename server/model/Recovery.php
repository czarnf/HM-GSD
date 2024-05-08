<?php

namespace app\model;

use app\config\DatabaseHandler;


class Recovery extends BaseModel
{
    private $table_name = 'recovery_tb';
    private $table_on = 'on_tb';
    private $table_wr = 'withdraw_request_tb';

    function __construct(DatabaseHandler $databaseHandler)
    {
        parent::__construct($databaseHandler);
    }

    function createRecovery(array $payload)
    {
        if ($this->isRecovery($payload["recovery_email"]) == true) {
            $sql = "INSERT INTO $this->table_name(recovery_slug, recovery_name, recovery_email, recovery_phone, recovery_broker, recovery_amount) 
            VALUES( :recovery_slug, :recovery_name, :recovery_email, :recovery_phone, :recovery_broker, :recovery_amount)";
            $response = $this->insert($sql, $payload, "recovery_slug");
            return $response;
        }
        return "exist";
    }

    function fetchRecoveries()
    {
        $sql = "SELECT * FROM $this->table_name WHERE status = 0 || status = 1";
        $response = $this->fetchMany($sql);
        return $response;
    }

    function fetchOnRecoveries()
    {
        $sql = "SELECT * FROM $this->table_name LEFT JOIN on_tb ON on_tb.on_recovery_id = $this->table_name.recovery_id WHERE status = 1";
        $response = $this->fetchMany($sql);
        return $response;
    }

    function fetchRecoveryBySlug(string $slug)
    {
        $sql = "SELECT * FROM $this->table_name WHERE recovery_slug = ?";
        $response = $this->fetch($sql, [$slug]);
        return $response;
    }

    function editRecovery(string $slug, int $status)
    {
        $updateUser = ["status" => $status, "recovery_slug" => $slug];
        $sql = "UPDATE $this->table_name SET status = :status, updatedAt = :updatedAt WHERE recovery_slug = :recovery_slug";
        if ($status == 1) {
            $this->createOn($slug);
        }
        return $this->update($sql, $updateUser);
    }

    function isRecovery(string $email)
    {
        $sql = "SELECT recovery_slug, status from $this->table_name WHERE recovery_email = ?";
        $stmt = $this->query($sql, [$email]);
        if ($stmt->rowCount() > 0) {
            $response = $this->fetch($sql, [$email]);
            if ($response["status"] == 0) {
                return false;
            }
        }
        return true;
    }


    // ON TABLE

    function createOn(string $slug)
    {
        $res = $this->fetchRecoveryBySlug($slug);

        if (count($res) > 0) {
            $_id = $res["recovery_id"];
            $code = $this->generateOtp();
            $password = password_hash("abcd1234", PASSWORD_BCRYPT);
            $amount = 0;

            $data = ["on_amount" => $amount, "on_recovery_id" => $_id, "on_code" => $code, "on_password" => $password,];
            $sql = "INSERT INTO $this->table_on(on_slug, on_recovery_id, on_amount, on_code, on_password) VALUES( :on_slug, :on_recovery_id, :on_amount, :on_code, :on_password)";
            $response = $this->insert($sql, $data, "on_slug");
            return $response;
        }
        return false;
    }


    function updatePassword(int $id, string $password)
    {
        $updateUser = ["on_password" => $password, "on_recovery_id" => $id];
        $sql = "UPDATE $this->table_on SET on_password = :on_password, updatedAt = :updatedAt WHERE on_recovery_id = :on_recovery_id";
        return $this->update($sql, $updateUser);
    }

    function updateCollectedAmount(string $slug, string $amount)
    {
        $updateUser = ["on_amount" => $amount, "on_slug" => $slug];
        $sql = "UPDATE $this->table_on SET on_amount = :on_amount, updatedAt = :updatedAt WHERE on_slug = :on_slug";
        return $this->update($sql, $updateUser);
    }

    function updateAmountUserId(int $id, string $amount)
    {
        $updateUser = ["on_amount" => $amount, "on_id" => $id];
        $sql = "UPDATE $this->table_on SET on_amount = :on_amount, updatedAt = :updatedAt WHERE on_recovery_id = :on_id";
        return $this->update($sql, $updateUser);
    }

    function fetchOn(string $email)
    {
        $sql = "SELECT * FROM $this->table_name LEFT JOIN on_tb ON on_tb.on_recovery_id = $this->table_name.recovery_id WHERE status = ? AND recovery_email=?";
        $response = $this->fetch($sql, [1, $email]);
        return $response;
    }

    function fetchBySlug(string $slug)
    {
        $sql = "SELECT * FROM $this->table_name LEFT JOIN on_tb ON on_tb.on_recovery_id = $this->table_name.recovery_id WHERE recovery_slug=?";
        $response = $this->fetch($sql, [$slug]);
        return $response;
    }



    // ------- --- withdraw Request

    // FetchWRsByStatus

    function fetchWRByStatus(int $status)
    {
        $sql = "SELECT $this->table_wr.*, $this->table_name.recovery_name, $this->table_on.on_amount FROM $this->table_wr LEFT JOIN $this->table_name ON $this->table_wr.wr_user_id = $this->table_name.recovery_id LEFT JOIN $this->table_on ON $this->table_wr.wr_user_id = $this->table_on.on_recovery_id WHERE wr_status = ?";
        $response = $this->fetchMany($sql, [$status]);
        return $response;
    }

    function fetchWRByUserId(int $id)
    {
        $sql = "SELECT $this->table_wr.*, $this->table_name.recovery_name FROM $this->table_wr LEFT JOIN $this->table_name ON $this->table_wr.wr_user_id = $this->table_name.recovery_id WHERE wr_user_id = ? ORDER BY $this->table_wr.createdAt DESC";
        $response = $this->fetchMany($sql, [$id]);
        return $response;
    }

    // updateWRStatus

    function updateWRStatus(array $payload)
    {
        $sql = "UPDATE $this->table_wr SET wr_status = :wr_status, wr_message = :wr_message, updatedAt = :updatedAt WHERE wr_slug = :wr_slug";
        return $this->update($sql, $payload);
    }

    function updateWRStatusByUserId(array $payload) // REMOVE
    {
        $sql = "UPDATE $this->table_wr SET wr_status = :wr_status, wr_message = :wr_message, updatedAt = :updatedAt WHERE wr_user_id = :wr_user_id";
        return $this->update($sql, $payload);
    }

    function updateWRStatusBySlug(array $payload)
    {
        $sql = "UPDATE $this->table_wr SET wr_status = :wr_status, wr_message = :wr_message, updatedAt = :updatedAt WHERE wr_slug = :wr_slug";
        return $this->update($sql, $payload);
    }

    // createWR
    function createWR(array $payload)
    {
        if ($this->isWROn($payload["wr_user_id"]) == 0) {
            $sql = "INSERT INTO $this->table_wr(wr_slug, wr_user_id, wr_amount, wr_address, wr_account_type) VALUES( :wr_slug, :wr_user_id, :wr_amount, :wr_address, :wr_account_type)";
            $response = $this->insert($sql, $payload, "wr_slug");
            return $response;
        }
        return "exist";
    }


    function isOnByCode(string $code)
    {
        $sql = "SELECT on_slug FROM $this->table_on WHERE on_code = ? ";
        return $this->count($sql, [$code]);
    }

    function isWROn(string $userId)
    {
        $sql = "SELECT wr_slug FROM $this->table_wr WHERE wr_user_id = ? AND wr_status = ?";
        return $this->count($sql, [$userId, 0]);
    }


    function generateOtp()
    {
        $randomNumber = rand(100000, 999999);
        return $randomNumber;
    }



    // WR


    // get


}
