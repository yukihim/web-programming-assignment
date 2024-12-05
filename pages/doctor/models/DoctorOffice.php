<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../core/Model.php';

class DoctorOffice extends Model {
    public function __construct() {
        parent::__construct();

        if (!isset($_SESSION['doctor_office_id'])) {
            header('Location: /doctor/login.php');
            exit;
        }

        $this->doctorOfficeId = $_SESSION['doctor_office_id'];

    }

    public function getAllSessions() {
        try {
            // SQL query to fetch all sessions for the current doctor office
            $sql = "
                SELECT 
                    ts.id AS session_id,                 -- Unique ID of the session
                    ts.available_time,                  -- Session start time
                    ts.max_slots,                       -- Maximum number of slots for the session
                    COUNT(a.patient_id) AS booked_slots         -- Number of booked slots for the session
                FROM 
                    time_slots ts
                LEFT JOIN 
                    appointments a 
                ON 
                    ts.id = a.time_slot_id 
                    AND a.status = 'confirmed'
                    AND a.doctor_office_id = ts.doctor_office_id
                WHERE 
                    ts.doctor_office_id = ?
                GROUP BY 
                    ts.id, ts.available_time, ts.max_slots
                ORDER BY 
                    ts.available_time ASC
            ";
            
            // Prepare and execute the statement
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$_SESSION['doctor_office_id']]);
            
            // Fetch and return the results
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Log any error and return an empty array as fallback
            error_log("Error fetching sessions: " . $e->getMessage());
            return [];
        }
    }

    
    public function createSession($time, $maxSlots) {
        $stmt = $this->db->prepare("INSERT INTO time_slots (doctor_office_id, available_time, max_slots) VALUES (?, ?, ?)");
        return $stmt->execute([$_SESSION['doctor_office_id'], $time, $maxSlots]);
    }

    // public function getTodaySessionCount($date) {
    //     $sql = "SELECT COUNT(*) AS count 
    //             FROM time_slots 
    //             WHERE DATE(available_time) = :selected_date 
    //             AND doctor_office_id = :doctor_office_id";
    //     $stmt = $this->db->prepare($sql);
    //     $stmt->bindParam(':selected_date', $date, PDO::PARAM_STR); // Ngày được truyền vào
    //     $stmt->bindParam(':doctor_office_id', $_SESSION['doctor_office_id'], PDO::PARAM_INT);
    //     $stmt->execute();
    //     $result = $stmt->fetch(PDO::FETCH_ASSOC);
    //     return $result['count'] ?? 0;
    // }
    
    public function getTodaySessionCount() {
        $sql = "SELECT COUNT(*) AS count 
                FROM time_slots 
                WHERE DATE(available_time) = CURDATE() 
                AND doctor_office_id = :doctor_office_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':doctor_office_id', $_SESSION['doctor_office_id'], PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        error_log("Debug result: " . print_r($result, true)); // Thêm để kiểm tra kết quả trả về
        return $result['count'] ?? 0;
    }
    
    public function deleteSession($sessionId) {
        try {
            // Bắt đầu transaction
            $this->db->beginTransaction();
    
            // Xóa các bản ghi liên quan trong bảng appointments
            $stmtAppointments = $this->db->prepare("DELETE FROM appointments WHERE time_slot_id = ? AND doctor_office_id = ?");
            $stmtAppointments->execute([$sessionId, $_SESSION['doctor_office_id']]);
    
            // Xóa bản ghi trong bảng time_slots
            $stmtTimeSlots = $this->db->prepare("DELETE FROM time_slots WHERE id = ? AND doctor_office_id = ?");
            $stmtTimeSlots->execute([$sessionId, $_SESSION['doctor_office_id']]);
    
            // Commit transaction nếu thành công
            $this->db->commit();
    
            return $stmtTimeSlots->rowCount() > 0;
        } catch (PDOException $e) {
            // Rollback transaction nếu có lỗi
            $this->db->rollBack();
            error_log("Error deleting session and related data: " . $e->getMessage());
            return false;
        }
    }
    
    public function editMaxSlots($sessionId, $newMaxSlots) {
        try {
            $stmt = $this->db->prepare("UPDATE time_slots SET max_slots = ? WHERE id = ? AND doctor_office_id = ?");
            $stmt->execute([$newMaxSlots, $sessionId, $_SESSION['doctor_office_id']]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error updating max_slots: " . $e->getMessage());
            return false;
        }
    }

    public function getBookedPatientCount($sessionId) {
        try {
            // SQL query to count the number of distinct booked patients for a given session and doctor office
            $stmt = $this->db->prepare("
                SELECT COUNT(DISTINCT a.patient_id) AS booked_patient_count
                FROM appointments a
                INNER JOIN time_slots ts ON a.time_slot_id = ts.id
                WHERE a.time_slot_id = ? 
                AND a.doctor_office_id = ? 
                AND a.status = 'confirmed'  -- Only confirmed appointments are counted
            ");
            
            // Execute the query with sessionId and doctor office ID
            $stmt->execute([$sessionId, $_SESSION['doctor_office_id']]);
    
            // Fetch the result
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
            // Return the count of booked patients, defaulting to 0 if no results
            return $result['booked_patient_count'] ?? 0;
        } catch (PDOException $e) {
            // Log any error and return 0 as fallback
            error_log("Error fetching booked patients count: " . $e->getMessage());
            return 0;
        }
    }
    
}
