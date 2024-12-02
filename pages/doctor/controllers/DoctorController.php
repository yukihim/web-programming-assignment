<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../core/Controller.php';

class DoctorController extends Controller {
    private $doctorModel;
    private $appointmentModel;

    public function __construct() {
        $this->doctorModel = $this->loadModel('DoctorOffice');
        $this->appointmentModel = $this->loadModel('Appointment');
    }

    /**
     * Xử lý các hành động dựa trên action từ URL
     */
    public function handleRequest() {
        $action = $_GET['action'] ?? null; // Lấy action từ URL

        // Gọi phương thức tương ứng
        if ($action === 'fetchPatients') {
            $this->fetchPatients();
        } elseif ($action === 'fetchSessions') {
            $this->fetchSessions();
        } elseif ($action === 'createSession') {
            $this->createSession();
        } elseif ($action === 'fetchCounts') {
            $this->fetchCounts();
        } elseif ($action === 'deleteSession') {
            $this->deleteSession();
        } elseif ($action === 'editMaxSlots') {
            $this->editMaxSlots();
        } elseif ($action === 'getBookedPatientCount') {
            $this->getBookedPatientCount();
        } else {
            // $this->index();
        }
    }

    public function index() {
        // Lấy danh sách bệnh nhân
        $patients = $this->appointmentModel->getUpcomingAppointments();
    
        // Truyền dữ liệu đến view
        // check path views/doctor.php is correct

        $this->loadView('doctor.php', ['patients' => $patients]);
    }
    

    /**
     * Lấy danh sách bệnh nhân sắp tới
     */
    public function fetchPatients() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            try {
                $patients = $this->appointmentModel->getUpcomingAppointments();
                if ($patients) {
                    echo json_encode($patients);
                } else {
                    echo json_encode(['No patients found']);
                }
            } catch (Exception $e) {
                echo json_encode(['error' => 'Failed to fetch patients', 'details' => $e->getMessage()]);
            }
        } else {
            echo json_encode(['error' => 'Invalid request method']);
        }
    }

    /**
     * Lấy danh sách các session
     */
    public function fetchSessions() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            try {
                $sessions = $this->doctorModel->getAllSessions();
                echo json_encode(['success' => true, 'sessions' => $sessions]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Invalid request method']);
        }
    }
    

    /**
     * Tạo một session mới
     */
    public function createSession() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $time = $_POST['sessionTime'] ?? null;
            $maxSlots = $_POST['maxSlots'] ?? null;

            if ($time && $maxSlots) {
                $result = $this->doctorModel->createSession($time, $maxSlots);
                echo json_encode(['success' => $result]);
            } else {
                echo json_encode(['error' => 'Invalid input']);
            }
        } else {
            echo json_encode(['error' => 'Invalid request method']);
        }
    }

    public function fetchCounts() {
        try {
            // Fetch counts for today
            // $sessionCount = $this->doctorModel->getTodaySessionCount('2024-11-18');
            $sessionCount = $this->doctorModel->getTodaySessionCount();
            $patientCount = $this->appointmentModel->getTodayPatientCount();
    
            echo json_encode([
                'sessionsToday' => $sessionCount,
                'patientsToday' => $patientCount,
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'error' => 'Failed to fetch counts',
                'details' => $e->getMessage()
            ]);
        }
    }

    public function deleteSession() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sessionId'])) {
            $sessionId = $_POST['sessionId'];
            $success = $this->doctorModel->deleteSession($sessionId);
            echo json_encode(['success' => $success]);
        } else {
            echo json_encode(['error' => 'Invalid request']);
        }
    }
    
    
    public function editMaxSlots() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sessionId'], $_POST['maxSlots'])) {
            $sessionId = $_POST['sessionId'];
            $newMaxSlots = $_POST['maxSlots'];
            $success = $this->doctorModel->editMaxSlots($sessionId, $newMaxSlots);
            echo json_encode(['success' => $success]);
        } else {
            echo json_encode(['error' => 'Invalid request']);
        }
    }
    
    public function getBookedPatientCount() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['sessionId'])) {
            $sessionId = $_GET['sessionId'];
            $count = $this->doctorModel->getBookedPatientCount($sessionId);
            echo json_encode(['success' => true, 'bookedPatients' => $count]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request or missing sessionId']);
        }
    }
    
}

// Khởi tạo controller và xử lý request
$controller = new DoctorController();
$controller->handleRequest(); // Gọi phương thức xử lý action
