CREATE DATABASE IF NOT EXISTS medical_appointment;

USE medical_appointment;

DROP TABLE IF EXISTS users;
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(15),
    role ENUM('admin', 'staff', 'doctor', 'patient') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

DROP TABLE IF EXISTS doctor_offices;
CREATE TABLE IF NOT EXISTS doctor_offices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    location VARCHAR(255) NOT NULL,
    phone VARCHAR(15),
    email VARCHAR(100),
    doctor_id INT NOT NULL,
    FOREIGN KEY (doctor_id) REFERENCES users(id) ON DELETE CASCADE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

DROP TABLE IF EXISTS time_slots;
CREATE TABLE IF NOT EXISTS time_slots (
    id INT AUTO_INCREMENT PRIMARY KEY,
    doctor_office_id INT NOT NULL,
    available_time DATETIME NOT NULL,
    max_slots INT NOT NULL DEFAULT 1,
    can_book BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (doctor_office_id) REFERENCES doctor_offices(id) ON DELETE CASCADE
);

DROP TABLE IF EXISTS appointments;
CREATE TABLE IF NOT EXISTS appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    doctor_office_id INT NOT NULL,
    time_slot_id INT NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (doctor_office_id) REFERENCES doctor_offices(id) ON DELETE CASCADE,
    FOREIGN KEY (time_slot_id) REFERENCES time_slots(id) ON DELETE CASCADE
);

INSERT INTO users (username, name, email, password, phone, role) VALUES
('staff_user', 'Staff User', 'staff@system.com', 'staffpassword', '1234567891', 'staff'),
('dr_smith', 'Dr. Smith', 'doctor_smith@clinic.com', 'doctorpassword', '1234567892', 'doctor'),
('john_doe', 'John Doe', 'patient@system.com', 'patientpassword', '1234567893', 'patient'),
('maximilian', 'Maximilian', 'patient1@system.com', 'patientpassword', '1234567893', 'patient'),
('connan', 'Connan', 'doctor_connan@clinic.com', 'doctorpassword', '1234567892', 'doctor');

INSERT INTO doctor_offices (name, location, phone, email, doctor_id) VALUES
('Central Clinic', '123 Main Street, Cityville', '123-456-7890', 'central@clinic.com', 2),
('Downtown Medical Center', '456 Elm Street, Cityville', '987-654-3210', 'downtown@clinic.com', 2),
('North Clinic', '789 Oak Street, Cityville', '456-789-0123', 'north@clinic.com', 5),
('South Clinic', '101 Pine Street, Cityville', '789-012-3456', 'sounth@clinic.com', 5);

INSERT INTO time_slots (doctor_office_id, available_time, max_slots, can_book) VALUES
(1, '2024-11-18 09:00:00', 5, TRUE),
(1, '2024-11-18 10:00:00', 3, TRUE),
(2, '2024-11-18 11:00:00', 4, TRUE),
(2, '2024-11-18 14:00:00', 6, TRUE),
(1, '2024-11-19 15:00:00', 2, TRUE),
(3, '2024-11-19 16:00:00', 3, TRUE),
(4, '2024-11-19 17:00:00', 4, TRUE),
(4, '2024-11-19 18:00:00', 5, TRUE);

UPDATE time_slots 
SET available_time = '2024-11-18 08:00:00' 
WHERE id = 1;

SET time_zone = '+07:00';

INSERT INTO appointments (patient_id, doctor_office_id, time_slot_id, status) VALUES
(4, 1, 1, 'confirmed'),
(4, 2, 4, 'pending'),
(3, 1, 5, 'pending'),
(3, 3, 6, 'pending'),
(4, 4, 7, 'pending'),
(3, 4, 8, 'pending');

CREATE USER 'mySQL_Admin'@'%' IDENTIFIED BY 'newpassword';
GRANT INSERT, UPDATE, DELETE, SELECT ON medical_appointment.* TO 'mySQL_Admin'@'%';
