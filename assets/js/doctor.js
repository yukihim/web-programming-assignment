$(document).ready(function () {
    let fetchPatientsRequest = null;

    // Fetch patients
    function fetchPatients() {
        if (fetchPatientsRequest) {
            fetchPatientsRequest.abort(); // Hủy yêu cầu trước nếu chưa hoàn tất
        }
    
        fetchPatientsRequest = $.ajax({
            url: "pages/doctor/controllers/DoctorController.php?action=fetchPatients",
            method: "GET",
            success: function (response) {
                console.log("Response: ", response);
                try {
                    const patients = JSON.parse(response);
                    let content = "<ul class='list-group'>";
                    
                    patients.forEach(patient => {
                        let badgeClass = '';
                            if (patient.appointment_status === 'cancelled') {
                                badgeClass = 'bg-danger';  // Red for cancelled
                            } else if (patient.appointment_status === 'pending') {
                                badgeClass = 'bg-warning';  // Yellow for pending
                            } else {
                                badgeClass = 'bg-primary';  // Default color for confirmed or others
                            }
                        content += `
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>
                                    <strong>${patient.patient_name || 'No body'}</strong>
                                    <br>
                                    <small class="text-muted">${patient.time || 'No time available'}</small>
                                </span>
                                <span class="badge ${badgeClass} rounded-pill">${patient.appointment_status || 'No status'}</span>
                            </li>
                        `;
                    });
                    
                    content += "</ul>";
                    $("#upcomingPatients").html(content);
                } catch (error) {
                    console.error("Failed to parse response: ", error);
                    $("#upcomingPatients").html("<p class='text-danger'>Error parsing response.</p>");
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error: ", error);
                $("#upcomingPatients").html("<p class='text-danger'>Failed to load patients.</p>");
            }
        });
        
    }

    // Fetch sessions
    function fetchSessions() {
        $.ajax({
            url: "pages/doctor/controllers/DoctorController.php?action=fetchSessions",
            method: "GET",
            success: function (response) {
                const res = JSON.parse(response);
    
                if (res.success) {
                    const sessions = res.sessions;
                    let content = "";
    
                    sessions.forEach(session => {
                        content += `
                            <tr id="session-${session.session_id}">
                                <td>${session.session_id}</td>
                                <td>${session.available_time}</td>
                                <td>${session.booked_slots}</td>
                                <td>${session.max_slots}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm" onclick="editMaxSlots(${session.session_id})">Edit</button>
                                    <button class="btn btn-danger btn-sm" onclick="deleteSession(${session.session_id})">Delete</button>
                                </td>
                            </tr>
                        `;
                    });
    
                    if (sessions.length === 0) {
                        content = "<tr><td colspan='5'>No sessions found.</td></tr>";
                    }
    
                    $("#sessionTable").html(content);
                } else {
                    $("#sessionTable").html("<tr><td colspan='5'>Failed to load sessions.</td></tr>");
                    console.error("Error: ", res.error);
                }
            },
            error: function () {
                $("#sessionTable").html("<tr><td colspan='5'>Failed to load sessions.</td></tr>");
            }
        });
    }

    // Create session
    $("#createSessionForm").on("submit", function (e) {
        e.preventDefault();
        const sessionTime = $("#sessionTime").val();
        const maxSlots = $("#maxSlots").val();

        $.ajax({
            url: "pages/doctor/controllers/DoctorController.php?action=createSession",
            method: "POST",
            data: { sessionTime, maxSlots },
            success: function (response) {
                const res = JSON.parse(response);
                alert(res.success ? "Session created!" : "Failed to create session.");
                fetchSessions(); // Refresh sessions
            },
            error: function () {
                alert("Failed to create session.");
            }
        });
    });

    function updateCounts() {
        $.ajax({
            url: "pages/doctor/controllers/DoctorController.php?action=fetchCounts",
            method: "GET",
            success: function (response1) {
                console.log("Counts response: ", response1);
                try {
                    const counts = JSON.parse(response1);
                    $("#sessionCount").text(counts.sessionsToday || 0);
                    $("#patientCount").text(counts.patientsToday || 0);
                } catch (error) {
                    console.error("Failed to parse counts response: ", error);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error: ", error);
            }
        });
    }

    function deleteSession(sessionId) {
        if (confirm("Are you sure you want to delete this session?")) {
            $.ajax({
                url: "pages/doctor/controllers/DoctorController.php?action=deleteSession",
                method: "POST",
                data: { sessionId },
                success: function (response) {
                    const res = JSON.parse(response);
                    if (res.success) {
                        alert("Session deleted successfully!");
                        fetchSessions(); // Refresh the session list
                    } else {
                        alert("Failed to delete session.");
                    }
                },
                error: function () {
                    alert("Error occurred while deleting the session.");
                }
            });
        }
    }

    function editMaxSlots(sessionId) {
        // Lấy số lượng bệnh nhân đã đặt cho session này (sử dụng AJAX để lấy từ server)
        $.ajax({
            url: "pages/doctor/controllers/DoctorController.php?action=getBookedPatientCount",
            method: "GET",
            data: { sessionId: sessionId },
            success: function (response) {
                console.log("Booked patients response: ", response);
                const res = JSON.parse(response);
    
                if (res.success) {
                    const bookedPatients = res.bookedPatients;  // Số lượng bệnh nhân đã đặt
                    const newMaxSlots = prompt("Enter new max slots:");
    
                    if (newMaxSlots && !isNaN(newMaxSlots) && newMaxSlots >= bookedPatients) {
                        // Gửi AJAX để cập nhật max_slots
                        $.ajax({
                            url: "pages/doctor/controllers/DoctorController.php?action=editMaxSlots",
                            method: "POST",
                            data: { sessionId, maxSlots: newMaxSlots },
                            success: function (response) {
                                const res = JSON.parse(response);
                                if (res.success) {
                                    alert("Max slots updated successfully!");
                                    fetchSessions(); // Refresh the session list
                                } else {
                                    alert("Failed to update max slots.");
                                }
                            },
                            error: function () {
                                alert("Error occurred while updating max slots.");
                            }
                        });
                    } else {
                        alert("The new max slots should be greater than or equal to the number of patients already booked.");
                    }
                } else {
                    alert("Failed to fetch booked patients data.");
                }
            },
            error: function () {
                alert("Error occurred while fetching booked patients count.");
            }
        });
    }
    
    // interval setup
    setInterval(() => {
        fetchPatients();
        updateCounts();
    }, 5000);


    // Initial fetch when the page loads
    fetchPatients();
    fetchSessions();
    updateCounts();

    // add global function
    window.deleteSession = deleteSession;
    window.editMaxSlots = editMaxSlots;
});