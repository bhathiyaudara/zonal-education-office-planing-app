-- ============================================================
-- ZEMS (Zonal Education Management System) - Complete Database
-- Database: zems_db
-- Engine: MySQL / MariaDB
-- Generated: 2026-02-25
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';

-- Drop existing tables (in reverse dependency order)
DROP TABLE IF EXISTS `auditlog`;
DROP TABLE IF EXISTS `profileeditrequest`;
DROP TABLE IF EXISTS `salaryrequest`;
DROP TABLE IF EXISTS `transferrequest`;
DROP TABLE IF EXISTS `subjectstatistic`;
DROP TABLE IF EXISTS `studentcount`;
DROP TABLE IF EXISTS `teachersubject`;
DROP TABLE IF EXISTS `teacher`;
DROP TABLE IF EXISTS `subject`;
DROP TABLE IF EXISTS `school`;
DROP TABLE IF EXISTS `user`;
DROP TABLE IF EXISTS `sessions`;
DROP TABLE IF EXISTS `cache`;
DROP TABLE IF EXISTS `cache_locks`;
DROP TABLE IF EXISTS `jobs`;
DROP TABLE IF EXISTS `failed_jobs`;

-- ============================================================
-- 1. USER TABLE
-- Stores all system users (Zonal Admin, School Admin, Teacher)
-- ============================================================
CREATE TABLE `user` (
    `id` VARCHAR(36) NOT NULL,
    `name` VARCHAR(191) NOT NULL,
    `email` VARCHAR(191) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('SUPER_ADMIN', 'ZONAL_ADMIN', 'SCHOOL_ADMIN', 'TEACHER') NOT NULL DEFAULT 'TEACHER',
    `schoolId` VARCHAR(36) DEFAULT NULL,
    `isActive` TINYINT(1) NOT NULL DEFAULT 1,
    `image` VARCHAR(500) DEFAULT NULL,
    `remember_token` VARCHAR(100) DEFAULT NULL,
    `createdAt` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updatedAt` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_user_email` (`email`),
    KEY `idx_user_role` (`role`),
    KEY `idx_user_schoolId` (`schoolId`),
    KEY `idx_user_isActive` (`isActive`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 2. SCHOOL TABLE
-- Stores school records managed by the Zonal Admin
-- ============================================================
CREATE TABLE `school` (
    `id` VARCHAR(36) NOT NULL,
    `name` VARCHAR(191) NOT NULL,
    `nameSinhala` VARCHAR(191) DEFAULT NULL,
    `category` VARCHAR(100) DEFAULT NULL,
    `censusNo` VARCHAR(191) DEFAULT NULL,
    `address` VARCHAR(500) DEFAULT NULL,
    `contactNumber` VARCHAR(50) DEFAULT NULL,
    `email` VARCHAR(191) DEFAULT NULL,
    `schoolType` VARCHAR(100) DEFAULT NULL,
    `eduZone` VARCHAR(100) DEFAULT 'Wilgamuwa',
    `eduDivision` VARCHAR(100) DEFAULT NULL,
    `gradeStart` INT DEFAULT NULL,
    `gradeEnd` INT DEFAULT NULL,
    `sanctionedPositions` INT DEFAULT NULL,
    `principalName` VARCHAR(191) DEFAULT NULL,
    `principalNic` VARCHAR(50) DEFAULT NULL,
    `principalMobile` VARCHAR(50) DEFAULT NULL,
    `isActive` TINYINT(1) NOT NULL DEFAULT 1,
    `createdAt` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updatedAt` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_school_censusNo` (`censusNo`),
    KEY `idx_school_name` (`name`),
    KEY `idx_school_isActive` (`isActive`),
    KEY `idx_school_eduZone` (`eduZone`),
    KEY `idx_school_eduDivision` (`eduDivision`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 3. SUBJECT TABLE
-- Subject definitions managed by zonal admin
-- ============================================================
CREATE TABLE `subject` (
    `id` VARCHAR(36) NOT NULL,
    `name` VARCHAR(191) NOT NULL,
    `nameSinhala` VARCHAR(191) DEFAULT NULL,
    `category` VARCHAR(100) DEFAULT NULL,
    `createdAt` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updatedAt` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_subject_name` (`name`),
    KEY `idx_subject_category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 4. TEACHER TABLE
-- Teacher profile records (linked to user via userId)
-- ============================================================
CREATE TABLE `teacher` (
    `id` VARCHAR(36) NOT NULL,
    `userId` VARCHAR(36) DEFAULT NULL,
    `schoolId` VARCHAR(36) DEFAULT NULL,
    `nic` VARCHAR(20) NOT NULL,
    `nameWithInitials` VARCHAR(255) NOT NULL,
    `fullName` VARCHAR(255) DEFAULT NULL,
    `dateOfBirth` DATE DEFAULT NULL,
    `gender` VARCHAR(20) DEFAULT NULL,
    `address` TEXT DEFAULT NULL,
    `phone` VARCHAR(50) DEFAULT NULL,
    `whatsapp` VARCHAR(50) DEFAULT NULL,
    `email` VARCHAR(255) DEFAULT NULL,
    `maritalStatus` VARCHAR(50) DEFAULT NULL,
    `spouseIsTeacherInWilgamuwa` TINYINT(1) DEFAULT 0,
    `spouseNameWithInitials` VARCHAR(255) DEFAULT NULL,
    `spouseNic` VARCHAR(20) DEFAULT NULL,
    `spouseSchoolId` VARCHAR(36) DEFAULT NULL,
    `appointmentDate` DATE DEFAULT NULL,
    `appointmentType` VARCHAR(100) DEFAULT NULL,
    `appointmentMedium` VARCHAR(50) DEFAULT NULL,
    `appointmentSubject` VARCHAR(191) DEFAULT NULL,
    `serviceGrade` VARCHAR(100) DEFAULT NULL,
    `serviceGradeAppointedDate` DATE DEFAULT NULL,
    `currentPosition` VARCHAR(191) DEFAULT NULL,
    `joinedCurrentSchoolDate` DATE DEFAULT NULL,
    `isLockedByZonal` TINYINT(1) DEFAULT 0,
    `isLockedBySchool` TINYINT(1) DEFAULT 0,
    `fileNumber` VARCHAR(50) DEFAULT NULL,
    `salaryNumber` VARCHAR(50) DEFAULT NULL,
    `trainingStatus` VARCHAR(100) DEFAULT NULL,
    `natureOfAppointment` VARCHAR(100) DEFAULT NULL,
    `salaryPayingSchool` VARCHAR(191) DEFAULT NULL,
    `undergraduateDegrees` JSON DEFAULT NULL,
    `undergraduateDegreesOther` TEXT DEFAULT NULL,
    `postgraduateDiplomas` JSON DEFAULT NULL,
    `postgraduateDiplomasOther` TEXT DEFAULT NULL,
    `postgraduateDegrees` JSON DEFAULT NULL,
    `postgraduateDegreesOther` TEXT DEFAULT NULL,
    `additionalTeachingQualifications` JSON DEFAULT NULL,
    `pastServiceDetails` JSON DEFAULT NULL,
    `appointmentLetterUrl` VARCHAR(500) DEFAULT NULL,
    `createdByUserId` VARCHAR(36) DEFAULT NULL,
    `updatedByUserId` VARCHAR(36) DEFAULT NULL,
    `createdAt` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updatedAt` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_teacher_nic` (`nic`),
    KEY `idx_teacher_userId` (`userId`),
    KEY `idx_teacher_schoolId` (`schoolId`),
    KEY `idx_teacher_spouseSchoolId` (`spouseSchoolId`),
    KEY `idx_teacher_nameWithInitials` (`nameWithInitials`),
    KEY `idx_teacher_gender` (`gender`),
    KEY `idx_teacher_serviceGrade` (`serviceGrade`),
    KEY `idx_teacher_createdByUserId` (`createdByUserId`),
    KEY `idx_teacher_updatedByUserId` (`updatedByUserId`),
    CONSTRAINT `fk_teacher_userId` FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_teacher_schoolId` FOREIGN KEY (`schoolId`) REFERENCES `school` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_teacher_spouseSchoolId` FOREIGN KEY (`spouseSchoolId`) REFERENCES `school` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_teacher_createdByUserId` FOREIGN KEY (`createdByUserId`) REFERENCES `user` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_teacher_updatedByUserId` FOREIGN KEY (`updatedByUserId`) REFERENCES `user` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 5. TEACHERSUBJECT TABLE (Pivot)
-- Links teachers to their subjects
-- ============================================================
CREATE TABLE `teachersubject` (
    `id` VARCHAR(36) NOT NULL,
    `teacherId` VARCHAR(36) NOT NULL,
    `subjectId` VARCHAR(36) NOT NULL,
    `createdAt` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_ts_teacherId` (`teacherId`),
    KEY `idx_ts_subjectId` (`subjectId`),
    UNIQUE KEY `uk_ts_teacher_subject` (`teacherId`, `subjectId`),
    CONSTRAINT `fk_ts_teacherId` FOREIGN KEY (`teacherId`) REFERENCES `teacher` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_ts_subjectId` FOREIGN KEY (`subjectId`) REFERENCES `subject` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 6. STUDENTCOUNT TABLE
-- Student demographics per school, grade, and academic year
-- ============================================================
CREATE TABLE `studentcount` (
    `id` VARCHAR(36) NOT NULL,
    `schoolId` VARCHAR(36) NOT NULL,
    `grade` VARCHAR(50) NOT NULL,
    `academicYear` VARCHAR(20) NOT NULL,
    `maleCount` INT NOT NULL DEFAULT 0,
    `femaleCount` INT NOT NULL DEFAULT 0,
    `classCount` INT NOT NULL DEFAULT 0,
    `classes` VARCHAR(500) DEFAULT NULL,
    `classDetails` JSON DEFAULT NULL,
    `createdAt` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updatedAt` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_sc_schoolId` (`schoolId`),
    KEY `idx_sc_academicYear` (`academicYear`),
    KEY `idx_sc_grade` (`grade`),
    UNIQUE KEY `uk_sc_school_grade_year` (`schoolId`, `grade`, `academicYear`),
    CONSTRAINT `fk_sc_schoolId` FOREIGN KEY (`schoolId`) REFERENCES `school` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 7. SUBJECTSTATISTIC TABLE
-- Subject-level statistics per school
-- ============================================================
CREATE TABLE `subjectstatistic` (
    `id` VARCHAR(36) NOT NULL,
    `schoolId` VARCHAR(36) NOT NULL,
    `subject` VARCHAR(191) NOT NULL,
    `grade` INT DEFAULT NULL,
    `gender` VARCHAR(20) DEFAULT NULL,
    `medium` VARCHAR(50) DEFAULT NULL,
    `studentCount` INT NOT NULL DEFAULT 0,
    `academicYear` VARCHAR(20) DEFAULT NULL,
    `createdAt` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updatedAt` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_ss_schoolId` (`schoolId`),
    KEY `idx_ss_subject` (`subject`),
    KEY `idx_ss_academicYear` (`academicYear`),
    CONSTRAINT `fk_ss_schoolId` FOREIGN KEY (`schoolId`) REFERENCES `school` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 8. TRANSFERREQUEST TABLE
-- Teacher transfer requests between schools
-- ============================================================
CREATE TABLE `transferrequest` (
    `id` VARCHAR(36) NOT NULL,
    `teacherId` VARCHAR(36) NOT NULL,
    `fromSchoolId` VARCHAR(36) DEFAULT NULL,
    `toSchoolId` VARCHAR(36) DEFAULT NULL,
    `reason` TEXT DEFAULT NULL,
    `status` ENUM('PENDING', 'APPROVED', 'REJECTED') NOT NULL DEFAULT 'PENDING',
    `decidedBy` VARCHAR(36) DEFAULT NULL,
    `decidedAt` TIMESTAMP NULL DEFAULT NULL,
    `createdAt` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updatedAt` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_tr_teacherId` (`teacherId`),
    KEY `idx_tr_fromSchoolId` (`fromSchoolId`),
    KEY `idx_tr_toSchoolId` (`toSchoolId`),
    KEY `idx_tr_status` (`status`),
    KEY `idx_tr_decidedBy` (`decidedBy`),
    CONSTRAINT `fk_tr_teacherId` FOREIGN KEY (`teacherId`) REFERENCES `teacher` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_tr_fromSchoolId` FOREIGN KEY (`fromSchoolId`) REFERENCES `school` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_tr_toSchoolId` FOREIGN KEY (`toSchoolId`) REFERENCES `school` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_tr_decidedBy` FOREIGN KEY (`decidedBy`) REFERENCES `user` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 9. SALARYREQUEST TABLE
-- Salary document requests from teachers
-- ============================================================
CREATE TABLE `salaryrequest` (
    `id` VARCHAR(36) NOT NULL,
    `teacherId` VARCHAR(36) NOT NULL,
    `schoolId` VARCHAR(36) DEFAULT NULL,
    `documentType` VARCHAR(100) NOT NULL,
    `periodType` VARCHAR(100) NOT NULL,
    `periodDetails` VARCHAR(500) DEFAULT NULL,
    `status` ENUM('PENDING', 'APPROVED', 'REJECTED', 'COMPLETED') NOT NULL DEFAULT 'PENDING',
    `remarks` TEXT DEFAULT NULL,
    `requestDate` TIMESTAMP NULL DEFAULT NULL,
    `processedDate` TIMESTAMP NULL DEFAULT NULL,
    `processedBy` VARCHAR(36) DEFAULT NULL,
    `createdAt` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updatedAt` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_sr_teacherId` (`teacherId`),
    KEY `idx_sr_schoolId` (`schoolId`),
    KEY `idx_sr_status` (`status`),
    KEY `idx_sr_processedBy` (`processedBy`),
    CONSTRAINT `fk_sr_teacherId` FOREIGN KEY (`teacherId`) REFERENCES `teacher` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_sr_schoolId` FOREIGN KEY (`schoolId`) REFERENCES `school` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_sr_processedBy` FOREIGN KEY (`processedBy`) REFERENCES `user` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 10. PROFILEEDITREQUEST TABLE
-- Teacher profile edit requests for approval
-- ============================================================
CREATE TABLE `profileeditrequest` (
    `id` VARCHAR(36) NOT NULL,
    `teacherId` VARCHAR(36) NOT NULL,
    `schoolId` VARCHAR(36) DEFAULT NULL,
    `fieldName` VARCHAR(100) NOT NULL,
    `fieldLabel` VARCHAR(191) DEFAULT NULL,
    `currentValue` TEXT DEFAULT NULL,
    `requestedValue` TEXT DEFAULT NULL,
    `reason` TEXT DEFAULT NULL,
    `status` ENUM('PENDING', 'APPROVED', 'REJECTED') NOT NULL DEFAULT 'PENDING',
    `adminRemarks` TEXT DEFAULT NULL,
    `processedBy` VARCHAR(36) DEFAULT NULL,
    `processedDate` TIMESTAMP NULL DEFAULT NULL,
    `createdAt` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updatedAt` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_per_teacherId` (`teacherId`),
    KEY `idx_per_schoolId` (`schoolId`),
    KEY `idx_per_status` (`status`),
    KEY `idx_per_processedBy` (`processedBy`),
    CONSTRAINT `fk_per_teacherId` FOREIGN KEY (`teacherId`) REFERENCES `teacher` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_per_schoolId` FOREIGN KEY (`schoolId`) REFERENCES `school` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_per_processedBy` FOREIGN KEY (`processedBy`) REFERENCES `user` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 11. AUDITLOG TABLE
-- System audit trail for all actions
-- ============================================================
CREATE TABLE `auditlog` (
    `id` VARCHAR(36) NOT NULL,
    `userId` VARCHAR(36) DEFAULT NULL,
    `userEmail` VARCHAR(191) DEFAULT NULL,
    `action` VARCHAR(100) NOT NULL,
    `resourceType` VARCHAR(100) DEFAULT NULL,
    `resourceId` VARCHAR(36) DEFAULT NULL,
    `details` JSON DEFAULT NULL,
    `createdAt` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_al_userId` (`userId`),
    KEY `idx_al_action` (`action`),
    KEY `idx_al_resourceType` (`resourceType`),
    KEY `idx_al_createdAt` (`createdAt`),
    CONSTRAINT `fk_al_userId` FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- LARAVEL FRAMEWORK TABLES
-- Required for sessions, cache, and queue
-- ============================================================

-- Sessions (for session driver = database; optional if using file driver)
CREATE TABLE `sessions` (
    `id` VARCHAR(255) NOT NULL,
    `user_id` BIGINT UNSIGNED DEFAULT NULL,
    `ip_address` VARCHAR(45) DEFAULT NULL,
    `user_agent` TEXT DEFAULT NULL,
    `payload` LONGTEXT NOT NULL,
    `last_activity` INT NOT NULL,
    PRIMARY KEY (`id`),
    KEY `sessions_user_id_index` (`user_id`),
    KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Cache (for cache driver = database)
CREATE TABLE `cache` (
    `key` VARCHAR(255) NOT NULL,
    `value` MEDIUMTEXT NOT NULL,
    `expiration` INT NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache_locks` (
    `key` VARCHAR(255) NOT NULL,
    `owner` VARCHAR(255) NOT NULL,
    `expiration` INT NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Jobs (for queue driver = database)
CREATE TABLE `jobs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `queue` VARCHAR(255) NOT NULL,
    `payload` LONGTEXT NOT NULL,
    `attempts` TINYINT UNSIGNED NOT NULL,
    `reserved_at` INT UNSIGNED DEFAULT NULL,
    `available_at` INT UNSIGNED NOT NULL,
    `created_at` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`id`),
    KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `failed_jobs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `uuid` VARCHAR(255) NOT NULL,
    `connection` TEXT NOT NULL,
    `queue` TEXT NOT NULL,
    `payload` LONGTEXT NOT NULL,
    `exception` LONGTEXT NOT NULL,
    `failed_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- ADD FOREIGN KEY: user.schoolId -> school.id
-- (Added after both tables exist)
-- ============================================================
ALTER TABLE `user`
    ADD CONSTRAINT `fk_user_schoolId` FOREIGN KEY (`schoolId`) REFERENCES `school` (`id`) ON DELETE SET NULL;

-- ============================================================
-- SEED DATA: Default Zonal Admin User
-- Email: admin@wilgamuwa.com
-- Password: password123 (bcrypt hashed)
-- ============================================================
INSERT INTO `user` (`id`, `name`, `email`, `password`, `role`, `isActive`, `createdAt`, `updatedAt`) VALUES
('01JMQZXK0000000000000ADMIN', 'Zonal Administrator', 'admin@wilgamuwa.com', '$2y$12$aIiuPGv4yP6T8FpPsTEnh.ZMOpc9g8hVade/awSpvFzuSZN6ls0Ka', 'ZONAL_ADMIN', 1, NOW(), NOW());

-- Default admin login:
--   Email:    admin@wilgamuwa.com
--   Password: password123

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- SCHEMA VERIFICATION SUMMARY
-- ============================================================
-- Tables (11 app + 5 framework = 16 total):
--   1.  user               - Auth, roles, school assignment
--   2.  school             - School records with principal info
--   3.  subject            - Subject definitions
--   4.  teacher            - Teacher profiles (40+ fields)
--   5.  teachersubject     - Teacher <-> Subject pivot
--   6.  studentcount       - Student demographics per grade/year
--   7.  subjectstatistic   - Subject stats per school
--   8.  transferrequest    - Transfer workflow (PENDING/APPROVED/REJECTED)
--   9.  salaryrequest      - Salary doc requests
--   10. profileeditrequest - Profile edit approval workflow
--   11. auditlog           - Full audit trail
--   12. sessions           - Laravel sessions
--   13. cache              - Laravel cache
--   14. cache_locks        - Laravel cache locks
--   15. jobs               - Laravel queue jobs
--   16. failed_jobs        - Laravel failed jobs
--
-- All column names use camelCase to match Eloquent models.
-- All foreign keys are properly defined with ON DELETE actions.
-- Unique constraints on: user.email, school.censusNo, teacher.nic,
--   teachersubject(teacherId+subjectId), studentcount(schoolId+grade+year)
-- ============================================================
