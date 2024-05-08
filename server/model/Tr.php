<?php

namespace app\model;

use app\config\DatabaseHandler;


class Tr extends BaseModel
{
    private $table_name = 'treatment_records_tb';

    function __construct(DatabaseHandler $databaseHandler)
    {
        parent::__construct($databaseHandler);
    }

    function createTr(array $payload)
    {
        $id = $payload["tr_patient_id"];

        if ($this->isTr($id) === true) {
            $sql = "INSERT INTO $this->table_name(tr_slug, tr_patient_id, tr_staff_id) 
                    VALUES(:tr_slug, :tr_patient_id, :tr_staff_id)";
            $response = $this->insert($sql, $payload, ":tr_slug");
            return $response;
        } else {
            // update
            $updateUser = ["tr_staff_id" => $payload["tr_staff_id"], "tr_patient_id" => $payload["tr_patient_id"]];
            $sql = "UPDATE $this->table_name SET tr_staff_id = :tr_staff_id, updatedAt = :updatedAt WHERE tr_patient_id = :tr_patient_id";
            return $this->update($sql, $updateUser);
        }
    }

    function createRecord(array $payload)
    {
        $sql = "INSERT INTO records_tb(record_slug, record_tr_id, record_staff_id) VALUES(:record_slug, :record_tr_id, :record_staff_id)";
        $response = $this->insert($sql, $payload, ":record_slug");
        return $response;
    }

    function fetchTotalVisit(int $id)
    {
        $sql = "SELECT * from records_tb WHERE record_tr_id = ?";
        $response = $this->count($sql, [$id]);
        return $response;
    }


    function fetchDoctorVisits(int $patientId)
    {
        $sql = "SELECT records_tb.createdAt, staffs_tb.staff_name from $this->table_name LEFT JOIN records_tb ON $this->table_name.tr_id = records_tb.record_tr_id LEFT JOIN staffs_tb ON records_tb.record_staff_id = staffs_tb.staff_id WHERE tr_patient_id = ?";
        $response = $this->fetchMany($sql, [$patientId]);
        return $response;
    }

    function isTr(string $id): bool
    {
        $sql = "SELECT tr_slug from $this->table_name WHERE tr_patient_id = ?";
        $stmt = $this->query($sql, [$id]);
        return $stmt->rowCount() == 0;
    }
}
