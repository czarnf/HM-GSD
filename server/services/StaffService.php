<?php

namespace app\services;

use app\config\MysqlDBH;
use app\config\PasswordConfig;
use app\dto\ResponseDto;
use app\model\Staff;
use app\utils\Helper as UtilsHelper;
use app\utils\PasswordEncoder;
use Helper;

class StaffService
{

    private $model;

    function __construct()
    {
        $mysqlConnector = new MysqlDBH();
        $this->model = new Staff($mysqlConnector);
    }

    function setStaff(array $data): string
    {
        $response = $this->model->createStaff($data);
        if (is_bool($response)) {
            if ($response) {
                // send in email
                return ResponseDto::json(" New staff account has been created.", 201);
            }
            return ResponseDto::json("An error was encountered while trying to register staff!", 500);
        }
        return ResponseDto::json("This staff detail already exist in our system!", 422);
    }

    function getStaffs(): string
    {
        $response = $this->model->fetchStaffs();
        return ResponseDto::json($response);
    }

    function getStaffByTeam(int $id): string
    {
        $response = $this->model->fetchStaffByTeamId($id);
        return ResponseDto::json($response);
    }

    function staffAuth(string $username, string $password): string
    {
        $response = $this->model->fetchStaffByNumber($username);
        if (count($response) > 4) {
            $passwordHash = $response["staff_password"];
            if (PasswordEncoder::decodePassword($password, $passwordHash)) {
                $response["staff_password"] = "_____";
                return ResponseDto::json("Login was successful", 200, $response);
            }
            return ResponseDto::json("Your password is not recognized!");
        }
        return ResponseDto::json("Your staff number is not recognized!");
    }


    function getTotalStaffs(): int
    {
        $response = $this->model->fetchStaffs();
        return count($response);
    }

    function getTotalDoctors(): int
    {
        $response = $this->model->fetchStaffsByRole("doctor");
        $response2 = $this->model->fetchStaffsByRole("consultant");

        return count($response) + count($response2);
    }
}
