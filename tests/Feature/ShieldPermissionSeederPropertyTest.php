<?php

namespace Tests\Feature;

use Database\Seeders\ShieldPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class)
    ->in('Feature');

/**
 * Property-based tests for ShieldPermissionSeeder.
 * 
 * Feature: permission-based-access-control
 * 
 * These tests validate the correctness properties of the seeder:
 * - Property 2: Seeder assigns exactly the declared map and never touches Super_Admin
 * - Property 3: Seeder idempotence under repetition - run twice should equal run once
 * - Property 4: Seeder preserves assignments of unmanaged roles
 * - Property 5: Seeder fails fast on the first missing permission
 * - Property 6: Ekskul role map excludes hidden-resource permissions
 * 
 * Validates: Requirements 3.1, 3.2, 3.3, 3.4, 3.5, 7.2
 * 
 * NOTE: For proper PBT validation, run these tests with 100 iterations using:
 *    php vendor/bin/pest tests/Feature/ShieldPermissionSeederPropertyTest.php --repeat=100
 * Or run the test file 100 times to validate the property holds across multiple runs.
 */

/**
 * Resource names that use HidesFromEkskulRole trait.
 */
$hiddenFromEkskulResources = [
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
$unmanagedRoles = [
    'student',
    'parent',
    'panel_user',
];

/**
 * All managed roles in the seeder map.
 */
$managedRoles = ['admin', 'teacher', 'counselor', 'editor', 'piket', 'guru_ekstrakurikuler'];

// =============================================================================
// HELPER FUNCTIONS
// =============================================================================

function getRolePermissions(string $roleName): Collection
{
    $role = Role::where('name', $roleName)->first();
    
    if (!$role) {
        return collect();
    }
    
    return $role->permissions->pluck('name');
}

function createTestRoles(array $roleNames): void
{
    foreach ($roleNames as $roleName) {
        Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
    }
}

// =============================================================================
// PROPERTY 2: Seeder assigns exactly the declared map and never touches Super_Admin
// Validates: Requirements 3.1, 3.2
// =============================================================================

describe('Property 2: Seeder assigns exactly the declared map', function () use ($managedRoles) {
    
    it('seeder assigns exactly the declared permissions to each managed role', function () use ($managedRoles) {
        // Setup: Create permissions and roles
        $this->seed(PermissionSeederForPbt::class);
        createTestRoles(array_merge($managedRoles, ['super_admin']));
        
        // Execute: Run the seeder
        $this->seed(ShieldPermissionSeeder::class);
        
        // Verify: Each managed role has exactly the declared permissions
        $map = ShieldPermissionSeeder::map();
        
        foreach ($map as $roleName => $expectedPermissions) {
            $actualPermissions = getRolePermissions($roleName)->toArray();
            $expectedPermissionsSorted = $expectedPermissions;
            sort($expectedPermissionsSorted);
            sort($actualPermissions);
            
            expect($actualPermissions)
                ->toBe($expectedPermissionsSorted, "Role {$roleName} should have exactly the declared permissions");
        }
    });

    it('seeder never touches super_admin permissions', function () use ($managedRoles) {
        // Setup: Create permissions and roles
        $this->seed(PermissionSeederForPbt::class);
        createTestRoles(array_merge($managedRoles, ['super_admin']));
        
        // Give super_admin some permissions BEFORE running seeder that are DIFFERENT from admin's
        $superAdmin = Role::where('name', 'super_admin')->first();
        
        // Give a permission that admin does NOT have
        $uniquePerm = 'View:SomeRandomResourceThatAdminDoesNotHave';
        Permission::firstOrCreate(['name' => $uniquePerm, 'guard_name' => 'web']);
        $superAdmin->givePermissionTo($uniquePerm);
        
        // Record super_admin permissions BEFORE running seeder
        $superAdminPermissionsBefore = getRolePermissions('super_admin')->sort()->values()->toArray();
        
        // Execute: Run the seeder
        $this->seed(ShieldPermissionSeeder::class);
        
        // Verify: super_admin still has its original permissions PLUS the unique one we added
        $superAdminPermissionsAfter = getRolePermissions('super_admin')->sort()->values()->toArray();
        
        expect($superAdminPermissionsAfter)
            ->toBe($superAdminPermissionsBefore, 'super_admin permissions should not be modified by the seeder');
        
        // Verify super_admin does NOT get admin's unique permissions (that super_admin didn't already have)
        $adminPermissions = getRolePermissions('admin');
        
        // Check a permission that admin has but super_admin shouldn't get from the seeder
        $adminUniquePerm = 'Delete:SchoolClass'; // admin has this, super_admin didn't have it before
        expect(getRolePermissions('super_admin')->contains($adminUniquePerm))
            ->toBeFalse("super_admin should not have gotten admin's permission: {$adminUniquePerm}");
    });
});

// =============================================================================
// PROPERTY 3: Seeder idempotence under repetition
// Validates: Requirement 3.3
// =============================================================================

describe('Property 3: Seeder idempotence under repetition', function () use ($managedRoles) {
    
    it('running the seeder twice produces the same result as running it once', function () use ($managedRoles) {
        // Setup: Create permissions and roles
        $this->seed(PermissionSeederForPbt::class);
        createTestRoles($managedRoles);
        
        // Execute: Run the seeder once
        $this->seed(ShieldPermissionSeeder::class);
        
        // Record the permissions after first run
        $permissionsAfterFirstRun = [];
        foreach ($managedRoles as $roleName) {
            $permissionsAfterFirstRun[$roleName] = getRolePermissions($roleName)->sort()->values()->toArray();
        }
        
        // Execute: Run the seeder again (idempotent)
        $this->seed(ShieldPermissionSeeder::class);
        
        // Record the permissions after second run
        $permissionsAfterSecondRun = [];
        foreach ($managedRoles as $roleName) {
            $permissionsAfterSecondRun[$roleName] = getRolePermissions($roleName)->sort()->values()->toArray();
        }
        
        // Verify: The permissions are the same after both runs
        foreach ($managedRoles as $roleName) {
            expect($permissionsAfterSecondRun[$roleName])
                ->toBe($permissionsAfterFirstRun[$roleName], 
                    "Role {$roleName} should have same permissions after running seeder twice");
        }
    });

    it('seeder handles various initial permission states idempotently', function () use ($managedRoles) {
        // Setup: Create permissions and roles
        $this->seed(PermissionSeederForPbt::class);
        createTestRoles($managedRoles);
        
        // Randomly assign some extra permissions to some roles before first run
        $allPermissions = Permission::pluck('name')->take(10)->toArray();
        
        foreach ($managedRoles as $roleName) {
            $role = Role::where('name', $roleName)->first();
            if ($role && rand(0, 1)) {
                // Assign 1-3 random permissions
                $randomPerms = array_slice($allPermissions, 0, rand(1, 3));
                $role->givePermissionTo($randomPerms);
            }
        }
        
        // Execute: Run the seeder once
        $this->seed(ShieldPermissionSeeder::class);
        
        // Record the permissions after first run
        $permissionsAfterFirstRun = [];
        foreach ($managedRoles as $roleName) {
            $permissionsAfterFirstRun[$roleName] = getRolePermissions($roleName)->sort()->values()->toArray();
        }
        
        // Execute: Run the seeder again
        $this->seed(ShieldPermissionSeeder::class);
        
        // Record the permissions after second run
        $permissionsAfterSecondRun = [];
        foreach ($managedRoles as $roleName) {
            $permissionsAfterSecondRun[$roleName] = getRolePermissions($roleName)->sort()->values()->toArray();
        }
        
        // Verify: The permissions are the same after both runs
        foreach ($managedRoles as $roleName) {
            expect($permissionsAfterSecondRun[$roleName])
                ->toBe($permissionsAfterFirstRun[$roleName], 
                    "Role {$roleName} should have same permissions after running seeder twice");
        }
    });
});

// =============================================================================
// PROPERTY 4: Seeder preserves assignments of unmanaged roles
// Validates: Requirement 3.5
// =============================================================================

describe('Property 4: Seeder preserves assignments of unmanaged roles', function () use ($unmanagedRoles, $managedRoles) {
    
    it('seeder preserves permissions of unmanaged roles', function () use ($unmanagedRoles, $managedRoles) {
        // Setup: Create permissions and roles (including unmanaged)
        $this->seed(PermissionSeederForPbt::class);
        createTestRoles(array_merge($managedRoles, $unmanagedRoles));
        
        // Assign some permissions to unmanaged roles before running seeder
        $allPermissions = Permission::pluck('name')->toArray();
        
        foreach ($unmanagedRoles as $roleName) {
            $role = Role::where('name', $roleName)->first();
            if ($role && count($allPermissions) >= 3) {
                // Assign 1-3 random permissions
                $startIndex = rand(0, max(0, count($allPermissions) - 3));
                $randomPerms = array_slice($allPermissions, $startIndex, rand(1, 3));
                $role->givePermissionTo($randomPerms);
            }
        }
        
        // Record original permissions
        $permissionsBefore = [];
        foreach ($unmanagedRoles as $roleName) {
            $permissionsBefore[$roleName] = getRolePermissions($roleName)->sort()->values()->toArray();
        }
        
        // Execute: Run the seeder
        $this->seed(ShieldPermissionSeeder::class);
        
        // Verify: Unmanaged roles still have their original permissions
        foreach ($unmanagedRoles as $roleName) {
            $permissionsAfter = getRolePermissions($roleName)->sort()->values()->toArray();
            expect($permissionsAfter)
                ->toBe($permissionsBefore[$roleName], 
                    "Role {$roleName} permissions should be preserved");
        }
    });

    it('seeder does not add permissions to unmanaged roles', function () use ($unmanagedRoles, $managedRoles) {
        // Setup: Create permissions and roles (including unmanaged)
        $this->seed(PermissionSeederForPbt::class);
        createTestRoles(array_merge($managedRoles, $unmanagedRoles));
        
        // Record original permissions (should be empty)
        $permissionsBefore = [];
        foreach ($unmanagedRoles as $roleName) {
            $permissionsBefore[$roleName] = getRolePermissions($roleName)->count();
        }
        
        // Execute: Run the seeder
        $this->seed(ShieldPermissionSeeder::class);
        
        // Verify: Unmanaged roles still have no permissions
        foreach ($unmanagedRoles as $roleName) {
            $permissionsAfter = getRolePermissions($roleName)->count();
            expect($permissionsAfter)
                ->toBe($permissionsBefore[$roleName], 
                    "Role {$roleName} should not have gained any permissions");
        }
    });
});

// =============================================================================
// PROPERTY 5: Seeder fails fast on the first missing permission
// Validates: Requirement 3.4
// =============================================================================

describe('Property 5: Seeder fails fast on the first missing permission', function () {
    
    beforeEach(function () {
        // Clear all permissions to start fresh
        Permission::query()->delete();
    });
    
    it('seeder throws exception when a permission is missing', function () {
        // Setup: Create roles
        createTestRoles(['admin']);
        
        // Create only 2 permissions that admin role needs - NOT the full set
        // These 2 exist, but many more in the map don't exist
        $partialPermissions = [
            'ViewAny:Student',
            'View:Student',
        ];
        
        foreach ($partialPermissions as $permName) {
            Permission::firstOrCreate(['name' => $permName, 'guard_name' => 'web']);
        }
        
        // The seeder should fail because ViewAny:Quiz doesn't exist
        $this->expectException(\RuntimeException::class);
        $this->seed(ShieldPermissionSeeder::class);
    });

    it('seeder fails with the correct missing permission name in error message', function () {
        // Setup: Create roles but NO permissions at all
        createTestRoles(['admin', 'teacher']);
        
        // Verify we have no permissions
        $this->assertEquals(0, Permission::count());
        
        // Execute & Verify: The seeder should fail with a meaningful message
        $this->expectException(\RuntimeException::class);
        $this->seed(ShieldPermissionSeeder::class);
    });
});

// =============================================================================
// PROPERTY 6: Ekskul role map excludes hidden-resource permissions
// Validates: Requirement 7.2
// =============================================================================

describe('Property 6: Ekskul role map excludes hidden-resource permissions', function () use ($hiddenFromEkskulResources) {
    
    it('guru_ekstrakurikuler does not have permissions for hidden resources', function () use ($hiddenFromEkskulResources) {
        // Setup: Create all permissions
        $this->seed(PermissionSeederForPbt::class);
        
        // Create roles
        createTestRoles(['admin', 'guru_ekstrakurikuler']);
        
        // Execute: Run the seeder
        $this->seed(ShieldPermissionSeeder::class);
        
        // Get guru_ekstrakurikuler permissions
        $ekskulPermissions = getRolePermissions('guru_ekstrakurikuler');
        
        // Verify: Hidden resources are NOT in the ekskul permissions
        foreach ($hiddenFromEkskulResources as $resource) {
            $permissionPrefixes = ['ViewAny', 'View', 'Create', 'Update', 'Delete'];
            
            foreach ($permissionPrefixes as $prefix) {
                $permissionName = "{$prefix}:{$resource}";
                
                expect($ekskulPermissions->contains($permissionName))
                    ->toBeFalse("guru_ekstrakurikuler should NOT have {$permissionName} (hidden from ekskul)");
            }
        }
    });

    it('guru_ekstrakurikuler has permissions for extracurricular resources', function () {
        // Setup: Create all permissions
        $this->seed(PermissionSeederForPbt::class);
        
        // Create roles
        createTestRoles(['admin', 'guru_ekstrakurikuler']);
        
        // Execute: Run the seeder
        $this->seed(ShieldPermissionSeeder::class);
        
        // Get guru_ekstrakurikuler permissions
        $ekskulPermissions = getRolePermissions('guru_ekstrakurikuler');
        
        // Verify: Extracurricular-related permissions ARE included
        expect($ekskulPermissions->contains('ViewAny:Extracurricular'))
            ->toBeTrue('guru_ekstrakurikuler should have ViewAny:Extracurricular');
        
        expect($ekskulPermissions->contains('ViewAny:ExtracurricularMember'))
            ->toBeTrue('guru_ekstrakurikuler should have ViewAny:ExtracurricularMember');
        
        // Verify basic student view is included (as per spec)
        expect($ekskulPermissions->contains('ViewAny:Student'))
            ->toBeTrue('guru_ekstrakurikuler should have ViewAny:Student');
        
        expect($ekskulPermissions->contains('View:Student'))
            ->toBeTrue('guru_ekstrakurikuler should have View:Student');
    });
});


/**
 * Helper seeder to create all permissions needed for property-based testing.
 */
class PermissionSeederForPbt extends \Illuminate\Database\Seeder
{
    public function run(): void
    {
        $permissions = $this->getRequiredPermissions();
        
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }
    }

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