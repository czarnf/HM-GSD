<?php

namespace app\model;

use app\config\DatabaseHandler;
use app\utils\PasswordEncoder;


class User extends BaseModel
{
    private $table_name = 'users_tb';

    function __construct(DatabaseHandler $databaseHandler)
    {
        parent::__construct($databaseHandler);
    }


    // status type ::: delete = 4,

    function createUser(array $payload)
    {
        $email = $payload["user_email"];
        $username = $payload["user_username"];

        if ($this->isUser($username, $email) === true) {
            $payload["user_password"] = PasswordEncoder::encodePassword($payload["user_password"]);
            $sql = "INSERT INTO $this->table_name(user_slug, user_username, user_email, user_role, user_password, user_country) 
                    VALUES(:user_slug, :user_username, :user_email, :user_role, :user_password, :user_country)";
            $response = $this->insert($sql, $payload, "user_slug");
            return $response;
        }
        return "exist";
    }
    function updateUserPassword(array $payload)
    {
        $payload["user_password"] = PasswordEncoder::encodePassword($payload["user_password"]);
        $sql = "UPDATE $this->table_name SET user_password = :user_password, updatedAt = :updatedAt WHERE user_slug = :user_slug";
        if ($this->update($sql, $payload)) {
            return true;
        }
        return false;
    }

    function deleteUser(string $slug)
    {
        $updateUser = ["status" => 4, "user_slug" => $slug];
        $sql = "UPDATE $this->table_name SET user_status = :user_status, updatedAt = :updatedAt WHERE user_slug = :user_slug";
        return $this->update($sql, $updateUser);
    }

    function fetchUsers()
    {
        $sql = "SELECT * FROM $this->table_name";
        $response = $this->fetchMany($sql);
        return $response;
    }

    function fetchUserByRole(string $role)
    {
        $sql = "SELECT * from $this->table_name WHERE user_role = ?";
        $response = $this->fetchMany($sql, [$role]);
        return $response;
    }

    function fetchUserByEmail(string $email)
    {
        $sql = "SELECT * from $this->table_name WHERE user_email = ?";
        $response = $this->fetch($sql, [$email]);
        return $response;
    }


    function fetchUserBySlug(string $slug)
    {
        $sql = "SELECT * from $this->table_name WHERE user_slug = ?";
        $response = $this->fetch($sql, [$slug]);
        return $response;
    }

    function fetchUserByUsername(string $username)
    {
        $sql = "SELECT * from $this->table_name WHERE user_username = ?";
        $response = $this->fetch($sql, [$username]);
        return $response;
    }

    function fetchRecentUsers()
    {
        $sql = "SELECT * FROM $this->table_name WHERE user_role = ? ORDER BY $this->table_name.createdAt desc limit 8";
        $response = $this->fetchMany($sql, ["user"]);
        return $response;
    }

    function verifyUser(string $slug)
    {
        $updateUser = ["user_is_verified" => 1, "user_slug" => $slug];
        $sql = "UPDATE $this->table_name SET user_is_verified = :user_is_verified, updatedAt = :updatedAt WHERE user_slug = :user_slug";
        return $this->update($sql, $updateUser);
    }
    function editUserLogInStatus(string $slug, int $status)
    {
        $updateUser = ["user_is_logged_in" => $status, "user_slug" => $slug];
        $sql = "UPDATE $this->table_name SET user_is_logged_in = :user_is_logged_in, updatedAt = :updatedAt WHERE user_slug = :user_slug";
        return $this->update($sql, $updateUser);
    }
    function editUserStatus(string $status, string $slug)
    {
        $updateUser = ["status" => $status, "user_slug" => $slug];
        $sql = "UPDATE $this->table_name SET user_status = :user_status, updatedAt = :updatedAt WHERE user_slug = :user_slug";
        return $this->update($sql, $updateUser);
    }

    function isUser(string $username, string $email): bool
    {
        $sql = "SELECT user_slug from $this->table_name WHERE user_username = ? OR user_email=?";
        $payload = [$username, $email];
        $stmt = $this->query($sql, $payload);
        return $stmt->rowCount() == 0;
    }


    // fetch Settings
    function fetchSettings()
    {
        $sql = "SELECT * FROM setting_tb ";
        $response = $this->fetch($sql);
        return $response;
    }
    // update settings
    function updateSetting(array $payload)
    {
        $sql = "UPDATE setting_tb SET setting_telegram = :setting_telegram, setting_phone = :setting_phone,  setting_email = :setting_email,  updatedAt = :updatedAt WHERE setting_id = :setting_id";
        if ($this->update($sql, $payload)) {
            return true;
        }
        return false;
    }
}
