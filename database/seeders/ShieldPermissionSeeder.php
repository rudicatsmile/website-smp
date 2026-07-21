<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Seeder that assigns baseline permissions to roles based on the old canAccess() patterns.
 * 
 * This seeder is idempotent - running it multiple times produces the same result.
 * It uses syncPermissions() for full replacement of managed role permissions.
 * 
 * Requirements: 3.1, 3.2, 3.3, 3.4, 3.5
 */
class ShieldPermissionSeeder extends Seeder
{
    /**
     * Mapping of role names to their allowed permissions.
     * Derived from the old canAccess() patterns in Resources and Pages.
     * 
     * Key points:
     * - super_admin is NOT included (gets access via Shield gate intercept)
     * - guru_ekstrakurikuler excludes permissions for resources hidden via HidesFromEkskulRole trait
     * 
     * @return array<string, array<int, string>>
     */
    public static function map(): array
    {
        return [
            // Admin: full access to all resources
            'admin' => [
                // Student-related (admin only in canAccess)
                'ViewAny:Student',
                'View:Student',
                'Create:Student',
                'Update:Student',
                'Delete:Student',
                'ViewAny:StudentPayment',
                'View:StudentPayment',
                'Create:StudentPayment',
                'Update:StudentPayment',
                'Delete:StudentPayment',
                'ViewAny:SchoolClass',
                'View:SchoolClass',
                'Create:SchoolClass',
                'Update:SchoolClass',
                'Delete:SchoolClass',
                'ViewAny:NotificationLog',
                'View:NotificationLog',
                'Create:NotificationLog',
                'Update:NotificationLog',
                'Delete:NotificationLog',

                // Teacher-scoped resources (teacher + admin)
                'ViewAny:Quiz',
                'View:Quiz',
                'Create:Quiz',
                'Update:Quiz',
                'Delete:Quiz',
                'ViewAny:QuestionBank',
                'View:QuestionBank',
                'Create:QuestionBank',
                'Update:QuestionBank',
                'Delete:QuestionBank',
                'ViewAny:LessonSession',
                'View:LessonSession',
                'Create:LessonSession',
                'Update:LessonSession',
                'Delete:LessonSession',
                'ViewAny:CurriculumPlan',
                'View:CurriculumPlan',
                'Create:CurriculumPlan',
                'Update:CurriculumPlan',
                'Delete:CurriculumPlan',
                'Replicate:CurriculumPlan',
                'ViewAny:Grade',
                'View:Grade',
                'Create:Grade',
                'Update:Grade',
                'Delete:Grade',
                'ViewAny:StudentAttendance',
                'View:StudentAttendance',
                'Create:StudentAttendance',
                'Update:StudentAttendance',
                'Delete:StudentAttendance',
                'ViewAny:ExamSession',
                'View:ExamSession',
                'Create:ExamSession',
                'Update:ExamSession',
                'Delete:ExamSession',
                'ViewAny:LeaveRequest',
                'View:LeaveRequest',
                'Create:LeaveRequest',
                'Update:LeaveRequest',
                'Delete:LeaveRequest',
                'ViewAny:ParentNote',
                'View:ParentNote',
                'Create:ParentNote',
                'Update:ParentNote',
                'Delete:ParentNote',

                // Counselor resources
                'ViewAny:StudentViolation',
                'View:StudentViolation',
                'Create:StudentViolation',
                'Update:StudentViolation',
                'Delete:StudentViolation',
                'ViewAny:CounselingTicket',
                'View:CounselingTicket',
                'Create:CounselingTicket',
                'Update:CounselingTicket',
                'Delete:CounselingTicket',

                // Editor resources
                'ViewAny:ClassMaterial',
                'View:ClassMaterial',
                'Create:ClassMaterial',
                'Update:ClassMaterial',
                'Delete:ClassMaterial',
                'ViewAny:ClassAnnouncement',
                'View:ClassAnnouncement',
                'Create:ClassAnnouncement',
                'Update:ClassAnnouncement',
                'Delete:ClassAnnouncement',
                'ViewAny:ClassAssignment',
                'View:ClassAssignment',
                'Create:ClassAssignment',
                'Update:ClassAssignment',
                'Delete:ClassAssignment',

                // All other resources - admin gets full access to everything
                // Extracurriculars & members
                'ViewAny:Extracurricular',
                'View:Extracurricular',
                'Create:Extracurricular',
                'Update:Extracurricular',
                'Delete:Extracurricular',
                'ViewAny:ExtracurricularMember',
                'View:ExtracurricularMember',
                'Create:ExtracurricularMember',
                'Update:ExtracurricularMember',
                'Delete:ExtracurricularMember',

                // Academic & Curriculum
                'ViewAny:Academic',
                'View:Academic',
                'Create:Academic',
                'Update:Academic',
                'Delete:Academic',
                'ViewAny:LearningModel',
                'View:LearningModel',
                'Create:LearningModel',
                'Update:LearningModel',
                'Delete:LearningModel',
                'ViewAny:LearningMethod',
                'View:LearningMethod',
                'Create:LearningMethod',
                'Update:LearningMethod',
                'Delete:LearningMethod',
                'ViewAny:LearningObjective',
                'View:LearningObjective',
                'Create:LearningObjective',
                'Update:LearningObjective',
                'Delete:LearningObjective',
                'ViewAny:LearningMedia',
                'View:LearningMedia',
                'Create:LearningMedia',
                'Update:LearningMedia',
                'Delete:LearningMedia',
                'ViewAny:AssessmentType',
                'View:AssessmentType',
                'Create:AssessmentType',
                'Update:AssessmentType',
                'Delete:AssessmentType',
                'ViewAny:KkoLevel',
                'View:KkoLevel',
                'Create:KkoLevel',
                'Update:KkoLevel',
                'Delete:KkoLevel',

                // Staff & Schedule
                'ViewAny:StaffMember',
                'View:StaffMember',
                'Create:StaffMember',
                'Update:StaffMember',
                'Delete:StaffMember',
                'ViewAny:StaffCategory',
                'View:StaffCategory',
                'Create:StaffCategory',
                'Update:StaffCategory',
                'Delete:StaffCategory',
                'ViewAny:StaffSchedule',
                'View:StaffSchedule',
                'Create:StaffSchedule',
                'Update:StaffSchedule',
                'Delete:StaffSchedule',

                // Users & Roles
                'ViewAny:User',
                'View:User',
                'Create:User',
                'Update:User',
                'Delete:User',
                'ViewAny:Role',
                'View:Role',
                'Create:Role',
                'Update:Role',
                'Delete:Role',

                // Announcements
                'ViewAny:Announcement',
                'View:Announcement',
                'Create:Announcement',
                'Update:Announcement',
                'Delete:Announcement',
                'ViewAny:InternalAnnouncement',
                'View:InternalAnnouncement',
                'Create:InternalAnnouncement',
                'Update:InternalAnnouncement',
                'Delete:InternalAnnouncement',

                // Content management
                'ViewAny:News',
                'View:News',
                'Create:News',
                'Update:News',
                'Delete:News',
                'ViewAny:NewsCategory',
                'View:NewsCategory',
                'Create:NewsCategory',
                'Update:NewsCategory',
                'Delete:NewsCategory',
                'ViewAny:Faq',
                'View:Faq',
                'Create:Faq',
                'Update:Faq',
                'Delete:Faq',
                'ViewAny:Slider',
                'View:Slider',
                'Create:Slider',
                'Update:Slider',
                'Delete:Slider',
                'ViewAny:Popup',
                'View:Popup',
                'Create:Popup',
                'Update:Popup',
                'Delete:Popup',
                'ViewAny:PageHero',
                'View:PageHero',
                'Create:PageHero',
                'Update:PageHero',
                'Delete:PageHero',
                'ViewAny:Gallery',
                'View:Gallery',
                'Create:Gallery',
                'Update:Gallery',
                'Delete:Gallery',

                // Downloads & Materials
                'ViewAny:Download',
                'View:Download',
                'Create:Download',
                'Update:Download',
                'Delete:Download',
                'ViewAny:DownloadCategory',
                'View:DownloadCategory',
                'Create:DownloadCategory',
                'Update:DownloadCategory',
                'Delete:DownloadCategory',
                'ViewAny:Material',
                'View:Material',
                'Create:Material',
                'Update:Material',
                'Delete:Material',
                'ViewAny:MaterialCategory',
                'View:MaterialCategory',
                'Create:MaterialCategory',
                'Update:MaterialCategory',
                'Delete:MaterialCategory',
                'ViewAny:Media',
                'View:Media',
                'Create:Media',
                'Update:Media',
                'Delete:Media',

                // SPMB & Programs
                'ViewAny:SpmbPeriod',
                'View:SpmbPeriod',
                'Create:SpmbPeriod',
                'Update:SpmbPeriod',
                'Delete:SpmbPeriod',
                'ViewAny:SpmbRegistration',
                'View:SpmbRegistration',
                'Create:SpmbRegistration',
                'Update:SpmbRegistration',
                'Delete:SpmbRegistration',
                'ViewAny:Program',
                'View:Program',
                'Create:Program',
                'Update:Program',
                'Delete:Program',

                // Facilities & Events
                'ViewAny:Facility',
                'View:Facility',
                'Create:Facility',
                'Update:Facility',
                'Delete:Facility',
                'ViewAny:SchoolEvent',
                'View:SchoolEvent',
                'Create:SchoolEvent',
                'Update:SchoolEvent',
                'Delete:SchoolEvent',

                // Alumni & Tracer
                'ViewAny:Alumni',
                'View:Alumni',
                'Create:Alumni',
                'Update:Alumni',
                'Delete:Alumni',
                'ViewAny:TracerStudy',
                'View:TracerStudy',
                'Create:TracerStudy',
                'Update:TracerStudy',
                'Delete:TracerStudy',

                // Achievements
                'ViewAny:Achievement',
                'View:Achievement',
                'Create:Achievement',
                'Update:Achievement',
                'Delete:Achievement',

                // Contact
                'ViewAny:ContactMessage',
                'View:ContactMessage',
                'Create:ContactMessage',
                'Update:ContactMessage',
                'Delete:ContactMessage',

                // Quran & Tahfidz
                'ViewAny:QuranSurah',
                'View:QuranSurah',
                'Create:QuranSurah',
                'Update:QuranSurah',
                'Delete:QuranSurah',
                'ViewAny:TahfidzClass',
                'View:TahfidzClass',
                'Create:TahfidzClass',
                'Update:TahfidzClass',
                'Delete:TahfidzClass',

                // Tags
                'ViewAny:Tag',
                'View:Tag',
                'Create:Tag',
                'Update:Tag',
                'Delete:Tag',

                // Admin-only pages
                'View:CetakKartuSiswa',

                // Teacher pages (accessible to admin)
                'View:TeachingToday',
                'View:JurnalMengajar',
                'View:LaporanAbsensi',
                'View:LaporanPenilaian',
                'View:LaporanKasusSiswa',
                'View:InputNilaiSesi',
                'View:InputNilaiUjian',
                'View:AbsensiHariIni',

                // Profile pages
                'View:ManageProfile',
                'View:ManageGeneral',

                // Widgets
                'View:LessonProgressWidget',
            ],

            // Teacher: access to teaching-related resources
            'teacher' => [
                // Own scoped resources
                'ViewAny:Quiz',
                'View:Quiz',
                'Create:Quiz',
                'Update:Quiz',
                'Delete:Quiz',
                'ViewAny:QuestionBank',
                'View:QuestionBank',
                'Create:QuestionBank',
                'Update:QuestionBank',
                'Delete:QuestionBank',
                'ViewAny:LessonSession',
                'View:LessonSession',
                'Create:LessonSession',
                'Update:LessonSession',
                'Delete:LessonSession',
                'ViewAny:CurriculumPlan',
                'View:CurriculumPlan',
                'Create:CurriculumPlan',
                'Update:CurriculumPlan',
                'Delete:CurriculumPlan',
                'Replicate:CurriculumPlan',
                'ViewAny:Grade',
                'View:Grade',
                'Create:Grade',
                'Update:Grade',
                'Delete:Grade',
                'ViewAny:StudentAttendance',
                'View:StudentAttendance',
                'Create:StudentAttendance',
                'Update:StudentAttendance',
                'Delete:StudentAttendance',
                'ViewAny:ExamSession',
                'View:ExamSession',
                'Create:ExamSession',
                'Update:ExamSession',
                'Delete:ExamSession',
                'ViewAny:LeaveRequest',
                'View:LeaveRequest',
                'Create:LeaveRequest',
                'Update:LeaveRequest',
                'Delete:LeaveRequest',
                'ViewAny:ParentNote',
                'View:ParentNote',
                'Create:ParentNote',
                'Update:ParentNote',
                'Delete:ParentNote',

                // Editor resources (also accessible to teacher)
                'ViewAny:ClassMaterial',
                'View:ClassMaterial',
                'Create:ClassMaterial',
                'Update:ClassMaterial',
                'Delete:ClassMaterial',
                'ViewAny:ClassAnnouncement',
                'View:ClassAnnouncement',
                'Create:ClassAnnouncement',
                'Update:ClassAnnouncement',
                'Delete:ClassAnnouncement',
                'ViewAny:ClassAssignment',
                'View:ClassAssignment',
                'Create:ClassAssignment',
                'Update:ClassAssignment',
                'Delete:ClassAssignment',

                // Teacher pages
                'View:TeachingToday',
                'View:JurnalMengajar',
                'View:LaporanAbsensi',
                'View:LaporanPenilaian',
                'View:LaporanKasusSiswa',
                'View:InputNilaiSesi',
                'View:InputNilaiUjian',

                // Widgets
                'View:LessonProgressWidget',
            ],

            // Counselor: access to counseling-related resources
            'counselor' => [
                // Student violations and counseling
                'ViewAny:StudentViolation',
                'View:StudentViolation',
                'Create:StudentViolation',
                'Update:StudentViolation',
                'Delete:StudentViolation',
                'ViewAny:CounselingTicket',
                'View:CounselingTicket',
                'Create:CounselingTicket',
                'Update:CounselingTicket',
                'Delete:CounselingTicket',

                // Basic student view for counseling context
                'ViewAny:Student',
                'View:Student',
            ],

            // Editor: access to content management resources
            'editor' => [
                // Class materials and announcements
                'ViewAny:ClassMaterial',
                'View:ClassMaterial',
                'Create:ClassMaterial',
                'Update:ClassMaterial',
                'Delete:ClassMaterial',
                'ViewAny:ClassAnnouncement',
                'View:ClassAnnouncement',
                'Create:ClassAnnouncement',
                'Update:ClassAnnouncement',
                'Delete:ClassAnnouncement',
                'ViewAny:ClassAssignment',
                'View:ClassAssignment',
                'Create:ClassAssignment',
                'Update:ClassAssignment',
                'Delete:ClassAssignment',

                // Content management
                'ViewAny:Announcement',
                'View:Announcement',
                'Create:Announcement',
                'Update:Announcement',
                'Delete:Announcement',
                'ViewAny:News',
                'View:News',
                'Create:News',
                'Update:News',
                'Delete:News',
                'ViewAny:NewsCategory',
                'View:NewsCategory',
                'Create:NewsCategory',
                'Update:NewsCategory',
                'Delete:NewsCategory',
                'ViewAny:Faq',
                'View:Faq',
                'Create:Faq',
                'Update:Faq',
                'Delete:Faq',
                'ViewAny:Slider',
                'View:Slider',
                'Create:Slider',
                'Update:Slider',
                'Delete:Slider',
                'ViewAny:Popup',
                'View:Popup',
                'Create:Popup',
                'Update:Popup',
                'Delete:Popup',
                'ViewAny:PageHero',
                'View:PageHero',
                'Create:PageHero',
                'Update:PageHero',
                'Delete:PageHero',
                'ViewAny:Gallery',
                'View:Gallery',
                'Create:Gallery',
                'Update:Gallery',
                'Delete:Gallery',

                // Downloads
                'ViewAny:Download',
                'View:Download',
                'Create:Download',
                'Update:Download',
                'Delete:Download',
                'ViewAny:DownloadCategory',
                'View:DownloadCategory',
                'Create:DownloadCategory',
                'Update:DownloadCategory',
                'Delete:DownloadCategory',
                'ViewAny:Material',
                'View:Material',
                'Create:Material',
                'Update:Material',
                'Delete:Material',
                'ViewAny:MaterialCategory',
                'View:MaterialCategory',
                'Create:MaterialCategory',
                'Update:MaterialCategory',
                'Delete:MaterialCategory',
                'ViewAny:Media',
                'View:Media',
                'Create:Media',
                'Update:Media',
                'Delete:Media',
            ],

            // Piket: access to attendance-related pages
            'piket' => [
                'View:AbsensiHariIni',
                'ViewAny:StudentAttendance',
                'View:StudentAttendance',
                'Create:StudentAttendance',
                'Update:StudentAttendance',
                'Delete:StudentAttendance',
                'ViewAny:Student',
                'View:Student',
            ],

            // Guru Ekstrakurikuler: access to extracurricular resources
            // Excludes resources that use HidesFromEkskulRole trait
            'guru_ekstrakurikuler' => [
                // Extracurricular management
                'ViewAny:Extracurricular',
                'View:Extracurricular',
                'Create:Extracurricular',
                'Update:Extracurricular',
                'Delete:Extracurricular',
                'ViewAny:ExtracurricularMember',
                'View:ExtracurricularMember',
                'Create:ExtracurricularMember',
                'Update:ExtracurricularMember',
                'Delete:ExtracurricularMember',

                // Basic student view for extracurricular context
                'ViewAny:Student',
                'View:Student',
            ],
        ];
    }

