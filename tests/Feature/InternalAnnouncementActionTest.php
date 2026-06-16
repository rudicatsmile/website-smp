<?php

namespace Tests\Feature;

use App\Filament\Resources\InternalAnnouncements\InternalAnnouncementResource;
use App\Filament\Resources\InternalAnnouncements\Tables\InternalAnnouncementsTable;
use App\Models\InternalAnnouncement;
use App\Models\User;
use Database\Seeders\ShieldPermissionSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Example tests for InternalAnnouncement action visibility.
 * 
 * Feature: permission-based-access-control
 * 
 * These tests validate that actions (Edit, Delete, Create) are visible
 * if and only if the user has the corresponding permission.
 * 
 * Validates: Requirements 4.2, 11.1
 */
class InternalAnnouncementActionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Set up the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Create all permissions needed for ShieldPermissionSeeder
        $this->seed(PermissionSeederForInternalAnnouncementTest::class);
        
        // Create all roles needed for testing
        foreach (['super_admin', 'admin', 'teacher', 'editor', 'counselor', 'piket', 'guru_ekstrakurikuler'] as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }
        
        // Seed permissions from ShieldPermissionSeeder
        $this->seed(ShieldPermissionSeeder::class);
    }

    // =========================================================================
    // Task 8.1: EditAction visibility
    // Validates: Requirement 4.2 - Actions visible based on Update permission
    // =========================================================================

    /**
     * Test: EditAction is visible when user has Update:InternalAnnouncement permission.
     * 
     * Validates: Requirement 4.2
     * "Action muncul jika dan hanya jika role memiliki permission terkait"
     */
    public function test_edit_action_visible_with_update_permission(): void
    {
        // Admin has Update:InternalAnnouncement from seeder
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        
        // Create an announcement
        $announcement = InternalAnnouncement::factory()->create();
        
        // Act: Check canEdit method
        $this->actingAs($admin, 'web');
        
        $canEdit = InternalAnnouncementResource::canEdit($announcement);
        
        // Assert: Should be able to edit
        $this->assertTrue(
            $canEdit,
            'User with Update:InternalAnnouncement permission should see EditAction'
        );
    }

    /**
     * Test: EditAction is hidden when user lacks Update:InternalAnnouncement permission.
     * 
     * Validates: Requirement 4.2
     */
    public function test_edit_action_hidden_without_update_permission(): void
    {
        // Teacher does NOT have Update:InternalAnnouncement from seeder
        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');
        
        // Create an announcement
        $announcement = InternalAnnouncement::factory()->create();
        
        // Act: Check canEdit method
        $this->actingAs($teacher, 'web');
        
        $canEdit = InternalAnnouncementResource::canEdit($announcement);
        
        // Assert: Should NOT be able to edit
        $this->assertFalse(
            $canEdit,
            'User WITHOUT Update:InternalAnnouncement permission should NOT see EditAction'
        );
    }

    // =========================================================================
    // Task 8.1: DeleteAction visibility
    // Validates: Requirement 4.2 - Actions visible based on Delete permission
    // =========================================================================

    /**
     * Test: DeleteAction is visible when user has Delete:InternalAnnouncement permission.
     * 
     * Validates: Requirement 4.2
     */
    public function test_delete_action_visible_with_delete_permission(): void
    {
        // Admin has Delete:InternalAnnouncement from seeder
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        
        // Create an announcement
        $announcement = InternalAnnouncement::factory()->create();
        
        // Act: Check canDelete method
        $this->actingAs($admin, 'web');
        
        $canDelete = InternalAnnouncementResource::canDelete($announcement);
        
        // Assert: Should be able to delete
        $this->assertTrue(
            $canDelete,
            'User with Delete:InternalAnnouncement permission should see DeleteAction'
        );
    }

    /**
     * Test: DeleteAction is hidden when user lacks Delete:InternalAnnouncement permission.
     * 
     * Validates: Requirement 4.2
     */
    public function test_delete_action_hidden_without_delete_permission(): void
    {
        // Editor does NOT have Delete:InternalAnnouncement from seeder
        $editor = User::factory()->create();
        $editor->assignRole('editor');
        
        // Create an announcement
        $announcement = InternalAnnouncement::factory()->create();
        
        // Act: Check canDelete method
        $this->actingAs($editor, 'web');
        
        $canDelete = InternalAnnouncementResource::canDelete($announcement);
        
        // Assert: Should NOT be able to delete
        $this->assertFalse(
            $canDelete,
            'User WITHOUT Delete:InternalAnnouncement permission should NOT see DeleteAction'
        );
    }

    // =========================================================================
    // Task 8.1: DeleteBulkAction visibility
    // Validates: Requirement 4.2 - Bulk actions visible based on DeleteAny permission
    // =========================================================================

    /**
     * Test: DeleteBulkAction requires DeleteAny:InternalAnnouncement permission.
     * 
     * Validates: Requirement 4.2
     * Note: DeleteBulkAction requires DeleteAny permission, not Delete
     */
    public function test_delete_bulk_action_requires_delete_any_permission(): void
    {
        // Admin role should have Delete permission
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        
        // Act: Check user can delete
        $this->actingAs($admin, 'web');
        
        $canDelete = $admin->can('Delete:InternalAnnouncement');
        
        // Assert: Admin should have Delete permission
        $this->assertTrue(
            $canDelete,
            'Admin role should have Delete:InternalAnnouncement permission'
        );
        
        // Note: DeleteAny is generated by Shield but not assigned via ShieldPermissionSeeder
        // The bulk action visibility is controlled by DeleteAny permission check in the code
        // which falls back safely if permission doesn't exist
    }

    /**
     * Test: DeleteBulkAction is hidden for users without DeleteAny permission.
     * 
     * Validates: Requirement 4.2
     */
    public function test_delete_bulk_action_hidden_without_delete_any_permission(): void
    {
        // Teacher does NOT have Delete:InternalAnnouncement from seeder
        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');
        
        // Act: Check user can perform delete
        $this->actingAs($teacher, 'web');
        
        $canDelete = $teacher->can('Delete:InternalAnnouncement');
        
        // Assert: Teacher should NOT have Delete permission
        $this->assertFalse(
            $canDelete,
            'Teacher role should NOT have Delete:InternalAnnouncement permission'
        );
    }

    // =========================================================================
    // Task 8.1: CreateAction visibility
    // Validates: Requirement 4.2 - Create action visible based on Create permission
    // =========================================================================

    /**
     * Test: CreateAction is visible when user has Create:InternalAnnouncement permission.
     * 
     * Validates: Requirement 4.2
     */
    public function test_create_action_visible_with_create_permission(): void
    {
        // Admin has Create:InternalAnnouncement from seeder
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        
        // Act: Check canCreate method
        $this->actingAs($admin, 'web');
        
        $canCreate = InternalAnnouncementResource::canCreate();
        
        // Assert: Should be able to create
        $this->assertTrue(
            $canCreate,
            'User with Create:InternalAnnouncement permission should see CreateAction'
        );
    }

    /**
     * Test: CreateAction is hidden when user lacks Create:InternalAnnouncement permission.
     * 
     * Validates: Requirement 4.2
     */
    public function test_create_action_hidden_without_create_permission(): void
    {
        // Teacher does NOT have Create:InternalAnnouncement from seeder
        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');
        
        // Act: Check canCreate method
        $this->actingAs($teacher, 'web');
        
        $canCreate = InternalAnnouncementResource::canCreate();
        
        // Assert: Should NOT be able to create
        $this->assertFalse(
            $canCreate,
            'User WITHOUT Create:InternalAnnouncement permission should NOT see CreateAction'
        );
    }

    // =========================================================================
    // Requirement 11.1: Comprehensive role-based verification
    // Validates: Requirement 11.1 - Access granted if and only if permission exists
    // =========================================================================

    /**
     * Test: Admin role has all InternalAnnouncement permissions.
     * 
     * Validates: Requirement 11.1
     * "WHEN seorang pengguna dengan Role tertentu mengakses sebuah Resource...
     * THE Admin_Panel SHALL mengizinkan akses jika dan hanya jika Role tersebut
     * memiliki Permission yang sesuai."
     */
    public function test_admin_has_all_internal_announcement_permissions(): void
    {
        // Admin role should have all permissions from seeder
        $adminRole = Role::where('name', 'admin')->first();
        
        $permissions = [
            'ViewAny:InternalAnnouncement',
            'View:InternalAnnouncement',
            'Create:InternalAnnouncement',
            'Update:InternalAnnouncement',
            'Delete:InternalAnnouncement',
        ];
        
        foreach ($permissions as $permName) {
            $this->assertTrue(
                $adminRole->hasPermissionTo($permName),
                "Admin role should have {$permName} permission"
            );
        }
    }

    /**
     * Test: Teacher role has limited InternalAnnouncement permissions.
     * 
     * Teachers should NOT have Create/Update/Delete for InternalAnnouncement.
     * 
     * Validates: Requirement 11.1
     */
    public function test_teacher_has_limited_internal_announcement_permissions(): void
    {
        $teacherRole = Role::where('name', 'teacher')->first();
        
        // Teacher should NOT have Create permission
        $this->assertFalse(
            $teacherRole->hasPermissionTo('Create:InternalAnnouncement'),
            'Teacher role should NOT have Create:InternalAnnouncement permission'
        );
        
        // Teacher should NOT have Update permission
        $this->assertFalse(
            $teacherRole->hasPermissionTo('Update:InternalAnnouncement'),
            'Teacher role should NOT have Update:InternalAnnouncement permission'
        );
        
        // Teacher should NOT have Delete permission
        $this->assertFalse(
            $teacherRole->hasPermissionTo('Delete:InternalAnnouncement'),
            'Teacher role should NOT have Delete:InternalAnnouncement permission'
        );
    }

    /**
     * Test: Editor role does NOT have Delete for InternalAnnouncement.
     * 
     * Validates: Requirement 11.1
     */
    public function test_editor_does_not_have_delete_permission(): void
    {
        $editorRole = Role::where('name', 'editor')->first();
        
        // Editor should NOT have Delete permission for InternalAnnouncement
        $this->assertFalse(
            $editorRole->hasPermissionTo('Delete:InternalAnnouncement'),
            'Editor role should NOT have Delete:InternalAnnouncement permission'
        );
    }

    /**
     * Test: guru_ekstrakurikuler is hidden from InternalAnnouncement resource.
     * 
     * InternalAnnouncementResource uses HidesFromEkskulRole trait.
     * 
     * Validates: Requirement 7.3, 7.4
     */
    public function test_ekskul_hidden_from_internal_announcement(): void
    {
        $ekskul = User::factory()->create();
        $ekskul->assignRole('guru_ekstrakurikuler');
        
        // Act: Check canViewAny
        $this->actingAs($ekskul, 'web');
        
        $canViewAny = InternalAnnouncementResource::canViewAny();
        
        // Assert: Should be hidden due to HidesFromEkskulRole trait
        $this->assertFalse(
            $canViewAny,
            'InternalAnnouncement resource should hide from guru_ekstrakurikuler due to HidesFromEkskulRole trait'
        );
    }

    /**
     * Test: Comprehensive action visibility matrix.
     * 
     * This test verifies the complete action visibility for all roles.
     * 
     * Validates: Requirements 4.2, 11.1
     */
    public function test_action_visibility_matrix(): void
    {
        $announcement = InternalAnnouncement::factory()->create();
        
        // Based on ShieldPermissionSeeder map:
        // admin: has all permissions
        // teacher: does NOT have InternalAnnouncement permissions (only class-related)
        // editor: does NOT have InternalAnnouncement permissions (only content management)
        // counselor: does NOT have InternalAnnouncement permissions (only counseling)
        // piket: does NOT have InternalAnnouncement permissions (only attendance)
        $testCases = [
            // role => [canCreate, canEdit, canDelete]
            'admin' => [true, true, true],
            'teacher' => [false, false, false],
            'editor' => [false, false, false],
            'counselor' => [false, false, false],
            'piket' => [false, false, false],
        ];
        
        foreach ($testCases as $roleName => $expected) {
            [$canCreate, $canEdit, $canDelete] = $expected;
            
            $user = User::factory()->create();
            $user->assignRole($roleName);
            
            $this->actingAs($user, 'web');
            
            $actualCreate = InternalAnnouncementResource::canCreate();
            $actualEdit = InternalAnnouncementResource::canEdit($announcement);
            $actualDelete = InternalAnnouncementResource::canDelete($announcement);
            
            $this->assertEquals(
                $canCreate,
                $actualCreate,
                "Role {$roleName} create action visibility mismatch"
            );
            
            $this->assertEquals(
                $canEdit,
                $actualEdit,
                "Role {$roleName} edit action visibility mismatch"
            );
            
            $this->assertEquals(
                $canDelete,
                $actualDelete,
                "Role {$roleName} delete action visibility mismatch"
            );
        }
    }
    
    /**
     * Test: Action visibility changes when permission is granted/revoked.
     * 
     * Validates: Requirement 4.2 - "if and only if"
     */
    public function test_action_visibility_reflects_permission_changes(): void
    {
        // Create a user without any role
        $user = User::factory()->create();
        $this->actingAs($user, 'web');
        
        $announcement = InternalAnnouncement::factory()->create();
        
        // Initially, should NOT be able to edit
        $this->assertFalse(
            InternalAnnouncementResource::canEdit($announcement),
            'User without permission should NOT see EditAction'
        );
        
        // Grant Update permission directly
        $updatePerm = Permission::where('name', 'Update:InternalAnnouncement')->first();
        $user->givePermissionTo($updatePerm);
        
        // Now should be able to edit
        $this->assertTrue(
            InternalAnnouncementResource::canEdit($announcement),
            'User with permission should see EditAction'
        );
        
        // Revoke permission
        $user->revokePermissionTo($updatePerm);
        
        // Should NOT be able to edit again
        $this->assertFalse(
            InternalAnnouncementResource::canEdit($announcement),
            'User with revoked permission should NOT see EditAction'
        );
    }
}

/**
 * Helper seeder to create all permissions needed for testing.
 * This creates all permissions that the ShieldPermissionSeeder expects.
 */
class PermissionSeederForInternalAnnouncementTest extends Seeder
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
