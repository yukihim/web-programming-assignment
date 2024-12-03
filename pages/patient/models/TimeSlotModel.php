<?php
require_once 'core/Database.php';

class TimeSlotModel {
    public static function getTimeSlotsByOffice($officeId) {
        $conn = Database::getConnection();
        $query = "SELECT * FROM time_slots WHERE doctor_office_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $officeId);
        $stmt->execute();
        $result = $stmt->get_result();

        $timeSlots = [];
        while ($slot = $result->fetch_assoc()) {
            $timeSlots[] = $slot;
        }
        return $timeSlots;
    }
}
?>
