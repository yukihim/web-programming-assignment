<?php
require_once 'models/DoctorOfficeModel.php';
require_once 'models/TimeSlotModel.php';
require_once 'models/AppointmentModel.php';

class PatientController {
    public function showGuestPage() {
        session_start();
        if (!isset($_GET['isSignedIn']) || $_GET['user'] !== 'guest') {
            echo "<script>
                    alert('You must log in as a guest to access this page.');
                    window.location.href = 'index.php?page=signin';
                  </script>";
            exit();
        }
        $offices = DoctorOfficeModel::getAllOffices();
        include 'views/guest.php';
    }

    public function getTimeSlots() {
        $officeId = $_GET['office_id'];
        $timeSlots = TimeSlotModel::getTimeSlotsByOffice($officeId);
        header('Content-Type: application/json');
        echo json_encode($timeSlots);
    }

    public function bookAppointment() {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $patientId = $_SESSION['userid'];
            $doctorOfficeId = $_POST['doctor_office'];
            $timeSlotId = $_POST['time_slot'];
            $status = 'pending';
            $createdAt = date('Y-m-d H:i:s');

            if (AppointmentModel::bookAppointment($patientId, $doctorOfficeId, $timeSlotId, $status, $createdAt)) {
                header("Location: index.php?page=guest&isSignedIn=true&user=guest&message=Appointment+Booked");
            } else {
                header("Location: index.php?page=guest&isSignedIn=true&user=guest&message=Failed+to+Book+Appointment");
            }
        }
    }
}
?>
