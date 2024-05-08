<?php

namespace app\model;

use app\config\DatabaseHandler;
use app\utils\PasswordEncoder;


class Patient extends BaseModel
{
    private $table_name = 'patients_tb';

    function __construct(DatabaseHandler $databaseHandler)
    {
        parent::__construct($databaseHandler);
    }

    function createPatient(array $payload)
    {
        $number = $payload["patient_number"];

        if ($this->isPatient($number) === true) {
            $payload["patient_password"] = PasswordEncoder::encodePassword($payload["patient_password"]);
            $sql = "INSERT INTO $this->table_name(patient_slug, patient_name, patient_number, patient_age, patient_gender, patient_ward_id, patient_team_id, patient_bed_no, patient_password) 
                    VALUES(:patient_slug, :patient_name, :patient_number, :patient_age, :patient_gender, :patient_ward_id, :patient_team_id, :patient_bed_no, :patient_password)";
            $response = $this->insert($sql, $payload, ":patient_slug");
            return $response;
        }
        return "exist";
    }

    function fetchPatientByNumber(string $number)
    {
        $sql = "SELECT * from $this->table_name LEFT JOIN teams_tb ON $this->table_name.patient_team_id = teams_tb.team_id LEFT JOIN treatment_records_tb ON $this->table_name.patient_id = treatment_records_tb.tr_patient_id LEFT JOIN staffs_tb ON treatment_records_tb.tr_staff_id = staffs_tb.staff_id LEFT JOIN wards_tb ON $this->table_name.patient_ward_id = wards_tb.ward_id  WHERE patient_number = ?";
        $response = $this->fetch($sql, [$number]);
        return $response;
    }

    function fetchPatientByAssignedDoctor(int $id)
    {
        $sql = "SELECT * from treatment_records_tb LEFT JOIN $this->table_name ON treatment_records_tb.tr_patient_id = $this->table_name.patient_id WHERE tr_staff_id = ? ";
        $response = $this->fetchMany($sql, [$id]);
        return $response;
    }



    function updatePatientStatus(string $status, string $no)
    {
        $updateUser = ["patient_status" => $status, "patient_number" => $no];
        $sql = "UPDATE $this->table_name SET patient_status = :patient_status, updatedAt = :updatedAt WHERE patient_number = :patient_number";
        return $this->update($sql, $updateUser);
    }


    function transferPatient(array $payload, bool $isTeam)
    {
        if ($isTeam) {
            $sql = "UPDATE $this->table_name SET patient_ward_id = :patient_ward_id, patient_team_id = :patient_team_id, updatedAt = :updatedAt WHERE patient_number = :patient_number";
        } else {
            $sql = "UPDATE $this->table_name SET patient_ward_id = :patient_ward_id, updatedAt = :updatedAt WHERE patient_number = :patient_number";
        }
        return $this->update($sql, $payload);
    }

    function deletePatient(string $slug)
    {
        $sql = "DELETE FROM $this->table_name WHERE patient_number = ?";
        $response = $this->delete($sql, [$slug]);
        return $response;
    }

    function fetchPatientByTeam(int $id)
    {
        $sql = "SELECT * FROM $this->table_name LEFT JOIN teams_tb ON $this->table_name.patient_team_id = teams_tb.team_id LEFT JOIN wards_tb ON $this->table_name.patient_ward_id = wards_tb.ward_id WHERE patient_team_id = ?";
        $response = $this->fetchMany($sql, [$id]);
        return $response;
    }

    function fetchPatientByWard(int $id)
    {
        $sql = "SELECT * FROM $this->table_name LEFT JOIN teams_tb ON $this->table_name.patient_team_id = teams_tb.team_id LEFT JOIN wards_tb ON $this->table_name.patient_ward_id = wards_tb.ward_id WHERE patient_ward_id = ?";
        $response = $this->fetchMany($sql, [$id]);
        return $response;
    }

    function fetchPatients()
    {
        $sql = "SELECT * FROM $this->table_name LEFT JOIN teams_tb ON $this->table_name.patient_team_id = teams_tb.team_id LEFT JOIN wards_tb ON $this->table_name.patient_ward_id = wards_tb.ward_id";
        $response = $this->fetchMany($sql);
        return $response;
    }

    function isPatient(string $number): bool
    {
        $sql = "SELECT patient_slug from $this->table_name WHERE patient_number = ?";
        $payload = [$number];
        $stmt = $this->query($sql, $payload);
        return $stmt->rowCount() == 0;
    }
}
