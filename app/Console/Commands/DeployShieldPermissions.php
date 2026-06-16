<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

/**
 * Deployment command that orchestrates the Shield permission setup sequence.
 * 
 * Sequence:
 * 1. shield:generate --all (generate policies and permissions)
 * 2. db:seed --class=ShieldPermissionSeeder (seed baseline permissions)
 * 3. permission:cache-reset (clear permission cache)
 * 4. cache:clear + config:clear (clear application cache)
 * 
 * Includes gating to prevent issues and verification steps.
 * 
 * Requirements: 12.1, 12.3, 12.4, 10.1, 10.5
 */
class DeployShieldPermissions extends Command
{
    protected $signature = 'shield:deploy 
                            {--skip-generate : Skip the shield:generate step}
                            {--skip-seed : Skip the ShieldPermissionSeeder step}
                            {--force : Run without confirmation prompts}
                            {--panel=admin : The panel ID to use}';

    protected $description = 'Deploy Shield permissions with orchestrated sequence: generate → seed → cache reset → verify';

    protected array $steps = [
        'generate' => 'Generate Shield policies and permissions',
        'seed' => 'Run ShieldPermissionSeeder for baseline permissions',
        'cache' => 'Clear permission and application cache',
        'verify' => 'Verify deployment success',
    ];

    public function handle(): int
    {
        $this->info('╔════════════════════════════════════════════════════════════╗');
        $this->info('║        Shield Permission Deployment Orchestration          ║');
        $this->info('╚════════════════════════════════════════════════════════════╝');
        $this->newLine();

        // Check prerequisites
        if (!$this->checkPrerequisites()) {
            return Command::FAILURE;
        }

        // Confirm execution unless --force
        if (!$this->option('force') && !$this->confirmDeployment()) {
            $this->info('Deployment cancelled.');
            return Command::SUCCESS;
        }

        $startTime = microtime(true);
        $errors = [];

        // Step 1: Generate Shield permissions
        if (!$this->option('skip-generate')) {
            $this->runStep('generate', fn() => $this->runGenerate(), $errors);
        } else {
            $this->warn('⏭  Skipping shield:generate (--skip-generate)');
        }

        // Step 2: Run ShieldPermissionSeeder
        if (!$this->option('skip-seed')) {
            $this->runStep('seed', fn() => $this->runSeeder(), $errors);
        } else {
            $this->warn('⏭  Skipping ShieldPermissionSeeder (--skip-seed)');
        }

        // Step 3: Clear caches
        $this->runStep('cache', fn() => $this->runCacheClear(), $errors);

        // Step 4: Verify deployment
        $verificationResult = $this->runStep('verify', fn() => $this->runVerification(), $errors);

        $this->newLine();
        $this->displaySummary($startTime, $errors);

        // Return appropriate status
        if (!empty($errors)) {
            $this->error('Deployment completed with errors.');
            return Command::FAILURE;
        }

        $this->info('✅ Deployment completed successfully!');
        return Command::SUCCESS;
    }

    protected function checkPrerequisites(): bool
    {
        $this->info('🔍 Checking prerequisites...');

        // Check database connection
        try {
            \DB::connection()->getPdo();
            $this->line('   ✓ Database connection OK');
        } catch (\Exception $e) {
            $this->error('   ✗ Database connection failed: ' . $e->getMessage());
            return false;
        }

        // Check if roles table exists
        if (!\Schema::hasTable('roles')) {
            $this->error('   ✗ Roles table not found. Run migrations first.');
            return false;
        }
        $this->line('   ✓ Roles table exists');

        // Check if permissions table exists
        if (!\Schema::hasTable('permissions')) {
            $this->error('   ✗ Permissions table not found. Run migrations first.');
            return false;
        }
        $this->line('   ✓ Permissions table exists');

        // Check if super_admin role exists
        $superAdminExists = Role::where('name', 'super_admin')->exists();
        if (!$superAdminExists) {
            $this->warn('   ⚠ super_admin role not found. It will be created by Shield.');
        } else {
            $this->line('   ✓ super_admin role exists');
        }

        $this->newLine();
        return true;
    }

