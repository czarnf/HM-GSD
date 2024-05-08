<?php

namespace app\model;

use app\config\DatabaseHandler;
use app\utils\PasswordEncoder;


class Staff extends BaseModel
{
    private $table_name = 'staffs_tb';

    function __construct(DatabaseHandler $databaseHandler)
    {
        parent::__construct($databaseHandler);
    }


    // status type ::: delete = 4,

    function createStaff(array $payload)
    {
        $username = $payload["staff_number"];

        if ($this->isUser($username) === true) {
            $payload["staff_password"] = PasswordEncoder::encodePassword($payload["staff_password"]);
            $sql = "INSERT INTO $this->table_name(staff_slug, staff_name, staff_team_id, staff_number, staff_password, staff_role, staff_grade) 
                    VALUES(:staff_slug, :staff_name, :staff_team_id, :staff_number, :staff_password, :staff_role, :staff_grade)";
            $response = $this->insert($sql, $payload, ":staff_slug");
            return $response;
        }
        return "exist";
    }

    function fetchStaffByNumber(string $number)
    {
        $sql = "SELECT * from $this->table_name WHERE staff_number = ?";
        $response = $this->fetch($sql, [$number]);
        return $response;
    }

    function fetchStaffs()
    {
        $sql = "SELECT * FROM $this->table_name LEFT JOIN teams_tb ON $this->table_name.staff_team_id = teams_tb.team_id";
        $response = $this->fetchMany($sql);
        return $response;
    }

    function fetchStaffByTeamId(int $team_id)
    {
        $sql = "SELECT * FROM $this->table_name LEFT JOIN teams_tb ON $this->table_name.staff_team_id = teams_tb.team_id WHERE staff_team_id=? ";
        $response = $this->fetchMany($sql, [$team_id]);
        return $response;
    }


    function fetchStaffsByRole(string $role)
    {
        $sql = "SELECT * FROM $this->table_name WHERE staff_role=?";
        $response = $this->fetchMany($sql, [$role]);
        return $response;
    }

    function isUser(string $username): bool
    {
        $sql = "SELECT staff_slug from $this->table_name WHERE staff_number = ?";
        $payload = [$username];
        $stmt = $this->query($sql, $payload);
        return $stmt->rowCount() == 0;
    }
}
