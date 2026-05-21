-- SQL Script untuk mengosongkan tabel transaksi
-- Master data dan konten web dipertahankan
-- Jalankan di database MySQL/MariaDB

SET FOREIGN_KEY_CHECKS = 0;

-- Transaksi Akademik
TRUNCATE TABLE lesson_session_cases;
TRUNCATE TABLE lesson_session_assignments;
TRUNCATE TABLE lesson_session_materials;
TRUNCATE TABLE lesson_sessions;
TRUNCATE TABLE curriculum_plan_topics;
TRUNCATE TABLE curriculum_plans;
TRUNCATE TABLE session_assessment_scores;
TRUNCATE TABLE session_assessments;
TRUNCATE TABLE exam_scores;
TRUNCATE TABLE exam_sessions;
TRUNCATE TABLE student_attendances;

-- Tahfidz
TRUNCATE TABLE tahfidz_grades;
TRUNCATE TABLE tahfidz_participants;

-- Ekstrakurikuler
TRUNCATE TABLE extracurricular_gallery_items;
TRUNCATE TABLE extracurricular_achievements;
TRUNCATE TABLE extracurricular_members;
TRUNCATE TABLE extracurricular_schedules;

-- Materi & Tugas Kelas
TRUNCATE TABLE class_materials;
TRUNCATE TABLE class_assignments;

-- Catatan Orang Tua
TRUNCATE TABLE parent_note_messages;
TRUNCATE TABLE parent_notes;

-- Alumni & Tracer Study
TRUNCATE TABLE tracer_studies;
TRUNCATE TABLE alumni;

-- SPMB (Penerimaan Siswa Baru)
TRUNCATE TABLE spmb_documents;
TRUNCATE TABLE spmb_registrations;
TRUNCATE TABLE spmb_periods;

-- Logs & Cache
TRUNCATE TABLE activity_log;
TRUNCATE TABLE notifications;
TRUNCATE TABLE internal_announcements;
TRUNCATE TABLE cache;
TRUNCATE TABLE cache_locks;
TRUNCATE TABLE jobs;
TRUNCATE TABLE job_batches;
TRUNCATE TABLE failed_jobs;

-- Laravel Sessions (opsional - akan logout semua user)
TRUNCATE TABLE sessions;

SET FOREIGN_KEY_CHECKS = 1;
