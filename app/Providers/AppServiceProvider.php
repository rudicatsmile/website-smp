<?php

namespace App\Providers;

use App\Filament\Middleware\LogAuthorizationEvents;
use App\Models\ClassAnnouncement;
use App\Models\InternalAnnouncement;
use App\Models\SpmbRegistration;
use App\Models\StudentAttendance;
use App\Observers\ClassAnnouncementObserver;
use App\Observers\InternalAnnouncementObserver;
use App\Observers\SpmbRegistrationObserver;
use App\Observers\StudentAttendanceObserver;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Permission;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Carbon::setLocale('id');
        SpmbRegistration::observe(SpmbRegistrationObserver::class);
        InternalAnnouncement::observe(InternalAnnouncementObserver::class);
        StudentAttendance::observe(StudentAttendanceObserver::class);
        ClassAnnouncement::observe(ClassAnnouncementObserver::class);

        // Register authorization event logging for diagnostic purposes
        // This helps identify permission issues during and after migration
        // Validates: Requirements 10.3, 10.4
        $this->registerAuthorizationLogging();
    }

    /**
     * Register Gate::after callback for logging authorization failures.
     * 
     * This provides diagnostic logging for permission check failures,
     * helping to distinguish between migration issues and actual permission problems.
     * 
     * Validates: Requirements 10.3, 10.4
     */
    protected function registerAuthorizationLogging(): void
    {
        // Gate::after is called after every authorization check
        // We use it to log failures with helpful diagnostic information
        Gate::after(function ($user, $ability, $result, $arguments) {
            // Only log failures (result is false/null)
            if ($result === true) {
                return;
            }

            // Skip if user is not authenticated or doesn't have roles
            if (!$user || !method_exists($user, 'roles')) {
                return;
            }

            // Check if this is an admin panel request
            if (!request()->is('admin*')) {
                return;
            }

            $permission = $this->normalizeAbilityToPermission($ability, $arguments);
            
            $diagnostics = [
                'permission_checked' => $permission,
                'ability' => $ability,
                'user_id' => $user->id,
                'user_email' => $user->email ?? null,
                'user_roles' => $user->roles->pluck('name')->toArray(),
                'user_permissions_count' => $user->getAllPermissions()->count(),
                'permission_exists_in_db' => $permission ? Permission::where('name', $permission)->exists() : null,
                'request_url' => request()->fullUrl(),
            ];

            // Determine likely cause
            $diagnostics['likely_cause'] = $this->determineLikelyCause($diagnostics);

            // Log the failure
            \Illuminate\Support\Facades\Log::warning('Authorization check failed in admin panel', $diagnostics);
        });
    }

    /**
     * Normalize an ability string to a permission name.
     */
    protected function normalizeAbilityToPermission(string $ability, array $arguments): ?string
    {
        // Common Filament Shield permission prefixes
        $prefixes = ['viewAny', 'view', 'create', 'update', 'delete', 'deleteAny', 'restore', 'forceDelete', 'replicate', 'reorder'];
        
        // Check if ability starts with a known prefix
        foreach ($prefixes as $prefix) {
            if (str_starts_with(strtolower($ability), strtolower($prefix))) {
                // Extract the model/entity from arguments if available
                if (!empty($arguments)) {
                    $arg = $arguments[0];
                    if (is_object($arg)) {
                        $className = class_basename($arg);
                        return ucfirst($prefix) . ':' . $className;
                    }
                    if (is_string($arg)) {
                        return ucfirst($prefix) . ':' . $arg;
                    }
                }
                return $ability;
            }
        }

        // Already in Shield format (e.g., "View:PageName")
        if (str_contains($ability, ':')) {
            return $ability;
        }

        return $ability;
    }

    /**
     * Determine the likely cause of the authorization failure.
     */
    protected function determineLikelyCause(array $diagnostics): string
    {
        $permission = $diagnostics['permission_checked'];
        $permissionExists = $diagnostics['permission_exists_in_db'];
        $userPermissionsCount = $diagnostics['user_permissions_count'];
        $roles = $diagnostics['user_roles'];

        // Check if user has super_admin role
        if (in_array('super_admin', $roles)) {
            return 'super_admin_role_but_gate_intercept_not_working';
        }

        // Check if permission doesn't exist in database
        if ($permission && $permissionExists === false) {
            return 'permission_not_found_in_database';
        }

        // Check if user has no permissions at all
        if ($userPermissionsCount === 0) {
            return 'user_has_no_permissions_assigned';
        }

        // Permission exists but user doesn't have it
        if ($permission && $permissionExists === true) {
            return 'permission_not_assigned_to_user_role';
        }

        return 'unknown_cause';
    }
}
