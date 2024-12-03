<div class="container mt-5">
    <h2>Book an Appointment</h2>
    <form action="pages/patient/controllers/BookAppointment.php" method="POST">
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

<script>
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
</script>
