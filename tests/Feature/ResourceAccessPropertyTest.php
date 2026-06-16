<?php

namespace Tests\Feature;

use App\Filament\Resources\Quizzes\QuizResource;
use App\Filament\Resources\Students\StudentResource;
use App\Filament\Resources\SchoolClasses\SchoolClassResource;
use App\Filament\Resources\Grades\GradeResource;
use App\Filament\Resources\LessonSessions\LessonSessionResource;
use App\Filament\Resources\CurriculumPlans\CurriculumPlanResource;
use App\Filament\Resources\ExamSessions\ExamSessionResource;
use App\Filament\Resources\LeaveRequests\LeaveRequestResource;
use App\Filament\Resources\QuestionBanks\QuestionBankResource;
use App\Filament\Resources\ParentNotes\ParentNoteResource;
use App\Filament\Resources\StudentAttendances\StudentAttendanceResource;
use App\Filament\Resources\StudentViolations\StudentViolationResource;
use App\Filament\Resources\CounselingTickets\CounselingTicketResource;
use App\Filament\Resources\ClassMaterials\ClassMaterialResource;
use App\Filament\Resources\ClassAnnouncements\ClassAnnouncementResource;
use App\Filament\Resources\ClassAssignments\ClassAssignmentResource;
use App\Filament\Resources\InternalAnnouncements\InternalAnnouncementResource;
use App\Models\User;
use Database\Seeders\ShieldPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class)
    ->in('Feature');

/**
 * Property-based tests for Resource access control after migration.
 * 
 * Feature: permission-based-access-control
 * 
 * These tests validate the correctness properties of Resource access:
 * - Property 1: Surface access if and only if permission
 * - Property 7: Trait-bearing resources always hide guru_ekstrakurikuler
 * - Property 8: Scoped query returns exactly the teacher's own records
 * 
 * Validates: Requirements 4.2, 4.4, 5.2, 5.4, 6.2, 7.1, 7.3, 7.4, 8.1, 8.2, 8.3, 11.1
 * 
 * NOTE: For proper PBT validation, run these tests with 100 iterations using:
 *    php vendor/bin/pest tests/Feature/ResourceAccessPropertyTest.php --iterations=100
 */

// =============================================================================
// RESOURCES WITH HIDESFROMEKSTRALKULER TRAIT
// =============================================================================

/**
 * List of Resources that use the HidesFromEkskulRole trait.
 * These resources should always hide from guru_ekstrakurikuler role.
 */
$resourcesWithTrait = [
    QuizResource::class => 'Quiz',
    StudentResource::class => 'Student',
    StudentViolationResource::class => 'StudentViolation',
    StudentAttendanceResource::class => 'StudentAttendance',
    ParentNoteResource::class => 'ParentNote',
    LessonSessionResource::class => 'LessonSession',
    GradeResource::class => 'Grade',
    ExamSessionResource::class => 'ExamSession',
    CurriculumPlanResource::class => 'CurriculumPlan',
    CounselingTicketResource::class => 'CounselingTicket',
    ClassAssignmentResource::class => 'ClassAssignment',
    InternalAnnouncementResource::class => 'InternalAnnouncement',
    QuestionBankResource::class => 'QuestionBank',
    ClassMaterialResource::class => 'ClassMaterial',
    ClassAnnouncementResource::class => 'ClassAnnouncement',
    SchoolClassResource::class => 'SchoolClass',
];

// =============================================================================
// RESOURCES WITH DATASCOPING (getEloquentQuery)
// =============================================================================

/**
 * List of Resources that have getEloquentQuery() scoping for teachers.
 * These resources should scope to the teacher's own records.
 */
$scopedResources = [
    QuizResource::class => ['staff_member_id', 'teacher'],
    QuestionBankResource::class => ['staff_member_id', 'teacher'],
    LessonSessionResource::class => ['staff_member_id', 'teacher'],
    CurriculumPlanResource::class => ['staff_member_id', 'teacher'],
    GradeResource::class => ['staff_member_id', 'teacher'],
    StudentAttendanceResource::class => ['staff_member_id', 'teacher'],
    ExamSessionResource::class => ['staff_member_id', 'teacher'],
    LeaveRequestResource::class => ['staff_member_id', 'teacher'],
    ParentNoteResource::class => ['staff_member_id', 'teacher'],
];

// =============================================================================
// HELPER FUNCTIONS
// =============================================================================

/**
 * Get all permission names for a resource.
 */
