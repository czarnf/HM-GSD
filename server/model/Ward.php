<?php

namespace app\model;

use app\config\DatabaseHandler;
use app\utils\PasswordEncoder;


class Ward extends BaseModel
{
    private $table_name = 'wards_tb';

    function __construct(DatabaseHandler $databaseHandler)
    {
        parent::__construct($databaseHandler);
    }

    function createWard(array $payload)
    {
        $name = $payload["ward_name"];

        if ($this->isWard($name) === true) {
            $sql = "INSERT INTO $this->table_name(ward_slug, ward_name, ward_total_bed, ward_type, ward_gender) 
                    VALUES(:ward_slug, :ward_name, :ward_total_bed, :ward_type, :ward_gender)";
            $response = $this->insert($sql, $payload, ":ward_slug");
            return $response;
        }
        return "exist";
    }

    function fetchWardById(int $id)
    {
        $sql = "SELECT * from $this->table_name WHERE ward_id = ?";
        $response = $this->fetch($sql, [$id]);
        return $response;
    }

    function fetchWards()
    {
        $sql = "SELECT * FROM $this->table_name";
        $response = $this->fetchMany($sql);
        return $response;
    }

    function isWard(string $name): bool
    {
        $sql = "SELECT ward_slug from $this->table_name WHERE ward_name = ?";
        $payload = [$name];
        $stmt = $this->query($sql, $payload);
        return $stmt->rowCount() == 0;
    }
}
