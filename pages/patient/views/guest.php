<?php if ($offices): ?>
    <form action="index.php?action=book_appointment" method="POST">
        <div class="mb-3">
            <label for="doctor_office" class="form-label">Doctor Office</label>
            <select id="doctor_office" name="doctor_office" class="form-select" required>
                <option value="" disabled selected>Select a doctor office</option>
                <?php while ($office = $offices->fetch_assoc()): ?>
                    <option value="<?php echo $office['id']; ?>"><?php echo $office['name']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <!-- JavaScript for fetching time slots -->
        <div class="mb-3">
            <label for="available_time" class="form-label">Available Time Slot</label>
            <select id="available_time" name="time_slot" class="form-select" required></select>
        </div>
        <button type="submit" class="btn btn-primary w-100">Book Appointment</button>
    </form>
<?php endif; ?>