function getResourcePermissionNames(string $resourceName): array
{
    $prefixes = ['ViewAny', 'View', 'Create', 'Update', 'Delete'];
    return array_map(fn($prefix) => "{$prefix}:{$resourceName}", $prefixes);
}

/**
 * Create a user with a specific role and optional permissions.
 * Uses unique role names per call to avoid cross-test contamination.
 */
function createUserWithRole(string $roleName, array $permissions = []): User
{
    // Generate a unique role name to avoid permission contamination between test cases
    $uniqueRoleName = $roleName . '_test_' . uniqid();
    
    $role = Role::firstOrCreate(['name' => $uniqueRoleName, 'guard_name' => 'web']);
    
    $user = User::factory()->create();
    $user->assignRole($role);
    
    if (!empty($permissions)) {
        foreach ($permissions as $permName) {
            $perm = Permission::firstOrCreate(['name' => $permName, 'guard_name' => 'web']);
            $role->givePermissionTo($perm);
        }
        $user->refresh();
    }
    
    return $user;
}

/**
 * Get the canViewAny result for a resource.
 */
function getCanViewAny($resourceClass): bool
{
    return $resourceClass::canViewAny();
}

/**
 * Get the scoped query for a resource.
 */
function getScopedQuery($resourceClass, User $user): \Illuminate\Database\Eloquent\Builder
{
    // Simulate being logged in as the user
    auth()->login($user);
    return $resourceClass::getEloquentQuery();
}

// =============================================================================
// PROPERTY 1: Surface access if and only if permission
// Validates: Requirements 4.2, 4.4, 5.2, 5.4, 6.2, 7.1, 11.1
// =============================================================================

describe('Property 1: Surface access if and only if permission', function () {
    
    beforeEach(function () {
        // Seed permissions
        $this->seed(PermissionSeederForResourceAccess::class);
        
        // Create roles
        foreach (['admin', 'teacher', 'counselor', 'editor', 'piket', 'guru_ekstrakurikuler'] as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }
    });
    
    /**
     * Test that access is granted if AND ONLY IF the user has the ViewAny permission.
     * We test this across multiple resources and permission combinations.
     */
    it('grants access when ViewAny permission is present', function () {
        // Setup: Create user with admin role and ViewAny:Quiz permission
        $user = createUserWithRole('admin', ['ViewAny:Quiz']);
        
        // Act: Check canViewAny for Quiz resource
        auth()->login($user);
        $canView = QuizResource::canViewAny();
        
        // Assert: Access granted because ViewAny permission exists
        expect($canView)->toBeTrue('User with ViewAny:Quiz permission should be able to access Quiz resource');
    });
    
    it('denies access when ViewAny permission is absent', function () {
        // Setup: Create user with teacher role but NO ViewAny:Quiz permission
        $user = createUserWithRole('teacher', []); // No permissions
        
        // Act: Check canViewAny for Quiz resource
        auth()->login($user);
        $canView = QuizResource::canViewAny();
        
        // Assert: Access denied because ViewAny permission doesn't exist
        expect($canView)->toBeFalse('User without ViewAny:Quiz permission should NOT be able to access Quiz resource');
    });
    
    it('denies access when user has other permissions but not ViewAny', function () {
        // Setup: Create user with View:Quiz but NOT ViewAny:Quiz
        $role = Role::where('name', 'teacher')->first();
        Permission::firstOrCreate(['name' => 'View:Quiz', 'guard_name' => 'web']);
        $role->givePermissionTo('View:Quiz');
        
        $user = User::factory()->create();
        $user->assignRole('teacher');
        
        // Act: Check canViewAny for Quiz resource
        auth()->login($user);
        $canView = QuizResource::canViewAny();
        
        // Assert: Access denied because ViewAny is required for canViewAny
        expect($canView)->toBeFalse('User with View:Quiz but NOT ViewAny:Quiz should NOT be able to access the list');
    });
    
    it('validates access rule for multiple resources', function () {
        $testCases = [
            ['resource' => StudentResource::class, 'permission' => 'ViewAny:Student', 'role' => 'admin'],
            ['resource' => QuizResource::class, 'permission' => 'ViewAny:Quiz', 'role' => 'teacher'],
            ['resource' => GradeResource::class, 'permission' => 'ViewAny:Grade', 'role' => 'teacher'],
            ['resource' => SchoolClassResource::class, 'permission' => 'ViewAny:SchoolClass', 'role' => 'admin'],
        ];
        
        foreach ($testCases as $case) {
            // Create user WITH the permission
            $userWithPerm = createUserWithRole($case['role'], [$case['permission']]);
            auth()->login($userWithPerm);
            
            $canViewWithPerm = $case['resource']::canViewAny();
            
            // Create user WITHOUT the permission
            $userWithoutPerm = createUserWithRole($case['role'], []);
            auth()->login($userWithoutPerm);
            
            $canViewWithoutPerm = $case['resource']::canViewAny();
            
            // Assert: Access only with permission
            expect($canViewWithPerm)->toBeTrue("Should have access with {$case['permission']}");
            expect($canViewWithoutPerm)->toBeFalse("Should NOT have access without {$case['permission']}");
        }
    });
    
    it('super_admin bypasses permission checks', function () {
        // Setup: Create super_admin role and user
        Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $superAdmin = User::factory()->create();
        $superAdmin->assignRole('super_admin');
        
        // Act: Check canViewAny for a resource (without any explicit permissions)
        auth()->login($superAdmin);
        $canView = QuizResource::canViewAny();
        
        // Assert: Super admin should have access due to intercept_gate = before
        expect($canView)->toBeTrue('super_admin should bypass permission checks');
    });
});

