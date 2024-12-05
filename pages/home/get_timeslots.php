<?php

if (!isset($_SESSION)) {
    session_start();
}

// Kết nối đến cơ sở dữ liệu
$mysql = mysqli_connect("localhost", "root", NULL, "medical_appointment", 3306, NULL);

// Kiểm tra nếu có nhận được ID văn phòng bác sĩ
if (isset($_GET['doctor_office'])) {
    $doctor_office_id = mysqli_real_escape_string($mysql, $_GET['doctor_office']);

    // Lấy các khung giờ có sẵn cho bác sĩ đã chọn và có thời gian >= ngày hiện tại
    $query = "
        SELECT t.id, t.available_time, t.max_slots, 
               (t.max_slots - IFNULL(COUNT(a.patient_id), 0)) AS available_slots
        FROM time_slots t
        LEFT JOIN appointments a ON t.id = a.time_slot_id AND a.status = 'confirmed'
        WHERE t.doctor_office_id = '$doctor_office_id' 
          AND t.can_book = TRUE 
          AND t.available_time >= NOW()  -- is set >= now
        GROUP BY t.id
    ";

    $result = mysqli_query($mysql, $query);

    $options = [];  // Mảng chứa các khung giờ

    // Duyệt qua các khung giờ và lưu vào mảng
    while ($row = mysqli_fetch_assoc($result)) {
        $available_time = date('d/m/Y H:i', strtotime($row['available_time']));
        $available_slots = $row['available_slots']; // Số chỗ còn
        // Lưu trữ dưới dạng mảng để dễ dàng xử lý trong JS
        $options[] = [
            'id' => $row['id'],
            'available_time' => $available_time,
            'available_slots' => $available_slots
        ];
    }

    // Trả về dữ liệu dưới dạng JSON để JS có thể xử lý
    echo json_encode($options);
}
?>
