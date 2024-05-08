<?php

namespace app\model;

use app\config\DatabaseHandler;
use app\utils\PasswordEncoder;


class Team extends BaseModel
{
    private $table_name = 'teams_tb';

    function __construct(DatabaseHandler $databaseHandler)
    {
        parent::__construct($databaseHandler);
    }

    function createTeam(array $payload)
    {
        $name = $payload["team_name"];

        if ($this->isTeam($name) === true) {
            $sql = "INSERT INTO $this->table_name(team_slug, team_name, team_desc) 
                    VALUES(:team_slug, :team_name, :team_desc)";
            $response = $this->insert($sql, $payload, ":team_slug");
            return $response;
        }
        return "exist";
    }

    function fetchTeamById(int $id)
    {
        $sql = "SELECT * from $this->table_name WHERE team_id = ?";
        $response = $this->fetch($sql, [$id]);
        return $response;
    }

    function fetchTeams()
    {
        $sql = "SELECT * FROM $this->table_name";
        $response = $this->fetchMany($sql);
        return $response;
    }

    function isTeam(string $name): bool
    {
        $sql = "SELECT team_slug from $this->table_name WHERE team_name = ?";
        $payload = [$name];
        $stmt = $this->query($sql, $payload);
        return $stmt->rowCount() == 0;
    }



    
}