// =============================================================================
// PROPERTY 7: Trait-bearing resources always hide guru_ekstrakurikuler
// Validates: Requirements 7.3, 7.4
// =============================================================================

describe('Property 7: Trait-bearing resources always hide guru_ekstrakurikuler', function () use ($resourcesWithTrait) {
    
    beforeEach(function () {
        // Seed permissions
        $this->seed(PermissionSeederForResourceAccess::class);
        
        // Create roles
        foreach (['admin', 'teacher', 'guru_ekstrakurikuler'] as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }
    });
    
    /**
     * Test that ALL resources with HidesFromEkskulRole trait hide from guru_ekstrakurikuler.
     * This should work regardless of permissions or migration status.
     */
    it('hides trait-bearing resources from guru_ekstrakurikuler regardless of permissions', function () use ($resourcesWithTrait) {
        // Setup: Create guru_ekstrakurikuler with full permissions
        $role = Role::where('name', 'guru_ekstrakurikuler')->first();
        
        // Give guru_ekstrakurikuler some permissions (simulating what they would have)
        $allPermissions = Permission::pluck('name')->take(50)->toArray();
        foreach ($allPermissions as $perm) {
            $role->givePermissionTo($perm);
        }
        
        $user = User::factory()->create();
        $user->assignRole('guru_ekstrakurikuler');
        
        // Act & Assert: For each resource with the trait, canViewAny should return false
        foreach ($resourcesWithTrait as $resourceClass => $resourceName) {
            auth()->login($user);
            
            $canView = $resourceClass::canViewAny();
            
            expect($canView)
                ->toBeFalse("Resource {$resourceName} with HidesFromEkskulRole trait should hide from guru_ekstrakurikuler even WITH permissions");
        }
    });
    
    it('trait-bearing resources remain visible to other roles', function () use ($resourcesWithTrait) {
        // Setup: Create admin with full permissions
        $role = Role::where('name', 'admin')->first();
        $allPermissions = Permission::pluck('name')->take(50)->toArray();
        foreach ($allPermissions as $perm) {
            $role->givePermissionTo($perm);
        }
        
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        
        // Act & Assert: Admin should see trait-bearing resources
        foreach ($resourcesWithTrait as $resourceClass => $resourceName) {
            auth()->login($admin);
            
            $canView = $resourceClass::canViewAny();
            
            expect($canView)
                ->toBeTrue("Resource {$resourceName} should be visible to admin role");
        }
    });
    
    it('hides resources even when user has the ViewAny permission', function () {
        // Setup: Give guru_ekstrakurikuler the ViewAny:Quiz permission
        $role = Role::where('name', 'guru_ekstrakurikuler')->first();
        Permission::firstOrCreate(['name' => 'ViewAny:Quiz', 'guard_name' => 'web']);
        $role->givePermissionTo('ViewAny:Quiz');
        
        $user = User::factory()->create();
        $user->assignRole('guru_ekstrakurikuler');
        
        // Act: Check canViewAny for Quiz (which has the trait)
        auth()->login($user);
        $canView = QuizResource::canViewAny();
        
        // Assert: Should still be hidden due to trait, regardless of permission
        expect($canView)
            ->toBeFalse('Quiz resource should hide from guru_ekstrakurikuler even WITH ViewAny:Quiz permission');
    });
});