    /**
     * Run the database seeds.
     * 
     * This method:
     * - Uses full replacement (syncPermissions) for idempotency
     * - Fails fast if a permission doesn't exist
     * - Preserves permissions for roles not in the map
     * - Does NOT assign permissions to super_admin
     */
    public function run(): void
    {
        $roleMappings = static::map();
        $managedRoles = array_keys($roleMappings);

        // Get all existing permissions
        $existingPermissions = Permission::pluck('name')->toArray();
        $existingPermissionsSet = new Collection($existingPermissions);

        // Process each managed role
        foreach ($roleMappings as $roleName => $permissions) {
            $role = Role::where('name', $roleName)->first();

            if (!$role) {
                $this->command->warn("Role '{$roleName}' not found, skipping.");
                continue;
            }

            // Verify all permissions exist (fail-fast)
            foreach ($permissions as $permissionName) {
                if (!$existingPermissionsSet->contains($permissionName)) {
                    throw new \RuntimeException(
                        "Permission '{$permissionName}' not found for role '{$roleName}'. " .
                        "Please run 'php artisan shield:generate --all' first."
                    );
                }
            }

            // Get permission models
            $permissionModels = Permission::whereIn('name', $permissions)->get();

            // Full replacement - syncPermissions replaces all permissions
            $role->syncPermissions($permissionModels);

            $this->command->info("Assigned {$permissionModels->count()} permissions to role '{$roleName}'.");
        }

        // Log unmanaged roles for clarity
        $allRoles = Role::whereNotIn('name', $managedRoles)
            ->whereNotIn('name', ['super_admin']) // Also exclude super_admin from changes
            ->pluck('name');

        if ($allRoles->isNotEmpty()) {
            $this->command->info("Unmanaged roles (permissions preserved): {$allRoles->implode(', ')}");
        }

        $this->command->info('ShieldPermissionSeeder completed successfully.');
    }
}