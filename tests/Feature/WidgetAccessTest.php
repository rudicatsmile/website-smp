<?php

namespace Tests\Feature;

use App\Filament\Widgets\LessonProgressWidget;
use App\Models\User;
use Database\Seeders\ShieldPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * HTTP/Integration tests for migrated Widget access control.
 * 
 * These tests validate the widget visibility behavior:
 * - Without View:{Widget} permission → widget content not rendered (can be placeholder/disabled)
 * - With View:{Widget} permission → widget content appears
 * 
 * Validates: Requirements 6.1, 6.2, 6.3
 * 
 * Tests the following scenarios:
 * 1. User WITHOUT View:{Widget} permission - canView() returns false
 * 2. User WITH View:{Widget} permission - canView() returns true
 * 3. Widget uses HasWidgetShield trait for permission-based visibility
 */
class WidgetAccessTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Set up the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed permissions needed for testing
        $this->seed(WidgetPermissionSeederForTest::class);
        
        // Create all roles
        $this->createRoles();
    }

    /**
     * Create test roles.
     */
    protected function createRoles(): void
    {
        foreach (['super_admin', 'admin', 'teacher', 'counselor', 'editor', 'piket', 'guru_ekstrakurikuler'] as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }
    }

    /**
     * Test: Widget uses HasWidgetShield trait for permission-based visibility.
     * 
     * Validates: Requirements 6.1, 6.2
     * "WHEN migrasi sebuah Widget dilakukan, THE Admin_Panel SHALL tidak lagi 
     * memuat Manual_Access_Method berbasis hasAnyRole() pada Widget tersebut."
     */
    public function test_widget_uses_has_widget_shield_trait(): void
    {
        // Assert the widget uses the HasWidgetShield trait
        $this->assertContains(
            \BezhanSalleh\FilamentShield\Traits\HasWidgetShield::class,
            class_uses(LessonProgressWidget::class),
            'LessonProgressWidget should use HasWidgetShield trait'
        );
    }

    /**
     * Test: User WITHOUT View:LessonProgressWidget permission - canView() returns false.
     * 
     * Validates: Requirement 6.3
     * "IF seorang pengguna tidak memiliki Permission view untuk sebuah Widget yang 
     * telah dimigrasi, THEN THE Admin_Panel SHALL tidak merender konten Widget tersebut"
     */
    public function test_user_without_widget_permission_cannot_view(): void
    {
        // Create counselor role WITHOUT View:LessonProgressWidget permission
        $counselorRole = Role::where('name', 'counselor')->first();
        
        // Give counselor some other permissions but NOT View:LessonProgressWidget
        $otherPermissions = [
            'ViewAny:CounselingTicket',
            'View:CounselingTicket',
        ];
        
        foreach ($otherPermissions as $permName) {
            $perm = Permission::firstOrCreate(['name' => $permName, 'guard_name' => 'web']);
            $counselorRole->givePermissionTo($perm);
        }
        
        // Create counselor user
        $counselor = User::factory()->create();
        $counselor->assignRole('counselor');
        
        // Act: Check if widget is viewable
        $this->actingAs($counselor, 'web');
        
        // Assert: Widget should NOT be visible (canView returns false)
        $this->assertFalse(
            LessonProgressWidget::canView(),
            'LessonProgressWidget::canView should return false for user without View:LessonProgressWidget permission'
        );
    }

    /**
     * Test: User WITH View:LessonProgressWidget permission - canView() returns true.
     * 
     * Validates: Requirement 6.3
     * "WHEN sebuah Widget telah dimigrasi, THE Admin_Panel SHALL menentukan 
     * visibilitas Widget tersebut berdasarkan Permission view Widget yang 
     * di-assign ke Role pengguna."
     */
    public function test_user_with_widget_permission_can_view(): void
    {
        // Create admin role WITH View:LessonProgressWidget permission
        $adminRole = Role::where('name', 'admin')->first();
        $widgetView = Permission::firstOrCreate(['name' => 'View:LessonProgressWidget', 'guard_name' => 'web']);
        $adminRole->givePermissionTo($widgetView);
        
        // Create admin user
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        
        // Act: Check if widget is viewable
        $this->actingAs($admin, 'web');
        
        // Assert: Widget should be visible (canView returns true)
        $this->assertTrue(
            LessonProgressWidget::canView(),
            'LessonProgressWidget::canView should return true for user with View:LessonProgressWidget permission'
        );
    }

    /**
     * Test: Teacher WITH View:LessonProgressWidget permission can view widget.
     * 
     * This verifies that the teacher role (which previously had access via hasAnyRole)
     * still has access via the new permission-based system.
     * 
     * Validates: Requirements 6.2, 6.3
     */
    public function test_teacher_with_permission_can_view_widget(): void
    {
        // Create teacher role WITH View:LessonProgressWidget permission
        $teacherRole = Role::where('name', 'teacher')->first();
        $widgetView = Permission::firstOrCreate(['name' => 'View:LessonProgressWidget', 'guard_name' => 'web']);
        $teacherRole->givePermissionTo($widgetView);
        
        // Create teacher user
        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');
        
        // Act: Check if widget is viewable
        $this->actingAs($teacher, 'web');
        
        // Assert: Widget should be visible
        $this->assertTrue(
            LessonProgressWidget::canView(),
            'Teacher with View:LessonProgressWidget should be able to view widget'
        );
    }

    /**
     * Test: Teacher WITHOUT permission cannot view widget.
     * 
     * This verifies the negative case - teacher without the specific permission
     * should NOT be able to view the widget.
     * 
     * Validates: Requirements 6.2, 6.3
     */
    public function test_teacher_without_permission_cannot_view_widget(): void
    {
        // Create teacher role WITHOUT View:LessonProgressWidget permission
        $teacherRole = Role::where('name', 'teacher')->first();
        
        // Give some other permissions but NOT View:LessonProgressWidget
        $otherPermissions = [
            'ViewAny:Quiz',
            'View:Quiz',
            'View:TeachingToday',
        ];
        
        foreach ($otherPermissions as $permName) {
            $perm = Permission::firstOrCreate(['name' => $permName, 'guard_name' => 'web']);
            $teacherRole->givePermissionTo($perm);
        }
        
        // Create teacher user
        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');
        
        // Act: Check if widget is viewable
        $this->actingAs($teacher, 'web');
        
        // Assert: Widget should NOT be visible
        $this->assertFalse(
            LessonProgressWidget::canView(),
            'Teacher without View:LessonProgressWidget should NOT be able to view widget'
        );
    }

    /**
     * Test: Widget visibility is controlled by permission, not hardcoded role check.
     * 
     * This test verifies that the old hasAnyRole(['super_admin', 'admin', 'teacher'])
     * logic has been replaced with permission-based access control.
     * 
     * Validates: Requirements 6.1, 6.2
     */
    public function test_widget_visibility_is_permission_based_not_role_based(): void
    {
        // Create a user with 'teacher' role but WITHOUT View:LessonProgressWidget permission
        $teacherRole = Role::where('name', 'teacher')->first();
        // Clear any existing permissions for teacher
        $teacherRole->syncPermissions([]);
        
        // Create teacher user
        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');
        
        // Act: Check if widget is viewable
        $this->actingAs($teacher, 'web');
        
        // Assert: Widget should NOT be visible even though user has 'teacher' role
        // This proves the old hasAnyRole() check is no longer in use
        $this->assertFalse(
            LessonProgressWidget::canView(),
            'Widget should not be visible to teacher role without explicit permission, proving hasAnyRole() is removed'
        );
        
        // Now give the permission
        $widgetView = Permission::firstOrCreate(['name' => 'View:LessonProgressWidget', 'guard_name' => 'web']);
        $teacherRole->givePermissionTo($widgetView);
        
        // Clear permission cache for this user
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        // Assert: Widget should now be visible
        $this->assertTrue(
            LessonProgressWidget::canView(),
            'Widget should be visible to teacher role with explicit permission'
        );
    }

    /**
     * Test: Multiple users with different roles - access based on permission only.
     * 
     * Validates: Requirement 6.2
     */
    public function test_access_granted_only_with_permission_across_roles(): void
    {
        // Setup: Give admin the permission
        $adminRole = Role::where('name', 'admin')->first();
        $widgetView = Permission::firstOrCreate(['name' => 'View:LessonProgressWidget', 'guard_name' => 'web']);
        $adminRole->givePermissionTo($widgetView);
        
        // Setup: piket role WITHOUT the permission
        $piketRole = Role::where('name', 'piket')->first();
        $piketRole->syncPermissions([]); // No widget permission
        
        // Test admin WITH permission
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $this->actingAs($admin, 'web');
        $this->assertTrue(
            LessonProgressWidget::canView(),
            'Admin with View:LessonProgressWidget should see widget'
        );
        
        // Test piket WITHOUT permission
        $piket = User::factory()->create();
        $piket->assignRole('piket');
        $this->actingAs($piket, 'web');
        $this->assertFalse(
            LessonProgressWidget::canView(),
            'Piket without View:LessonProgressWidget should NOT see widget'
        );
    }
}

/**
 * Helper seeder to create Widget permissions needed for testing.
 */
class WidgetPermissionSeederForTest extends \Illuminate\Database\Seeder
{
    public function run(): void
    {
        // Create the View:LessonProgressWidget permission
        Permission::firstOrCreate(['name' => 'View:LessonProgressWidget', 'guard_name' => 'web']);
        
        // Also create some other permissions used in tests
        $permissions = [
            'ViewAny:CounselingTicket',
            'View:CounselingTicket',
            'ViewAny:Quiz',
            'View:Quiz',
            'View:TeachingToday',
        ];
        
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }
    }
}