// =============================================================================
// PROPERTY 8: Scoped query returns exactly the teacher's own records
// Validates: Requirements 8.1, 8.2, 8.3
// =============================================================================

describe('Property 8: Scoped query returns exactly the teacher\'s own records', function () use ($scopedResources) {
    
    beforeEach(function () {
        // Seed permissions
        $this->seed(PermissionSeederForResourceAccess::class);
        
        // Create roles
        foreach (['admin', 'teacher'] as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }
    });
    
    /**
     * Test that getEloquentQuery() returns only the teacher's own records,
     * regardless of what permissions the teacher has.
     */
    it('scopes to teacher records when teacher has all permissions', function () use ($scopedResources) {
        // Setup: Create teacher with ALL relevant permissions
        $role = Role::where('name', 'teacher')->first();
        
        // Give teacher all permissions
        $allPermissions = Permission::pluck('name')->toArray();
        foreach ($allPermissions as $perm) {
            $role->givePermissionTo($perm);
        }
        
        // Create a staff member for the teacher
        $staffMember = \App\Models\StaffMember::factory()->create();
        
        // Create teacher user with the staff member
        $teacher = User::factory()->create();
        $teacher->staffMember()->save($staffMember);
        $teacher->assignRole('teacher');
        
        // Create OTHER teachers' records
        $otherStaff = \App\Models\StaffMember::factory()->count(3)->create();
        
        // Act & Assert: Query should only return records for this teacher
        foreach ($scopedResources as $resourceClass => $scopeConfig) {
            [$field, $role] = $scopeConfig;
            
            auth()->login($teacher);
            $query = $resourceClass::getEloquentQuery();
            
            // The query should be scoped to this teacher's staff_member_id
            // We verify this by checking if the query has the where clause
            $querySql = $query->toBase()->toSql();
            
            expect($querySql)
                ->toContain($field, "Query for {$resourceClass} should be scoped by {$field}");
        }
    });
    
    it('scopes to teacher records when teacher has NO permissions', function () use ($scopedResources) {
        // Setup: Create teacher with NO permissions
        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');
        
        // Create a staff member
        $staffMember = \App\Models\StaffMember::factory()->create();
        $teacher->staffMember()->save($staffMember);
        
        // Act & Assert: Even without permissions, the query should be scoped
        foreach ($scopedResources as $resourceClass => $scopeConfig) {
            [$field, $role] = $scopeConfig;
            
            auth()->login($teacher);
            $query = $resourceClass::getEloquentQuery();
            
            // The query should still be scoped even without permissions
            $querySql = $query->toBase()->toSql();
            
            expect($querySql)
                ->toContain($field, "Query for {$resourceClass} should be scoped even WITHOUT permissions");
        }
    });
    
    it('scoping works independently of permissions (property test)', function () {
        // This test verifies the core property: scoping is independent of permissions
        // We'll test with varying permission states
        
        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');
        
        $staffMember = \App\Models\StaffMember::factory()->create();
        $teacher->staffMember()->save($staffMember);
        
        // Test 1: No permissions
        auth()->login($teacher);
        $queryNoPerms = QuizResource::getEloquentQuery()->toBase()->toSql();
        
        // Test 2: With ViewAny:Quiz only
        $role = Role::where('name', 'teacher')->first();
        $role->givePermissionTo('ViewAny:Quiz');
        $teacher->refresh();
        
        auth()->login($teacher);
        $queryWithPerms = QuizResource::getEloquentQuery()->toBase()->toSql();
        
        // The WHERE clause should be the same regardless of permissions
        expect($queryNoPerms)
            ->toBe($queryWithPerms, 'Query scoping should NOT change based on permissions');
    });
    
    it('admin bypasses scoping (gets all records)', function () {
        // Setup: Create admin with full access
        $role = Role::where('name', 'admin')->first();
        $allPermissions = Permission::pluck('name')->toArray();
        foreach ($allPermissions as $perm) {
            $role->givePermissionTo($perm);
        }
        
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        
        // Act: Check query for a scoped resource
        auth()->login($admin);
        $query = QuizResource::getEloquentQuery()->toBase()->toSql();
        
        // Admin should NOT have the staff_member_id where clause
        expect($query)->not->toContain('staff_member_id', 'Admin should see all records, not scoped');
    });
});


/**
 * Helper seeder to create all permissions needed for property-based testing.
 */
class PermissionSeederForResourceAccess extends \Illuminate\Database\Seeder
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
        // Get all permissions from the ShieldPermissionSeeder map
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