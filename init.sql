CREATE DATABASE medical_appointment;

USE medical_appointment;

-- Users table for authentication
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('staff', 'doctor', 'patient') NOT NULL
);

-- Appointments table
-- CREATE TABLE appointments (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     doctor_office VARCHAR(100) NOT NULL,
--     patient_name VARCHAR(100) NOT NULL,
--     phone_number VARCHAR(15) NOT NULL,
--     email VARCHAR(100),
--     appointment_time DATETIME NOT NULL,
--     status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending'
-- );

-- Insert mock data
INSERT INTO users (username, password, role) VALUES
('staff', 'sta123', 'staff'),
('doctor', 'doc123', 'doctor')
('patient', 'pat123', 'patient');
