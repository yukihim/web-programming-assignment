$(document).ready(function () {

    // Khi người dùng chọn bác sĩ, gửi yêu cầu AJAX để lấy khung giờ
    $('#doctor_office').on('change', function() {
        var doctorOfficeId = $(this).val(); // Lấy ID của bác sĩ đã chọn
        
        // Nếu đã chọn bác sĩ, gửi AJAX request để lấy khung giờ
        if (doctorOfficeId) {
            $.ajax({
                url: 'pages/home/get_timeslots.php',  // Đường dẫn tới file PHP xử lý AJAX
                type: 'GET',
                data: { doctor_office: doctorOfficeId }, // Truyền ID bác sĩ
                success: function(response) {
                    console.log(response);
                    // Parse dữ liệu JSON
                    var timeslots = JSON.parse(response);

                    console.log(timeslots.length);

                    if (timeslots.length === 0) {
                        // Nếu không có khung giờ, hiển thị thông báo trên box
                        $('#timeslot').html('<option value="">No available slot</option>');

                    }

                    else {

                        // Nếu có khung giờ, tạo các <option> và thêm vào dropdown
                        var options = '<option value="">Select time slot</option>';
                        timeslots.forEach(function(timeslot) {
                            options += "<option value='" + timeslot.id + "'>" + 
                                        timeslot.available_time + " (" + timeslot.available_slots + " remaining)" + 
                                        "</option>";
                        });

                    }

                    // Cập nhật dropdown timeslot với các <option> mới
                    $('#timeslot').html(options);
                },
                error: function() {
                    alert('Error fetching timeslots.');
                }
            });
        } else {
            // Nếu không chọn bác sĩ, reset dropdown timeslot
            $('#timeslot').html('<option value="">Select time slot</option>');
        }
    });
});
