<!-- TODO: Implement staff page -->
<h2>Appointments</h2>
<div class="container-fluid w-100 row g-5">
    <?php 
        $mysql = mysqli_connect("localhost", "root", NULL, "medical_appointment", 3306, NULL);
        $query_result = NULL;
        try {
            $query_result = mysqli_query($mysql, "SELECT doctor_office, patient_name, phone_number, email, appointment_time, status FROM appointments;");
        } catch (\Throwable $th) {
            echo "Error: $th";
            die;
        }
        if ($query_result->num_rows){
            $data = mysqli_fetch_all($query_result);
            foreach($data as $appointment){
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
                echo "<div class=\"card p-0 col-6 col-xl-3 col-lg-4\">
                        <div class=\"card-header fs-3 fw-semibold\">
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