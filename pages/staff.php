<?php
// Start the session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<h2>Appointments</h2>
<div class="container-fluid w-100 row g-5" style="min-height:calc(100vh - 20.5em)">
    <?php 
        // Kết nối đến cơ sở dữ liệu
        $mysql = mysqli_connect("localhost", "root", NULL, "medical_appointment", 3306, NULL);
        $query_result = NULL;
        try {
            // Truy vấn lấy thông tin lịch hẹn từ các bảng appointments, time_slots, và doctor_offices
            $query_result = mysqli_query($mysql, "
                SELECT 
                    doctor_offices.name AS doctor_office,
                    users.name AS patient_name,
                    users.phone AS phone_number,
                    users.email AS email,
                    time_slots.available_time AS appointment_time,
                    appointments.status 
                FROM appointments
                JOIN time_slots ON appointments.time_slot_id = time_slots.id
                JOIN doctor_offices ON appointments.doctor_office_id = doctor_offices.id
                JOIN users ON appointments.patient_id = users.id;
            ");
        } catch (\Throwable $th) {
            echo "Error: $th";
            die;
        }

        // Kiểm tra và hiển thị thông tin các lịch hẹn
        if ($query_result->num_rows) {
            $data = mysqli_fetch_all($query_result);
            foreach ($data as $appointment) {
                // Tính toán thời gian đã tạo lịch hẹn
                $appointment_time = new DateTime($appointment[4]);
                $now = new DateTime('now');
                $created_when = $now->diff($appointment_time);
                $M = $created_when->m;
                $d = $created_when->d;
                $h = $created_when->h;
                $m = $created_when->i;
                $s = $created_when->s;
                $display_when = "";
                if ($M) $display_when = "$M months ago";
                else if ($d) $display_when = "$d days ago";
                else if ($h) $display_when = "$h hours ago";
                else if ($m) $display_when = "$m minutes ago";
                else $display_when = "$s seconds ago";

                // Xác định màu trạng thái
                $status_color = "";
                switch ($appointment[5]) {
                    case 'pending':
                        $status_color = "text-warning";
                        break;
                    case 'confirmed':
                        $status_color = "text-success";
                        break;
                    default:
                        $status_color = "text-danger";
                        break;
                }

                // Hiển thị thông tin lịch hẹn
                echo "<div class=\"card p-0 col-6 col-xl-3 col-lg-4 border-black\">
                        <div class=\"card-header fs-3 fw-semibold\" style=\"background-color: #80FFDB; border-top-left-radius: 15px; border-top-right-radius: 15px;\">
                            $appointment[0]
                        </div>
                        <div class=\"card-body\">
                            <h5 class=\"card-title\">$appointment[4]</h5>
                            <p class=\"card-text\">
                                Name: $appointment[1]
                            </p>
                            <p class=\"card-text\">
                                Phone: $appointment[2]
                            </p>
                            <p class=\"card-text\">
                                Email: $appointment[3]
                            </p>
                            <strong class=\"card-text $status_color fs-5\">
                                $appointment[5]
                            </strong>
                        </div>
                        <div class=\"card-footer\" style=\"background-color: #5E60CE; color: white; border-bottom-left-radius: 15px; border-bottom-right-radius: 15px;\">
                            Appointment was made $display_when
                        </div>
                    </div>";
            }
        }
    ?>
</div>
