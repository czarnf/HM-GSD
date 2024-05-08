<?php

namespace app\services;

use app\config\MysqlDBH;
use app\config\PasswordConfig;
use app\dto\ResponseDto;
use app\model\Staff;
use app\model\Team;
use app\model\Ward;
use app\utils\Helper as UtilsHelper;
use app\utils\PasswordEncoder;
use Helper;

class WardService
{

    private $model;

    function __construct()
    {
        $mysqlConnector = new MysqlDBH();
        $this->model = new Ward($mysqlConnector);
    }

    function setWard(array $data): string
    {
        $response = $this->model->createWard($data);
        if (is_bool($response)) {
            if ($response) {
                return ResponseDto::json(" New ward has been registered successfully.", 201);
            }
            return ResponseDto::json("An error was encountered while trying to register a new ward!", 500);
        }
        return ResponseDto::json("This ward name already exist in our system!", 422);
    }

    function getWards(): string
    {
        $response = $this->model->fetchWards();
        return ResponseDto::json($response);
    }


    function getTotalWard(): int
    {
        $response = $this->model->fetchWards();
        return count($response);
    }
}
