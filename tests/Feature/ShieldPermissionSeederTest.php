<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\ShieldPermissionSeeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Property-based tests for ShieldPermissionSeeder.
 * 
 * These tests validate the correctness properties of the seeder:
 * - Property 2: Seeder assigns exactly the declared map and never touches Super_Admin
 * - Property 3: Seeder idempotence under repetition - run twice should equal run once
 * - Property 4: Seeder preserves assignments of unmanaged roles
 * - Property 5: Seeder fails fast on the first missing permission
 * - Property 6: Ekskul role map excludes hidden-resource permissions
 * 
 * Validates: Requirements 3.1, 3.2, 3.3, 3.4, 3.5, 7.2
 */
class ShieldPermissionSeederTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Resource names that use HidesFromEkskulRole trait.
     * These should be excluded from guru_ekstrakurikuler permissions.
     * 
     * Note: Student is NOT in this list because the seeder intentionally
     * includes ViewAny:Student and View:Student for extracurricular context.
     */
    protected array $hiddenFromEkskulResources = [
        'User',
        'TahfidzClass',
        'Tag',
        'StudentViolation',
        'StudentPayment',
        'StudentAttendance',
        'StaffSchedule',
        'StaffMember',
        'StaffCategory',
        'SpmbRegistration',
        'SpmbPeriod',
        'Slider',
        'SchoolEvent',
        'SchoolClass',
        'QuranSurah',
        'Quiz',
        'QuestionBank',
        'Program',
        'Popup',
        'PageHero',
        'ParentNote',
        'NewsCategory',
        'News',
        'Media',
        'MaterialCategory',
        'Material',
        'LessonSession',
        'KkoLevel',
        'InternalAnnouncement',
        'Grade',
        'Gallery',
        'ExamSession',
        'DownloadCategory',
        'Download',
        'CurriculumPlan',
        'CounselingTicket',
        'ContactMessage',
        'ClassAssignment',
        'ClassAnnouncement',
        'ClassMaterial',
        'Achievement',
        'Academic',
    ];

    /**
     * Unmanaged roles that should be preserved by the seeder.
     */
    protected array $unmanagedRoles = [
        'student',
        'parent',
        'panel_user',
    ];

    /**
     * Get permissions from the database.
     */
    protected function getPermissions(): \Illuminate\Support\Collection
    {
        return Permission::pluck('name');
    }

    /**
     * Get role's assigned permissions.
     */
    protected function getRolePermissions(string $roleName): \Illuminate\Support\Collection
    {
        $role = Role::where('name', $roleName)->first();
        
        if (!$role) {
            return collect();
        }
        
        return $role->permissions->pluck('name');
    }

    /**
     * Property 2: Seeder assigns exactly the declared map and never touches Super_Admin.
     * 
     * Validates: Requirements 3.1, 3.2
     * 
     * This test verifies that:
     * 1. Each managed role gets exactly the permissions declared in the map
     * 2. super_admin is never touched by the seeder
     * 3. No extra permissions are added to managed roles
     */
    public function test_seeder_assigns_exactly_the_declared_map(): void
    {
        // Create permissions first
        $this->seed(PermissionSeederForTest::class);
        
        // Create roles
        $this->createRoles(['admin', 'teacher', 'counselor', 'editor', 'piket', 'guru_ekstrakurikuler', 'super_admin']);
        
        // Run the seeder
        $this->seed(ShieldPermissionSeeder::class);
        
        $map = ShieldPermissionSeeder::map();
        
        // For each managed role, verify exactly the declared permissions are assigned
        foreach ($map as $roleName => $expectedPermissions) {
            $actualPermissions = $this->getRolePermissions($roleName)->toArray();
            $expectedPermissionsSorted = $expectedPermissions;
            sort($expectedPermissionsSorted);
            sort($actualPermissions);
            
            $this->assertEquals(
                $expectedPermissionsSorted,
                $actualPermissions,
                "Role {$roleName} should have exactly the declared permissions"
            );
        }
    }

    /**
     * Property 2: Verify super_admin is never touched by the seeder.
     * 
     * The seeder should NOT modify super_admin's permissions.
     * If super_admin already had admin's permissions BEFORE the seeder ran,
     * the test should NOT fail - only if the seeder ADDED new permissions should it fail.
     */
    public function test_seeder_never_touches_super_admin(): void
    {
        // Create permissions first
        $this->seed(PermissionSeederForTest::class);
        
        // Create roles including super_admin
        $this->createRoles(['admin', 'teacher', 'super_admin']);
        
        // Assign some permissions to super_admin before running seeder
        $superAdmin = Role::where('name', 'super_admin')->first();
        $somePermission = Permission::first();
        
        if ($somePermission) {
            $superAdmin->givePermissionTo($somePermission);
        }
        
        // Record super_admin permissions BEFORE running seeder
        $superAdminPermissionsBefore = $this->getRolePermissions('super_admin')->sort()->values()->toArray();
        
        // Run the seeder
        $this->seed(ShieldPermissionSeeder::class);
        
        // Verify super_admin still has the same permissions after running seeder
        $superAdminPermissionsAfter = $this->getRolePermissions('super_admin')->sort()->values()->toArray();
        
        $this->assertEquals(
            $superAdminPermissionsBefore,
            $superAdminPermissionsAfter,
            'super_admin permissions should not be modified by the seeder'
        );
    }

    /**
     * Property 3: Seeder idempotence under repetition.
     * 
     * Validates: Requirement 3.3
     * 
     * Running the seeder twice should produce the same result as running it once.
     */
    public function test_seeder_idempotence_under_repetition(): void
    {
        // Create permissions first
        $this->seed(PermissionSeederForTest::class);
        
        // Create roles
        $this->createRoles(['admin', 'teacher', 'counselor', 'editor', 'piket', 'guru_ekstrakurikuler']);
        
        // Run the seeder once
        $this->seed(ShieldPermissionSeeder::class);
        
        // Record the permissions after first run
        $permissionsAfterFirstRun = [];
        foreach (['admin', 'teacher', 'counselor', 'editor', 'piket', 'guru_ekstrakurikuler'] as $roleName) {
            $permissionsAfterFirstRun[$roleName] = $this->getRolePermissions($roleName)->sort()->values()->toArray();
        }
        
        // Run the seeder again (idempotent)
        $this->seed(ShieldPermissionSeeder::class);
        
        // Record the permissions after second run
        $permissionsAfterSecondRun = [];
        foreach (['admin', 'teacher', 'counselor', 'editor', 'piket', 'guru_ekstrakurikuler'] as $roleName) {
            $permissionsAfterSecondRun[$roleName] = $this->getRolePermissions($roleName)->sort()->values()->toArray();
        }
        
        // Verify the permissions are the same after both runs
        foreach (['admin', 'teacher', 'counselor', 'editor', 'piket', 'guru_ekstrakurikuler'] as $roleName) {
            $this->assertEquals(
                $permissionsAfterFirstRun[$roleName],
                $permissionsAfterSecondRun[$roleName],
                "Role {$roleName} should have same permissions after running seeder twice"
            );
        }
    }

    /**
     * Property 4: Seeder preserves assignments of unmanaged roles.
     * 
     * Validates: Requirement 3.5
     * 
     * The seeder should not affect roles that are not in the map (student, parent, panel_user).
     */
    public function test_seeder_preserves_unmanaged_roles(): void
    {
        // Create permissions first
        $this->seed(PermissionSeederForTest::class);
        
        // Create roles including unmanaged ones
        $this->createRoles(['admin', 'student', 'parent', 'panel_user']);
        
        // Assign some permissions to unmanaged roles before running seeder
        $studentRole = Role::where('name', 'student')->first();
        $parentRole = Role::where('name', 'parent')->first();
        $panelUserRole = Role::where('name', 'panel_user')->first();
        
        $somePermission = Permission::first();
        $anotherPermission = Permission::skip(1)->first();
        
        if ($somePermission && $studentRole) {
            $studentRole->givePermissionTo($somePermission);
        }
        
        if ($anotherPermission && $parentRole) {
            $parentRole->givePermissionTo($anotherPermission);
        }
        
        // Record original permissions
        $studentPermissionsBefore = $this->getRolePermissions('student');
        $parentPermissionsBefore = $this->getRolePermissions('parent');
        $panelUserPermissionsBefore = $this->getRolePermissions('panel_user');
        
        // Run the seeder
        $this->seed(ShieldPermissionSeeder::class);
        
        // Verify unmanaged roles still have their original permissions
        $this->assertEquals(
            $studentPermissionsBefore->sort()->values()->toArray(),
            $this->getRolePermissions('student')->sort()->values()->toArray(),
            'Student role permissions should be preserved'
        );
        
        $this->assertEquals(
            $parentPermissionsBefore->sort()->values()->toArray(),
            $this->getRolePermissions('parent')->sort()->values()->toArray(),
            'Parent role permissions should be preserved'
        );
        
        $this->assertEquals(
            $panelUserPermissionsBefore->sort()->values()->toArray(),
            $this->getRolePermissions('panel_user')->sort()->values()->toArray(),
            'Panel user role permissions should be preserved'
        );
    }

    /**
     * Property 5: Seeder fails fast on the first missing permission.
     * 
     * Validates: Requirement 3.4
     * 
     * If a permission doesn't exist in the database, the seeder should throw an exception
     * with the name of the first missing permission.
     */
    public function test_seeder_fails_fast_on_missing_permission(): void
    {
        // Create only partial permissions (missing some that the seeder expects)
        $this->createRoles(['admin']);
        
        // Create only a few permissions, not all that the seeder needs
        $partialPermissions = [
            'ViewAny:Student',
            'View:Student',
            // Missing many others that admin role needs
        ];
        
        foreach ($partialPermissions as $permName) {
            Permission::create(['name' => $permName, 'guard_name' => 'web']);
        }
        
        // Run the seeder and expect it to fail
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('not found');
        
        $this->seed(ShieldPermissionSeeder::class);
    }

    /**
     * Property 6: Ekskul role map excludes hidden-resource permissions.
     * 
     * Validates: Requirement 7.2
     * 
     * The guru_ekstrakurikuler role should NOT have permissions for resources
     * that use the HidesFromEkskulRole trait.
     */
    public function test_ekskul_map_excludes_hidden_resources(): void
    {
        // Create all permissions
        $this->seed(PermissionSeederForTest::class);
        
        // Create roles
        $this->createRoles(['admin', 'guru_ekstrakurikuler']);
        
        // Run the seeder
        $this->seed(ShieldPermissionSeeder::class);
        
        // Get guru_ekstrakurikuler permissions
        $ekskulPermissions = $this->getRolePermissions('guru_ekstrakurikuler');
        
        // Verify that hidden resources are NOT in the ekskul permissions
        foreach ($this->hiddenFromEkskulResources as $resource) {
            $permissionPrefixes = ['ViewAny', 'View', 'Create', 'Update', 'Delete'];
            
            foreach ($permissionPrefixes as $prefix) {
                $permissionName = "{$prefix}:{$resource}";
                
                $this->assertFalse(
                    $ekskulPermissions->contains($permissionName),
                    "guru_ekstrakurikuler should NOT have {$permissionName} (hidden from ekskul)"
                );
            }
        }
        
        // Verify that extracurricular-related permissions ARE included
        $this->assertTrue(
            $ekskulPermissions->contains('ViewAny:Extracurricular'),
            'guru_ekstrakurikuler should have ViewAny:Extracurricular'
        );
        
        $this->assertTrue(
            $ekskulPermissions->contains('ViewAny:ExtracurricularMember'),
            'guru_ekstrakurikuler should have ViewAny:ExtracurricularMember'
        );
        
        // Verify basic student view is included (as per spec)
        $this->assertTrue(
            $ekskulPermissions->contains('ViewAny:Student'),
            'guru_ekstrakurikuler should have ViewAny:Student'
        );
    }

    /**
     * Create test roles.
     */
    protected function createRoles(array $roleNames): void
    {
        foreach ($roleNames as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }
    }
}

/**
 * Helper seeder to create all permissions needed for testing.
 * This creates a minimal set of permissions that the ShieldPermissionSeeder expects.
 */
class PermissionSeederForTest extends \Illuminate\Database\Seeder
{
    public function run(): void
    {
        $permissions = $this->getRequiredPermissions();
        
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }
    }

    /**
     * Get all permissions required by ShieldPermissionSeeder map.
     */
    protected function getRequiredPermissions(): array
    {
        $map = ShieldPermissionSeeder::map();
        $permissions = [];
        
        foreach ($map as $roleName => $rolePermissions) {
            foreach ($rolePermissions as $permission) {
                $permissions[] = $permission;
            }
        }
        
        return array_unique($permissions);
    }
}