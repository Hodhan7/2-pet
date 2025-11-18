-- Full schema + seed data for Pet Health Tracker
-- Generated: 2025-10-24
-- Usage: import this file into a MySQL/MariaDB server
-- Example (Windows CMD):
--   mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS pet_health_tracker CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
--   mysql -u root -p pet_health_tracker < "C:\path\to\full_schema_and_data.sql"

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE DATABASE IF NOT EXISTS `pet_health_tracker` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `pet_health_tracker`;

-- Drop tables if they exist (safe recreate)
DROP TABLE IF EXISTS `system_settings`;
DROP TABLE IF EXISTS `notifications`;
DROP TABLE IF EXISTS `reminders`;
DROP TABLE IF EXISTS `weight_records`;
DROP TABLE IF EXISTS `medications`;
DROP TABLE IF EXISTS `veterinarian_applications`;
DROP TABLE IF EXISTS `appointments`;
DROP TABLE IF EXISTS `vaccinations`;
DROP TABLE IF EXISTS `health_records`;
DROP TABLE IF EXISTS `pets`;
DROP TABLE IF EXISTS `veterinarian_profiles`;
DROP TABLE IF EXISTS `users`;

-- Users table
CREATE TABLE `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('pet_owner','veterinarian','admin') NOT NULL DEFAULT 'pet_owner',
  `name` VARCHAR(255),
  `first_name` VARCHAR(100) NOT NULL,
  `last_name` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(20),
  `address` TEXT,
  `city` VARCHAR(100),
  `state` VARCHAR(50),
  `zip_code` VARCHAR(10),
  `bio` TEXT,
  `emergency_contact` VARCHAR(255),
  `emergency_phone` VARCHAR(20),
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `email_verified` TINYINT(1) NOT NULL DEFAULT 0,
  `last_login` TIMESTAMP NULL DEFAULT NULL,
  `notification_preferences` JSON NULL,
  `privacy_settings` JSON NULL,
  `deletion_requested` TINYINT(1) NOT NULL DEFAULT 0,
  `deletion_reason` TEXT,
  `deletion_requested_at` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_users_email` (`email`),
  KEY `idx_users_role` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Veterinarian profiles
CREATE TABLE `veterinarian_profiles` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `license_number` VARCHAR(100),
  `clinic_name` VARCHAR(255),
  `clinic_address` TEXT,
  `clinic_phone` VARCHAR(20),
  `specializations` JSON NULL,
  `years_experience` INT,
  `education` TEXT,
  `certifications` TEXT,
  `bio` TEXT,
  `consultation_fee` DECIMAL(10,2),
  `is_accepting_patients` TINYINT(1) NOT NULL DEFAULT 1,
  `working_hours` JSON NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_vet_profiles_user` (`user_id`),
  UNIQUE KEY `uq_vet_profiles_license` (`license_number`),
  CONSTRAINT `fk_vet_profiles_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Pets
