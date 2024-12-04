<div class="container mt-5">
    <h2>Book an Appointment</h2>
    <form id="appointmentForm">
        <div class="mb-3">
            <label for="doctor_office" class="form-label">Doctor Office</label>
            <select id="doctor_office" name="doctor_office" class="form-select" required>
                <option value="" disabled selected>Select a doctor office</option>
                <?php while ($office = $officesResult->fetch_assoc()): ?>
                    <option value="<?php echo $office['id']; ?>"><?php echo $office['name']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="available_time" class="form-label">Available Time Slot</label>
            <select id="available_time" name="time_slot" class="form-select" required>
                <option value="" disabled selected>Select a time slot</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary w-100">Book Appointment</button>
    </form>

</div>

<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Success</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Appointment Booked Successfully. Please check your email for details.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('appointmentForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        const formData = new FormData(this);

        fetch('pages/patient/controllers/BookAppointment.php', {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(result => {
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
        console.log(`Selected Doctor Office ID: ${officeId}`);
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
</script>