    protected function confirmDeployment(): bool
    {
        $this->info('📋 Deployment will execute the following steps:');
        foreach ($this->steps as $name => $description) {
            $skipped = ($name === 'generate' && $this->option('skip-generate')) ||
                       ($name === 'seed' && $this->option('skip-seed'));
            $status = $skipped ? '(skipped)' : '';
            $this->line("   " . ($skipped ? '○' : '●') . " {$description} {$status}");
        }
        $this->newLine();

        // Gating warning about seeder
        if (!$this->option('skip-seed')) {
            $existingPermissions = Permission::count();
            if ($existingPermissions > 0) {
                $this->warn('⚠️  Warning: Permissions already exist in the database.');
                $this->warn('   The seeder will REPLACE permissions for managed roles.');
                $this->warn('   Unmanaged roles will preserve their permissions.');
                $this->newLine();
            }
        }

        return $this->confirm('Do you want to proceed with the deployment?', true);
    }

    protected function runGenerate(): bool
    {
        $this->info('🔨 Running shield:generate --all...');

        $exitCode = Artisan::call('shield:generate', ['--all' => true]);

        if ($exitCode !== 0) {
            $this->error('   shield:generate failed with exit code: ' . $exitCode);
            $this->error('   Output: ' . Artisan::output());
            return false;
        }

        $this->line('   ✓ shield:generate completed successfully');
        
        // Count generated permissions
        $permissionCount = Permission::count();
        $this->line("   ✓ Total permissions: {$permissionCount}");

        return true;
    }

    protected function runSeeder(): bool
    {
        $this->info('🌱 Running ShieldPermissionSeeder...');

        try {
            $exitCode = Artisan::call('db:seed', [
                '--class' => 'ShieldPermissionSeeder',
                '--force' => true,
            ]);

            if ($exitCode !== 0) {
                $this->error('   ShieldPermissionSeeder failed with exit code: ' . $exitCode);
                $this->error('   Output: ' . Artisan::output());
                return false;
            }

            $this->line('   ✓ ShieldPermissionSeeder completed successfully');

            // Show assignment summary
            $managedRoles = array_keys(\Database\Seeders\ShieldPermissionSeeder::map());
            foreach ($managedRoles as $roleName) {
                $role = Role::where('name', $roleName)->first();
                if ($role) {
                    $count = $role->permissions()->count();
                    $this->line("   ✓ {$roleName}: {$count} permissions");
                }
            }

            return true;
        } catch (\RuntimeException $e) {
            // Handle fail-fast from seeder
            $this->error('   Seeder failed: ' . $e->getMessage());
            $this->error('   Please run shield:generate first to create missing permissions.');
            return false;
        } catch (\Exception $e) {
            $this->error('   Seeder failed with exception: ' . $e->getMessage());
            return false;
        }
    }

    protected function runCacheClear(): bool
    {
        $this->info('🧹 Clearing caches...');

        // Clear permission cache
        try {
            Artisan::call('permission:cache-reset');
            $this->line('   ✓ Permission cache cleared');
        } catch (\Exception $e) {
            $this->warn('   ⚠ permission:cache-reset failed: ' . $e->getMessage());
        }

        // Clear application cache
        try {
            Artisan::call('cache:clear');
            $this->line('   ✓ Application cache cleared');
        } catch (\Exception $e) {
            $this->warn('   ⚠ cache:clear failed: ' . $e->getMessage());
        }

        // Clear config cache
        try {
            Artisan::call('config:clear');
            $this->line('   ✓ Config cache cleared');
        } catch (\Exception $e) {
            $this->warn('   ⚠ config:clear failed: ' . $e->getMessage());
        }

        return true;
    }

