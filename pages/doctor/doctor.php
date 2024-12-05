<!-- TODO: Implement doctor page -->
<?php
// Start the session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<div style="min-height: calc(100vh - 20.5em)">
<h1 class="text-center">Doctor Page</h1>

<!-- Main Content -->
<div class="container mt-4">
        <div class="main-content">
            <div class="row">
                <!-- Upcoming Patients (2/3 màn hình) -->
                <div class="col-lg-8">
                    <h3 class="section-title mb-3">Upcoming Patients</h3>
                    <div id="upcomingPatients" class="section-container">
                        <?php if (isset($patients) && is_array($patients) && !empty($patients)): ?>
                            <ul class="list-group">
                                <?php foreach ($patients as $patient): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>
                                            <strong><?= htmlspecialchars($patient['patient_name'] ?? 'Unknown') ?></strong>
                                            <br>
                                            <small class="text-muted"><?= htmlspecialchars($patient['time'] ?? 'No time available') ?></small>
                                        </span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-muted">No upcoming patients.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Doctor Overview (1/3 màn hình) -->
                <div class="col-lg-4">
                    <h3 class="section-title mb-3">Overview</h3>
                    <div class="section-container">
                        <p><strong>Sessions Today:</strong> <span id="sessionCount">0</span></p>
                        <p><strong>Patients Today:</strong> <span id="patientCount">0</span></p>
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editSessionModal">
                            View/Edit Sessions
                        </button>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createSessionModal">
                            Create New Session
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal: View/Edit Sessions -->
    <div class="modal fade" id="editSessionModal" tabindex="-1" aria-labelledby="editSessionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="editSessionModalLabel">View/Edit Sessions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th>Session ID</th>
                                <th>Time Slot</th>
                                <th>Current Slots</th>
                                <th>Max Slots</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="sessionTable">
                            <!-- Dynamic Content -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Create New Session -->
    <div class="modal fade" id="createSessionModal" tabindex="-1" aria-labelledby="createSessionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="createSessionModalLabel">Create New Session</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createSessionForm">
                        <div class="mb-3">
                            <label for="sessionTime" class="form-label">Session Time</label>
                            <input type="datetime-local" class="form-control" id="sessionTime" name="sessionTime" required>
                        </div>
                        <div class="mb-3">
                            <label for="maxSlots" class="form-label">Max Slots</label>
                            <input type="number" class="form-control" id="maxSlots" name="maxSlots" min="1" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="assets/js/doctor.js"></script>