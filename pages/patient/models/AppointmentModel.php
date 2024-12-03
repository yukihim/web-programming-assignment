<?php
require_once 'core/Database.php';

class AppointmentModel {
    public static function bookAppointment($patientId, $doctorOfficeId, $timeSlotId, $status, $createdAt) {
        $conn = Database::getConnection();
        $query = "INSERT INTO appointments (patient_id, doctor_office_id, time_slot_id, status, created_at) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iiiss", $patientId, $doctorOfficeId, $timeSlotId, $status, $createdAt);
        return $stmt->execute();
    }
}
?>