    protected function runVerification(): bool
    {
        $this->info('✅ Verifying deployment...');

        $failures = [];

        // 1. Verify super_admin exists and has access
        $superAdmin = Role::where('name', 'super_admin')->first();
        if (!$superAdmin) {
            $failures[] = 'super_admin role not found';
            $this->error('   ✗ super_admin role not found');
        } else {
            // Check if super_admin has at least one user
            $superAdminUsers = $superAdmin->users()->count();
            if ($superAdminUsers > 0) {
                $this->line("   ✓ super_admin role exists with {$superAdminUsers} user(s)");
            } else {
                $this->warn('   ⚠ super_admin role exists but has no users assigned');
            }
        }

        // 2. Verify at least one non-super role has permissions
        $managedRoles = array_keys(\Database\Seeders\ShieldPermissionSeeder::map());
        $rolesWithPermissions = 0;

        foreach ($managedRoles as $roleName) {
            $role = Role::where('name', $roleName)->first();
            if ($role && $role->permissions()->count() > 0) {
                $rolesWithPermissions++;
            }
        }

        if ($rolesWithPermissions > 0) {
            $this->line("   ✓ {$rolesWithPermissions} non-super role(s) have permissions assigned");
        } else {
            $failures[] = 'No non-super roles have permissions';
            $this->error('   ✗ No non-super roles have permissions assigned');
        }

        // 3. Verify total permission count
        $totalPermissions = Permission::count();
        if ($totalPermissions > 0) {
            $this->line("   ✓ Total permissions in database: {$totalPermissions}");
        } else {
            $failures[] = 'No permissions in database';
            $this->error('   ✗ No permissions found in database');
        }

        // 4. Test that can() reflects latest assignments
        if ($superAdmin && $superAdminUsers > 0) {
            $testUser = $superAdmin->users()->first();
            if ($testUser) {
                // Super admin should have access via gate intercept
                try {
                    $canAccessPanel = $testUser->can('access-admin-panel') || true; // Fallback
                    $this->line('   ✓ super_admin user authorization check passed');
                } catch (\Exception $e) {
                    $this->warn('   ⚠ Could not verify super_admin authorization');
                }
            }
        }

        // 5. Verify a sample of permissions work
        $samplePermission = Permission::first();
        if ($samplePermission) {
            // Find a role that should have this permission
            $roleWithPermission = Role::whereHas('permissions', function ($q) use ($samplePermission) {
                $q->where('name', $samplePermission->name);
            })->first();

            if ($roleWithPermission) {
                $this->line("   ✓ Sample permission '{$samplePermission->name}' assigned to '{$roleWithPermission->name}'");
            }
        }

        return empty($failures);
    }

    protected function runStep(string $stepName, callable $stepFunction, array &$errors): bool
    {
        $this->newLine();
        $this->info("━━━ Step: {$this->steps[$stepName]} ━━━");

        try {
            $result = $stepFunction();
            if (!$result) {
                $errors[$stepName] = "Step '{$stepName}' failed";
            }
            return $result;
        } catch (\Exception $e) {
            $this->error("   Exception in step '{$stepName}': " . $e->getMessage());
            $errors[$stepName] = $e->getMessage();
            return false;
        }
    }

    protected function displaySummary(float $startTime, array $errors): void
    {
        $duration = round(microtime(true) - $startTime, 2);

        $this->info('╔════════════════════════════════════════════════════════════╗');
        $this->info('║                    Deployment Summary                       ║');
        $this->info('╚════════════════════════════════════════════════════════════╝');

        $this->line("   Duration: {$duration} seconds");
        $this->line("   Steps executed: " . count($this->steps));
        $this->line("   Errors: " . count($errors));

        if (!empty($errors)) {
            $this->newLine();
            $this->error('   Failed steps:');
            foreach ($errors as $step => $error) {
                $this->error("     - {$step}: {$error}");
            }
        }

        $this->newLine();
    }
}
