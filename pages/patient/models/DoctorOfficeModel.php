<?php
require_once 'core/Database.php';

class DoctorOfficeModel {
    public static function getAllOffices() {
        $conn = Database::getConnection();
        $query = "SELECT * FROM doctor_offices";
        return $conn->query($query);
    }
}
?>
