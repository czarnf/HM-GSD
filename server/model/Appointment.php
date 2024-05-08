<?php

namespace app\model;

use app\config\DatabaseHandler;
use app\utils\PasswordEncoder;


class Appointment extends BaseModel
{
    private $table_name = 'appointments_tb';

    function __construct(DatabaseHandler $databaseHandler)
    {
        parent::__construct($databaseHandler);
    }

    function createAppointment(array $payload)
    {
        $phone = $payload["appointment_phone"];
        $email = $payload["appointment_email"];

        if ($this->isAppointment($phone, $email) === true) {
            $sql = "INSERT INTO $this->table_name(appointment_slug, appointment_name, appointment_team_id, appointment_phone, appointment_email, appointment_date, appointment_time, appointment_comment) 
                    VALUES(:appointment_slug, :appointment_name, :appointment_team_id, :appointment_phone, :appointment_email, :appointment_date, :appointment_time, :appointment_comment)";
            $response = $this->insert($sql, $payload, ":appointment_slug");
            return $response;
        }
        return "exist";
    }

    function fetchAppointmentBySlug(string $slug)
    {
        $sql = "SELECT * from $this->table_name LEFT JOIN teams_tb ON $this->table_name.appointment_team_id = teams_tb.team_id  WHERE appointment_slug = ?";
        $response = $this->fetch($sql, [$slug]);
        return $response;
    }

    function deleteAppointmentBySlug(string $slug)
    {
        $sql = "DELETE FROM $this->table_name WHERE appointment_slug = ?";
        $response = $this->delete($sql, [$slug]);
        return $response;
    }

    function updateAppointmentStatus(string $status, string $slug)
    {
        $updateUser = ["appointment_status" => $status, "appointment_slug" => $slug];
        $sql = "UPDATE $this->table_name SET appointment_status = :appointment_status, updatedAt = :updatedAt WHERE appointment_slug = :appointment_slug";
        return $this->update($sql, $updateUser);
    }

    function fetchAppointmentByTeam(int $id)
    {
        $sql = "SELECT * FROM $this->table_name LEFT JOIN teams_tb ON $this->table_name.appointment_team_id = teams_tb.team_id WHERE appointment_team_id = ?";
        $response = $this->fetchMany($sql, [$id]);
        return $response;
    }

    function fetchAppointments()
    {
        $sql = "SELECT * FROM $this->table_name LEFT JOIN teams_tb ON $this->table_name.appointment_team_id = teams_tb.team_id";
        $response = $this->fetchMany($sql);
        return $response;
    }

    function isAppointment(string $phone, string $email): bool
    {
        $sql = "SELECT appointment_slug from $this->table_name WHERE appointment_phone = ? || appointment_phone=?";
        $payload = [$phone, $email];
        $stmt = $this->query($sql, $payload);
        return $stmt->rowCount() == 0;
    }
}
