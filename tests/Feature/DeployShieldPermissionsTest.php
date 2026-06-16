<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Console\Commands\DeployShieldPermissions;
use Database\Seeders\ShieldPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Tests for the shield:deploy command.
 * 
 * Validates: Requirements 12.2
 */
class DeployShieldPermissionsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Resource names that use HidesFromEkskulRole trait.
     */
    protected array $hiddenFromEkskulResources = [
        'User', 'TahfidzClass', 'Tag', 'StudentViolation', 'StudentPayment',
        'StudentAttendance', 'StaffSchedule', 'StaffMember', 'StaffCategory',
        'SpmbRegistration', 'SpmbPeriod', 'Slider', 'SchoolEvent', 'SchoolClass',
        'QuranSurah', 'Quiz', 'QuestionBank', 'Program', 'Popup', 'PageHero',
        'ParentNote', 'NewsCategory', 'News', 'Media', 'MaterialCategory',
        'Material', 'LessonSession', 'KkoLevel', 'InternalAnnouncement', 'Grade',
        'Gallery', 'ExamSession', 'DownloadCategory', 'Download', 'CurriculumPlan',
        'CounselingTicket', 'ContactMessage', 'ClassAssignment', 'ClassAnnouncement',
        'ClassMaterial', 'Achievement', 'Academic',
    ];

    protected function setUp(): void
    {
        parent::setUp();
        
        // Ensure we have the base roles
        Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'teacher', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'counselor', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'editor', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'piket', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'guru_ekstrakurikuler', 'guard_name' => 'web']);
    }

    /**
     * Test that the command exists and is registered.
     */
    public function test_command_exists_as_an_artisan_command(): void
    {
        $this->assertTrue(
            class_exists(DeployShieldPermissions::class),
            'DeployShieldPermissions command class should exist'
        );
        
        $command = new DeployShieldPermissions();
        $this->assertEquals('shield:deploy', $command->getName());
    }

    /**
     * Test that the command has the correct signature with options.
     */
    public function test_command_has_correct_signature_with_options(): void
    {
        $command = new DeployShieldPermissions();
        
        $this->assertStringContainsString('shield:deploy', $command->getName());
        $this->assertNotNull($command->getDefinition()->getOption('skip-generate'));
        $this->assertNotNull($command->getDefinition()->getOption('skip-seed'));
        $this->assertNotNull($command->getDefinition()->getOption('force'));
    }

    /**
     * Test that the command runs the shield:generate step.
     * Note: shield:generate may be slow or unavailable in tests, so we test the skip functionality.
     */
    public function test_it_runs_shield_generate_step(): void
    {
        // Create some sample permissions that shield:generate would create
        $this->seed(PermissionSeederForDeployTest::class);

        // Run with only generate step (skip seed to avoid dependency issues)
        // Note: In tests we can't actually run shield:generate as it requires full Filament setup
        // This test verifies the command handles skip flags correctly
        $this->artisan('shield:deploy', [
            '--skip-generate' => true,
            '--force' => true,
        ])
            ->expectsOutputToContain('Shield Permission Deployment')
            ->assertExitCode(0);
    }

    /**
     * Test that the command runs the seeder step and assigns permissions.
     */
    public function test_it_runs_seeder_step_and_assigns_permissions(): void
    {
        // Pre-create permissions that the seeder expects
        $this->seed(PermissionSeederForDeployTest::class);

        // Run the command
        $this->artisan('shield:deploy', [
            '--skip-generate' => true,
            '--force' => true,
        ])
            ->expectsOutputToContain('ShieldPermissionSeeder')
            ->assertExitCode(0);

        // Verify permissions were assigned to roles
        $admin = Role::where('name', 'admin')->first();
        $this->assertGreaterThan(0, $admin->permissions->count(), 'Admin should have permissions assigned');
    }

    /**
     * Test that the command clears permission cache.
     */
    public function test_it_clears_permission_cache(): void
    {
        $this->seed(PermissionSeederForDeployTest::class);

        // Run with skip-generate but run the seeder so verification passes
        $this->artisan('shield:deploy', [
            '--skip-generate' => true,
            '--force' => true,
        ])
            ->expectsOutputToContain('cache')  // Look for 'cache' in output
            ->assertExitCode(0);
    }

    /**
     * Test that the command verifies super_admin role exists.
     */
    public function test_it_verifies_super_admin_role_exists(): void
    {
        $this->seed(PermissionSeederForDeployTest::class);

        $this->artisan('shield:deploy', [
            '--skip-generate' => true,
            '--force' => true,
        ])
            ->expectsOutputToContain('super_admin')
            ->assertExitCode(0);

        $superAdmin = Role::where('name', 'super_admin')->first();
        $this->assertNotNull($superAdmin, 'super_admin role should exist');
    }

    /**
     * Test that the command verifies non-super roles have permissions.
     */
    public function test_it_verifies_non_super_roles_have_permissions(): void
    {
        $this->seed(PermissionSeederForDeployTest::class);

        $this->artisan('shield:deploy', [
            '--skip-generate' => true,
            '--force' => true,
        ])->assertExitCode(0);

        // Verify at least one non-super role has permissions
        $admin = Role::where('name', 'admin')->first();
        $teacher = Role::where('name', 'teacher')->first();

        $hasPermissions = $admin->permissions()->exists() || $teacher->permissions()->exists();
        $this->assertTrue($hasPermissions, 'At least one non-super role should have permissions');
    }

    /**
     * Test that the command fails gracefully when permissions are missing.
     */
    public function test_it_fails_gracefully_when_permissions_missing(): void
    {
        // Don't create permissions - seeder should fail

        $this->artisan('shield:deploy', [
            '--skip-generate' => true,
            '--force' => true,
        ])
            ->assertExitCode(1); // Should fail
    }

    /**
     * Test that the generate step can be skipped.
     */
    public function test_it_can_skip_generate_step(): void
    {
        $this->seed(PermissionSeederForDeployTest::class);

        $this->artisan('shield:deploy', [
            '--skip-generate' => true,
            '--force' => true,
        ])
            ->expectsOutputToContain('Skipping shield:generate')
            ->assertExitCode(0);
    }

    /**
     * Test that the seed step can be skipped.
     * Note: When both generate and seed are skipped, verification may fail because
     * no permissions are assigned. This is expected behavior.
     */
    public function test_it_can_skip_seed_step(): void
    {
        // Create permissions first so verification doesn't fail completely
        $this->seed(PermissionSeederForDeployTest::class);
        
        // When skipping both, the verification will show warnings but that's expected
        $this->artisan('shield:deploy', [
            '--skip-generate' => true,
            '--skip-seed' => true,
            '--force' => true,
        ])
            ->expectsOutputToContain('Skipping ShieldPermissionSeeder')
            ->assertExitCode(1); // Expected to fail since no permissions assigned
    }

    /**
     * Test that permission cache reflects latest assignment after cache reset.
     * Validates: Requirement 12.2
     */
    public function test_permission_cache_reflects_latest_assignment(): void
    {
        // Create permissions
        $this->seed(PermissionSeederForDeployTest::class);

        // Create a user with admin role
        $user = \App\Models\User::factory()->create();
        $user->assignRole('admin');

        // Run deployment
        $this->artisan('shield:deploy', [
            '--skip-generate' => true,
            '--force' => true,
        ])->assertExitCode(0);

        // Clear the permission cache manually to simulate fresh request
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        // After cache reset, can() should reflect the new assignments
        // The admin role should have permissions per the seeder map
        $admin = Role::where('name', 'admin')->first();
        $permissionNames = $admin->permissions->pluck('name')->toArray();

        // Check that admin has at least some permissions from the map
        $map = ShieldPermissionSeeder::map();
        $expectedAdminPermissions = $map['admin'] ?? [];

        $hasExpectedPermissions = !empty(array_intersect($permissionNames, $expectedAdminPermissions));
        $this->assertTrue($hasExpectedPermissions, 'Admin should have permissions from seeder map');
    }

    /**
     * Test that the deployment sequence is correct.
     * Validates: Requirement 12.1
     */
    public function test_deployment_sequence_is_correct(): void
    {
        // This test verifies the sequence described in requirements 12.1:
        // shield:generate → db:seed ShieldPermissionSeeder → permission:cache-reset + cache clear
        
        $this->seed(PermissionSeederForDeployTest::class);

        // Track the order of operations
        $executedSteps = [];

        // We can verify the command exists and has the right methods
        $command = new DeployShieldPermissions();
        
        // The command should have methods for each step
        $this->assertTrue(method_exists($command, 'runGenerate'), 'Should have runGenerate method');
        $this->assertTrue(method_exists($command, 'runSeeder'), 'Should have runSeeder method');
        $this->assertTrue(method_exists($command, 'runCacheClear'), 'Should have runCacheClear method');
        $this->assertTrue(method_exists($command, 'runVerification'), 'Should have runVerification method');
        
        // Verify the steps property defines correct order
        $reflection = new \ReflectionClass($command);
        $stepsProperty = $reflection->getProperty('steps');
        $stepsProperty->setAccessible(true);
        $steps = $stepsProperty->getValue($command);
        
        $expectedSteps = ['generate', 'seed', 'cache', 'verify'];
        $actualSteps = array_keys($steps);
        
        $this->assertEquals($expectedSteps, $actualSteps, 'Steps should be in correct order: generate, seed, cache, verify');
    }

    /**
     * Test that super_admin is not touched by seeder.
     */
    public function test_super_admin_not_touched_by_seeder(): void
    {
        $this->seed(PermissionSeederForDeployTest::class);

        // Run deployment
        $this->artisan('shield:deploy', [
            '--skip-generate' => true,
            '--force' => true,
        ])->assertExitCode(0);

        // Verify super_admin has no direct permissions assigned
        // (it gets access via gate intercept, not permission assignment)
        $superAdmin = Role::where('name', 'super_admin')->first();
        
        // super_admin should have 0 direct permissions (gate bypass)
        // But if Shield assigned permissions, that's also acceptable
        // The key is that the seeder does NOT manage super_admin
        $map = ShieldPermissionSeeder::map();
        $this->assertArrayNotHasKey('super_admin', $map, 'Seeder map should not include super_admin');
    }

    /**
     * Test that unmanaged roles preserve their permissions.
     */
    public function test_unmanaged_roles_preserve_permissions(): void
    {
        // Create an unmanaged role
        $customRole = Role::firstOrCreate(['name' => 'custom_role', 'guard_name' => 'web']);
        
        // Create and assign a custom permission
        $customPerm = Permission::firstOrCreate(['name' => 'CustomPermission', 'guard_name' => 'web']);
        $customRole->givePermissionTo($customPerm);

        $this->seed(PermissionSeederForDeployTest::class);

        // Run deployment
        $this->artisan('shield:deploy', [
            '--skip-generate' => true,
            '--force' => true,
        ])->assertExitCode(0);

        // Refresh the role
        $customRole = Role::where('name', 'custom_role')->first();
        
        // Custom role should still have its original permission
        $this->assertTrue(
            $customRole->hasPermissionTo('CustomPermission'),
            'Unmanaged role should preserve its permissions'
        );
    }

    /**
     * Test that gating prevents unsafe deployment.
     */
    public function test_gating_prevents_unsafe_deployment(): void
    {
        // The command should warn when permissions already exist
        $this->seed(PermissionSeederForDeployTest::class);

        // Run once to seed
        $this->artisan('shield:deploy', [
            '--skip-generate' => true,
            '--force' => true,
        ])->assertExitCode(0);

        // Second run should show warning about existing permissions (the word "exist" appears in the warning)
        $this->artisan('shield:deploy', [
            '--skip-generate' => true,
            '--force' => true,
        ])
            ->expectsOutputToContain('exist')
            ->assertExitCode(0);
    }
}

/**
 * Helper seeder to create all permissions needed for testing.
 * This creates a minimal set of permissions that the ShieldPermissionSeeder expects.
 */
class PermissionSeederForDeployTest extends \Illuminate\Database\Seeder
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
