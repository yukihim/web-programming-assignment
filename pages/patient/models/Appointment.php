<?php
class Appointment {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function book($patientId, $doctorOffice, $timeSlot, $status, $createdAt) {
        $stmt = $this->conn->prepare("INSERT INTO appointments (patient_id, doctor_office_id, time_slot_id, status, created_at) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iiiss", $patientId, $doctorOffice, $timeSlot, $status, $createdAt);
        return $stmt->execute();
    }
}
?>
