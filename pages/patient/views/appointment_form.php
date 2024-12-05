<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book an Appointment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            max-width: 600px;
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 40px;
        }
        h2 {
            text-align: center;
            font-size: 32px;
            color: #0056b3;
            margin-bottom: 30px;
        }
        .form-select, .btn {
            border-radius: 25px;
            padding: 12px 20px;
            font-size: 16px;
            transition: all 0.3s ease-in-out;
        }
        .form-select:focus, .btn:focus {
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.6);
            border-color: #0056b3;
        }
        .form-select {
            background-color: #f9f9f9;
        }
        .btn-primary {
            background-color: #0056b3;
            border-color: #0056b3;
            color: #ffffff;
        }
        .btn-primary:hover {
            background-color: #00408d;
            border-color: #00408d;
        }
        .modal-content {
            background: linear-gradient(45deg, #ffffff, #e1f5fe);
        }
        .modal-header {
            background: #1bb300;
            color: white;
        }
        .modal-footer button {
            background-color: #0056b3;
            border-color: #0056b3;
            color: #ffffff;
        }
        .modal-footer button:hover {
            background-color: #00408d;
            border-color: #00408d;
        }
        .btn-close {
            background: none;
            border: none;
            color: white;
        }
        .btn-close:hover {
            color: #0056b3;
        }
        .mb-4 {
            margin-bottom: 24px;
        }
        .form-label {
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 10px;
            color: #333;
        }
        .custom-icon {
            width: 24px;
            height: 24px;
            color: #0056b3;
            margin-right: 10px;
        }
        .modal-body {
            font-size: 18px;
        }
        .form-control-feedback {
            color: #dc3545;
            font-size: 14px;
        }
    </style>
</head>
<body>
<div class="modal-footer">
    <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="window.location.href='index.php?page=history&isSignedIn=true&user=guest';">View History</button>
</div>

<div class="container">
    <h2><i class="bi bi-calendar-check custom-icon"></i>Book an Appointment</h2>
    <form id="appointmentForm">
        <div class="mb-4">
            <label for="doctor_office" class="form-label">Doctor Office</label>
            <select id="doctor_office" name="doctor_office" class="form-select" required>
                <option value="" disabled selected>Select a doctor office</option>
                <?php while ($office = $officesResult->fetch_assoc()): ?>
                    <option value="<?php echo $office['id']; ?>"><?php echo $office['name']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-4">
            <label for="available_time" class="form-label">Available Time Slot</label>
            <select id="available_time" name="time_slot" class="form-select" required>
                <option value="" disabled selected>Select a time slot</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary w-100">Book Appointment</button>
    </form>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Success</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Appointment Booked Successfully. Please check your email for details.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="reloadPage()">OK</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('appointmentForm').addEventListener('submit', function (e) {
        e.preventDefault(); // Prevent the default form submission

        const formData = new FormData(this);
        const formObject = Object.fromEntries(formData.entries());
        console.log("Data being sent to the server:", formObject);

        fetch('pages/patient/controllers/BookAppointment.php', {
            method: 'POST',
            body: formData,
        })
            .then(response => response.json())
            .then(result => {
                console.log("Server response:", result);
                if (result.success) {
                    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                    successModal.show();
                } else {
                    alert('Failed to book appointment. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Something went wrong. Please try again.');
            });
    });


    document.getElementById('doctor_office').addEventListener('change', function() {
        const officeId = this.value;
        fetch(`pages/patient/controllers/SlotController.php?office_id=${officeId}`)
            .then(response => response.json())
            .then(data => {
                const timeSlotSelect = document.getElementById('available_time');
                timeSlotSelect.innerHTML = '<option value="" disabled selected>Select a time slot</option>';
                data.forEach(slot => {
                    const option = document.createElement('option');
                    option.value = slot.id;
                    option.textContent = slot.available_time;
                    timeSlotSelect.appendChild(option);
                });
            });
    });

    document.addEventListener('DOMContentLoaded', function() {
        if (localStorage.getItem('appointmentSuccess') === 'true') {
            const successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
            localStorage.removeItem('appointmentSuccess'); // Clear the flag
        }
    });

    function closeModal() {
        document.getElementById('successModal').style.display = 'none';
    }
    
    function reloadPage() {
        location.reload(); // Reload the current page
    }
</script>

</body>
</html>
