<?php
class TimeSlot {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getByOfficeId($officeId) {
        $stmt = $this->conn->prepare("SELECT * FROM time_slots WHERE doctor_office_id = ?");
        $stmt->bind_param("i", $officeId);
        $stmt->execute();
        return $stmt->get_result();
    }
}
?>