CREATE TABLE `pets` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `owner_id` INT UNSIGNED NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `species` VARCHAR(50) NOT NULL,
  `breed` VARCHAR(100),
  `gender` ENUM('male','female','unknown') DEFAULT 'unknown',
  `dob` DATE,
  `weight` DECIMAL(5,2),
  `color` VARCHAR(100),
  `microchip_id` VARCHAR(50),
  `is_spayed_neutered` TINYINT(1) DEFAULT 0,
  `allergies` TEXT,
  `medications` TEXT,
  `special_needs` TEXT,
  `notes` TEXT,
  `emergency_contact` VARCHAR(255),
  `emergency_phone` VARCHAR(20),
  `photo_url` VARCHAR(500),
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_pets_microchip` (`microchip_id`),
  KEY `idx_pets_owner` (`owner_id`),
  KEY `idx_pets_species` (`species`),
  CONSTRAINT `fk_pets_owner` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Health records
CREATE TABLE `health_records` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `pet_id` INT UNSIGNED NOT NULL,
  `veterinarian_id` INT UNSIGNED NULL,
  `record_type` ENUM('vaccination','checkup','illness','surgery','medication','lab_result','note','emergency') NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `diagnosis` TEXT,
  `treatment` TEXT,
  `medications_prescribed` TEXT,
  `follow_up_required` TINYINT(1) DEFAULT 0,
  `follow_up_date` DATE,
  `record_date` DATE NOT NULL,
  `weight_at_visit` DECIMAL(5,2),
  `temperature` DECIMAL(4,1),
  `heart_rate` INT,
  `attachments` JSON NULL,
  `cost` DECIMAL(10,2),
  `notes` TEXT,
  `is_urgent` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_health_records_pet` (`pet_id`),
  KEY `idx_health_records_date` (`record_date`),
  KEY `idx_health_records_type` (`record_type`),
  CONSTRAINT `fk_health_records_pet` FOREIGN KEY (`pet_id`) REFERENCES `pets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_health_records_vet` FOREIGN KEY (`veterinarian_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Vaccinations
CREATE TABLE `vaccinations` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `pet_id` INT UNSIGNED NOT NULL,
  `vaccine_name` VARCHAR(255) NOT NULL,
  `vaccine_type` VARCHAR(100),
  `administered_date` DATE NOT NULL,
  `expiry_date` DATE,
  `next_due_date` DATE,
  `veterinarian_id` INT UNSIGNED NULL,
  `batch_number` VARCHAR(100),
  `manufacturer` VARCHAR(255),
  `site_administered` VARCHAR(100),
  `reaction` TEXT,
  `notes` TEXT,
  `cost` DECIMAL(10,2),
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_vaccinations_pet` (`pet_id`),
  KEY `idx_vaccinations_due` (`next_due_date`),
  CONSTRAINT `fk_vaccinations_pet` FOREIGN KEY (`pet_id`) REFERENCES `pets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_vaccinations_vet` FOREIGN KEY (`veterinarian_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Medications
CREATE TABLE `medications` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `pet_id` INT UNSIGNED NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `dosage` VARCHAR(100),
  `frequency` VARCHAR(100),
  `start_date` DATE NOT NULL,
  `end_date` DATE,
  `prescribed_by` INT UNSIGNED NULL,
  `purpose` TEXT,
  `instructions` TEXT,
  `side_effects` TEXT,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_medications_pet` (`pet_id`),
  KEY `idx_medications_active` (`is_active`),
  CONSTRAINT `fk_medications_pet` FOREIGN KEY (`pet_id`) REFERENCES `pets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_medications_prescribed_by` FOREIGN KEY (`prescribed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Weight records
CREATE TABLE `weight_records` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `pet_id` INT UNSIGNED NOT NULL,
  `weight` DECIMAL(5,2) NOT NULL,
  `unit` ENUM('kg','lbs') DEFAULT 'kg',
  `recorded_date` DATE NOT NULL,
  `notes` TEXT,
  `recorded_by` INT UNSIGNED NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_weight_pet` (`pet_id`),
  CONSTRAINT `fk_weight_pet` FOREIGN KEY (`pet_id`) REFERENCES `pets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_weight_recorded_by` FOREIGN KEY (`recorded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Appointments
CREATE TABLE `appointments` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `pet_id` INT UNSIGNED NOT NULL,
  `veterinarian_id` INT UNSIGNED NOT NULL,
  `owner_id` INT UNSIGNED NOT NULL,
  `appointment_date` DATETIME NOT NULL,
  `duration_minutes` INT DEFAULT 30,
  `duration` INT DEFAULT 30,
  `appointment_type` ENUM('checkup','vaccination','emergency','surgery','consultation','follow_up') DEFAULT 'checkup',
  `purpose` TEXT,
  `status` ENUM('pending','confirmed','in_progress','completed','cancelled','no_show') DEFAULT 'pending',
  `cancellation_reason` TEXT,
  `notes` TEXT,
  `reminder_sent` TINYINT(1) DEFAULT 0,
  `cost` DECIMAL(10,2),
  `payment_status` ENUM('pending','paid','overdue') DEFAULT 'pending',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_appointments_date` (`appointment_date`),
  KEY `idx_appointments_vet` (`veterinarian_id`),
  KEY `idx_appointments_status` (`status`),
  CONSTRAINT `fk_appointments_pet` FOREIGN KEY (`pet_id`) REFERENCES `pets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_appointments_vet` FOREIGN KEY (`veterinarian_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_appointments_owner` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Veterinarian applications
CREATE TABLE `veterinarian_applications` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `license_number` VARCHAR(100) NOT NULL,
  `clinic_name` VARCHAR(255),
  `qualifications` TEXT NOT NULL,
  `specializations` JSON NULL,
  `years_experience` INT,
  `education` TEXT,
  `certifications` TEXT,
  `documents` JSON NULL,
  `status` ENUM('pending','under_review','approved','rejected') DEFAULT 'pending',
  `rejection_reason` TEXT,
  `reviewed_by` INT UNSIGNED NULL,
  `reviewed_at` TIMESTAMP NULL DEFAULT NULL,
  `notes` TEXT,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_vet_app_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_vet_app_reviewed_by` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Reminders
CREATE TABLE `reminders` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `pet_id` INT UNSIGNED NULL,
  `type` ENUM('vaccination','medication','appointment','checkup','custom') NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `reminder_date` DATE NOT NULL,
  `is_recurring` TINYINT(1) DEFAULT 0,
  `recurrence_pattern` VARCHAR(50),
  `is_completed` TINYINT(1) DEFAULT 0,
  `is_sent` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_reminders_date` (`reminder_date`),
  KEY `idx_reminders_user` (`user_id`),
  CONSTRAINT `fk_reminders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_reminders_pet` FOREIGN KEY (`pet_id`) REFERENCES `pets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Notifications
CREATE TABLE `notifications` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `type` ENUM('appointment','reminder','system','application_status') NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `message` TEXT NOT NULL,
  `is_read` TINYINT(1) DEFAULT 0,
  `related_id` INT UNSIGNED NULL,
  `related_type` VARCHAR(50) NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_notifications_user` (`user_id`),
  KEY `idx_notifications_read` (`is_read`),
  CONSTRAINT `fk_notifications_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- System settings
CREATE TABLE `system_settings` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `setting_key` VARCHAR(100) NOT NULL,
  `setting_value` TEXT,
  `description` TEXT,
  `updated_by` INT UNSIGNED NULL,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_system_settings_key` (`setting_key`),
  CONSTRAINT `fk_system_settings_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Indexes: key/indexes are declared inline inside CREATE TABLE above to avoid
-- duplicate index errors on import. If you need additional indexes, add them
-- here but ensure names don't collide with existing keys.

-- Seed data (from original database.sql)

-- Sample users (including the new fields)
INSERT INTO users (email, password, role, name, first_name, last_name, phone, address, city, state, zip_code, bio, notification_preferences, privacy_settings) VALUES
('admin@pethealthtracker.com', '$2y$10$RCEYpaxbGgfhGLBGYnxr8eyBM/jdERjP2sph0a3KhyB4zMYjc1g7O', 'admin', 'Admin User', 'Admin', 'User', '555-0100', '123 Admin St', 'AdminCity', 'AC', '12345', 'System administrator for Pet Health Tracker', '{"email_notifications": true, "sms_notifications": false, "appointment_reminders": true}', '{"profile_visibility": "private", "data_sharing": false, "analytics": true}'),
('dr.smith@vetclinic.com', '$2y$10$RCEYpaxbGgfhGLBGYnxr8eyBM/jdERjP2sph0a3KhyB4zMYjc1g7O', 'veterinarian', 'Dr. John Smith', 'John', 'Smith', '555-0200', '456 Vet Ave', 'VetCity', 'VC', '23456', 'Experienced veterinarian specializing in general practice and surgery', '{"email_notifications": true, "sms_notifications": true, "appointment_reminders": true}', '{"profile_visibility": "public", "data_sharing": true, "analytics": true}'),
('dr.johnson@animalcare.com', '$2y$10$RCEYpaxbGgfhGLBGYnxr8eyBM/jdERjP2sph0a3KhyB4zMYjc1g7O', 'veterinarian', 'Dr. Sarah Johnson', 'Sarah', 'Johnson', '555-0201', '789 Care Blvd', 'CareCity', 'CC', '34567', 'Emergency medicine specialist with focus on critical care', '{"email_notifications": true, "sms_notifications": true, "appointment_reminders": true}', '{"profile_visibility": "public", "data_sharing": true, "analytics": true}'),
('owner1@email.com', '$2y$10$RCEYpaxbGgfhGLBGYnxr8eyBM/jdERjP2sph0a3KhyB4zMYjc1g7O', 'pet_owner', 'Alice Williams', 'Alice', 'Williams', '555-0300', '321 Pet Lane', 'PetCity', 'PC', '45678', 'Loving pet owner with two furry companions', '{"email_notifications": true, "sms_notifications": false, "appointment_reminders": true}', '{"profile_visibility": "private", "data_sharing": false, "analytics": false}'),
('owner2@email.com', '$2y$10$RCEYpaxbGgfhGLBGYnxr8eyBM/jdERjP2sph0a3KhyB4zMYjc1g7O', 'pet_owner', 'Bob Brown', 'Bob', 'Brown', '555-0301', '654 Animal Dr', 'AnimalTown', 'AT', '56789', 'Pet enthusiast and advocate for animal welfare', '{"email_notifications": true, "sms_notifications": true, "appointment_reminders": true}', '{"profile_visibility": "private", "data_sharing": false, "analytics": true}');

-- Veterinarian profiles for the vets
INSERT INTO veterinarian_profiles (user_id, license_number, clinic_name, clinic_address, clinic_phone, specializations, years_experience, education, consultation_fee, working_hours) VALUES
(2, 'VET123456', 'Smith Veterinary Clinic', '456 Vet Ave, VetCity, VC 23456', '555-0200', '["General Practice", "Surgery", "Internal Medicine"]', 15, 'DVM from State University, Board Certified in Internal Medicine', 75.00, '{"monday": "9:00-17:00", "tuesday": "9:00-17:00", "wednesday": "9:00-17:00", "thursday": "9:00-17:00", "friday": "9:00-17:00", "saturday": "9:00-13:00", "sunday": "closed"}'),
(3, 'VET789012', 'Johnson Animal Care Center', '789 Care Blvd, CareCity, CC 34567', '555-0201', '["Emergency Medicine", "Surgery", "Dermatology"]', 12, 'DVM from Animal Medical College, Emergency Medicine Residency', 85.00, '{"monday": "8:00-18:00", "tuesday": "8:00-18:00", "wednesday": "8:00-18:00", "thursday": "8:00-18:00", "friday": "8:00-18:00", "saturday": "10:00-16:00", "sunday": "10:00-14:00"}');

-- Sample pets (including notes field)
INSERT INTO pets (owner_id, name, species, breed, gender, dob, weight, color, is_spayed_neutered, allergies, notes) VALUES
(4, 'Buddy', 'Dog', 'Golden Retriever', 'male', '2020-05-15', 28.5, 'Golden', TRUE, 'None known', 'Very friendly and energetic dog, loves playing fetch'),
(4, 'Whiskers', 'Cat', 'Siamese', 'female', '2019-08-22', 4.2, 'Seal Point', TRUE, 'Fish protein', 'Indoor cat, very vocal and affectionate'),
(5, 'Max', 'Dog', 'German Shepherd', 'male', '2018-12-10', 35.8, 'Black and Tan', FALSE, 'Chicken', 'Guard dog with protective instincts, well-trained'),
(5, 'Luna', 'Cat', 'Persian', 'female', '2021-03-08', 3.8, 'White', TRUE, 'None known', 'Long-haired beauty, requires regular grooming');

-- Sample health records (including additional fields)
INSERT INTO health_records (pet_id, veterinarian_id, record_type, title, description, diagnosis, treatment, record_date, weight_at_visit, temperature, notes, cost) VALUES
(1, 2, 'checkup', 'Annual Wellness Exam', 'Routine annual checkup', 'Healthy', 'Continue current diet and exercise routine', '2024-05-15', 28.5, 38.5, 'Very cooperative during exam', 85.00),
(1, 2, 'vaccination', 'DHPP Vaccination', 'Annual DHPP booster vaccination', NULL, 'DHPP vaccine administered', '2024-05-15', 28.5, 38.5, 'No adverse reactions observed', 45.00),
(2, 3, 'checkup', 'Senior Cat Checkup', 'Routine senior cat wellness exam', 'Mild dental tartar', 'Dental cleaning recommended', '2024-06-20', 4.2, 38.8, 'Patient was slightly stressed but cooperative', 75.00),
(3, 2, 'illness', 'Skin Allergy Flare-up', 'Presenting with itching and redness', 'Allergic dermatitis', 'Antihistamine prescribed, hypoallergenic diet recommended', '2024-07-10', 35.8, 39.2, 'Owner advised to monitor symptoms closely', 120.00),
(4, 3, 'checkup', 'Kitten Wellness Check', 'First wellness exam for new kitten', 'Healthy kitten', 'Continue kitten food, schedule spay surgery', '2024-04-12', 3.8, 38.6, 'Very active and healthy kitten', 65.00);

-- Sample vaccinations (including notes field)
INSERT INTO vaccinations (pet_id, vaccine_name, vaccine_type, administered_date, expiry_date, next_due_date, veterinarian_id, manufacturer, notes) VALUES
(1, 'DHPP', 'Core', '2024-05-15', '2025-05-15', '2025-05-15', 2, 'Zoetis', 'Annual booster administered without complications'),
(1, 'Rabies', 'Core', '2024-05-15', '2027-05-15', '2027-05-15', 2, 'Merial', '3-year vaccine, next due in 2027'),
(2, 'FVRCP', 'Core', '2024-06-20', '2025-06-20', '2025-06-20', 3, 'Zoetis', 'Senior cat vaccination, well tolerated'),
(3, 'DHPP', 'Core', '2024-01-15', '2025-01-15', '2025-01-15', 2, 'Zoetis', 'Annual vaccination for large breed dog'),
(4, 'FVRCP', 'Core', '2024-04-12', '2025-04-12', '2025-04-12', 3, 'Zoetis', 'Kitten series vaccination');

-- Sample appointments (including duration fields and cost)
INSERT INTO appointments (pet_id, veterinarian_id, owner_id, appointment_date, duration_minutes, duration, appointment_type, purpose, status, cost) VALUES
(1, 2, 4, '2024-08-15 10:00:00', 30, 30, 'checkup', 'Annual wellness exam', 'confirmed', 85.00),
(2, 3, 4, '2024-08-20 14:30:00', 45, 45, 'consultation', 'Follow-up for dental cleaning', 'confirmed', 75.00),
(3, 2, 5, '2024-08-25 09:15:00', 30, 30, 'follow_up', 'Check on skin allergy treatment', 'pending', 60.00),
(4, 3, 5, '2024-09-05 16:00:00', 60, 60, 'surgery', 'Spay surgery', 'confirmed', 450.00);

-- Sample medications
INSERT INTO medications (pet_id, name, dosage, frequency, start_date, end_date, prescribed_by, purpose, instructions) VALUES
(3, 'Benadryl', '25mg', 'Twice daily', '2024-07-10', '2024-07-24', 2, 'Allergic reaction', 'Give with food, monitor for drowsiness'),
(2, 'Dental Chews', '1 chew', 'Daily', '2024-06-20', NULL, 3, 'Dental health', 'Give as treat, supervise while chewing');

-- Sample reminders
INSERT INTO reminders (user_id, pet_id, type, title, description, reminder_date, is_recurring, recurrence_pattern) VALUES
(4, 1, 'vaccination', 'Buddy DHPP Due', 'Annual DHPP vaccination is due', '2025-05-15', TRUE, 'yearly'),
(4, 2, 'vaccination', 'Whiskers FVRCP Due', 'Annual FVRCP vaccination is due', '2025-06-20', TRUE, 'yearly'),
(5, 3, 'checkup', 'Max Annual Checkup', 'Annual wellness examination', '2025-01-15', TRUE, 'yearly'),
(5, 4, 'appointment', 'Luna Spay Surgery', 'Scheduled spay surgery', '2024-09-05', FALSE, NULL);

-- Sample system settings
INSERT INTO system_settings (setting_key, setting_value, description) VALUES
('appointment_reminder_days', '3', 'Number of days before appointment to send reminder'),
('vaccination_reminder_days', '30', 'Number of days before vaccination due date to send reminder'),
('clinic_hours', '{"open": "08:00", "close": "18:00"}', 'Default clinic operating hours'),
('emergency_contact', '555-EMERGENCY', 'Emergency contact number for after-hours'),
('max_appointment_duration', '120', 'Maximum appointment duration in minutes');

SET FOREIGN_KEY_CHECKS = 1;
