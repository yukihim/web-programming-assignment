<!-- TODO: Implement staff page -->
<h2>Appointments</h2>
<div class="container-fluid w-100 row g-5">
    <?php 
        $mysql = mysqli_connect("localhost", "root", NULL, "medical_appointment", 3306, NULL);
        $query_result = NULL;
        try {
            $query_result = mysqli_query($mysql, "SELECT patient_id, doctor_office_id, time_slot_id, status, created_at FROM appointments;");
        } catch (\Throwable $th) {
            echo "Error: $th";
            die;
        }
        if ($query_result->num_rows){
            $data = mysqli_fetch_all($query_result);
            foreach($data as $appointment){
                $doctor_office_id = $appointment[1];
                $patient_id = $appointment[0];
                $time_slot_id = $appointment[2];
                $doctor_office_name = "";
                $patient_name = "";
                $time_slot = "";
                $email = ""; $phone = "";
                try {
                    $query_result = mysqli_query($mysql, "SELECT name FROM doctor_offices WHERE id = $doctor_office_id;");
                    if ($query_result->num_rows) 
                        $doctor_office_name = mysqli_fetch_all($query_result)[0][0];
                    $query_result = mysqli_query($mysql, "SELECT name, email, phone FROM users WHERE id = $patient_id");
                    if ($query_result->num_rows) {
                        $temp = mysqli_fetch_all($query_result)[0];
                        $patient_name = $temp[0];
                        $email = $temp[1];
                        $phone = $temp[2];
                    }
                    $query_result = mysqli_query($mysql, "SELECT available_time FROM time_slots WHERE id = $time_slot_id");
                    if ($query_result->num_rows) 
                        $time_slot = mysqli_fetch_all($query_result)[0][0];
                } catch (\Throwable $th) {
                    echo "Error: $th";
                    die;
                }
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
                $status_color = "";
                switch ($appointment[3]) {
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
                echo "<div class=\"card p-0 col-6 col-xl-3 col-lg-4\">
                        <div class=\"card-header fs-3 fw-semibold\">
                            $doctor_office_name
                        </div>
                        <div class=\"card-body\">
                            <h5 class=\"card-title\">$time_slot</h5>
                            <p class=\"card-text\">
                                Name: $patient_name
                            </p>
                            <p class=\"card-text\">
                                Phone: $phone
                            </p>
                            <p class=\"card-text\">
                                Email: $email
                            </p>
                            <strong class=\"card-text $status_color fs-5\">
                                $appointment[3]
                            </strong>
                        </div>
                        <div class=\"card-footer\">
                            Appointment was made $display_when
                        </div>
                    </div>";
            }
        }
    ?>
</div>

<!-- 
appointment:
    doctor office
    time slot
    name
    phone number
    email
  -->