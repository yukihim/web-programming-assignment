<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once '../core/Model.php';
// require_once '../public/index.php';

class Appointment extends Model {
    /**
     * Lấy danh sách các bệnh nhân sắp tới cho văn phòng bác sĩ hiện tại
     *
     * @return array|null
     * 
     */
    private $doctorOfficeId;
    private $available_time; // debug time

    public function __construct() {
        parent::__construct(); // Gọi constructor của class Model
        if (isset($_SESSION['doctor_office_id'])) {
            $this->doctorOfficeId = $_SESSION['doctor_office_id'];

        } else {
            $this->doctorOfficeId = null;
        }

        $this->available_time = '2024-11-18 08:00:00';
    }
    
    public function getUpcomingAppointments() {
        try {
            // Truy vấn dữ liệu, bao gồm cả trạng thái của cuộc hẹn
            $stmt = $this->db->prepare("
                SELECT 
                    u.name AS patient_name, 
                    ts.available_time AS time, 
                    a.status AS appointment_status  -- Add appointment status
                FROM appointments a
                INNER JOIN users u ON a.patient_id = u.id
                INNER JOIN time_slots ts ON a.time_slot_id = ts.id
                WHERE a.doctor_office_id = ? 
                AND ts.available_time >= NOW()
                ORDER BY ts.available_time ASC
            ");
    
            $stmt->execute([$this->doctorOfficeId]);
            $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            return $appointments ?: null;
        } catch (PDOException $e) {
            error_log("Database error in getUpcomingAppointments: " . $e->getMessage());
            return null;
        }
    }    

    public function getTodayPatientCount() {
        try {
            // Truy vấn dữ liệu
            $stmt = $this->db->prepare("
                SELECT COUNT(DISTINCT a.patient_id) AS patient_count
                FROM appointments a
                INNER JOIN time_slots ts ON a.time_slot_id = ts.id
                WHERE a.doctor_office_id = ? 
                AND ts.available_time >= NOW()
            ");

            $stmt->execute([$this->doctorOfficeId]);

            // Lấy dữ liệu
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['patient_count'] ?? 0;
        } catch (PDOException $e) {
            // Ghi log lỗi và trả về 0
            error_log("Database error in getTodayPatientCount: " . $e->getMessage());
            return 0;
        }
    }
    
}
