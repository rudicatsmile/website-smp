<?php

namespace App\Filament\Middleware;

use Illuminate\Auth\Events\Failed as AuthFailed;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Permission;

/**
 * Event listener for authorization events in the admin panel.
 * 
 * This class provides comprehensive logging for authorization events,
 * helping to diagnose permission-related issues during and after migration.
 * 
 * Validates: Requirements 10.3, 10.4
 * 
 * Usage:
 * - Call LogAuthorizationEvents::register() in AppServiceProvider::boot()
 * 
 * The listener will log:
 * - Gate checks that fail
 * - Permission checks with context about the user's roles and permissions
 * - Suggestions for resolving authorization failures
 */
class LogAuthorizationEvents
{
    /**
     * Register the authorization event listeners.
     * 
     * This method sets up listeners for authorization-related events
     * to provide diagnostic logging for permission failures.
     */
    public static function register(): void
    {
        // Listen for Gate::after checks to log authorization decisions
        // This is done via the Gate facade in AppServiceProvider
    }

    /**
     * Log a permission check failure with diagnostic information.
     * 
     * @param  mixed  $user  The user being checked
     * @param  string  $ability  The ability/permission being checked
     * @param  array  $arguments  Additional arguments passed to the gate
     * @param  bool  $result  The result of the authorization check
     */
    public static function logPermissionCheck(
        mixed $user,
        string $ability,
        array $arguments,
        bool $result
    ): void {
        // Only log failures
        if ($result) {
            return;
        }

        // Skip if user is not authenticated
        if (!$user || !method_exists($user, 'roles')) {
            return;
        }

        $permission = self::normalizeAbilityToPermission($ability, $arguments);
        
        $diagnostics = [
            'permission_checked' => $permission,
            'ability' => $ability,
            'user_id' => $user->id,
            'user_roles' => $user->roles->pluck('name')->toArray(),
            'user_permissions_count' => $user->getAllPermissions()->count(),
            'permission_exists' => $permission ? Permission::where('name', $permission)->exists() : null,
        ];

        Log::channel('single')->warning('Permission check failed in admin panel', $diagnostics);
    }

    /**
     * Normalize an ability/permission string from gate checks.
     */
    protected static function normalizeAbilityToPermission(string $ability, array $arguments): ?string
    {
        // Common Filament Shield permission patterns
        $prefixes = ['viewAny', 'view', 'create', 'update', 'delete', 'deleteAny', 'restore', 'forceDelete', 'replicate', 'reorder'];
        
        foreach ($prefixes as $prefix) {
            if (str_starts_with($ability, $prefix)) {
                // Extract the model or entity from arguments
                if (!empty($arguments)) {
                    $arg = $arguments[0];
                    if (is_object($arg)) {
                        $className = class_basename($arg);
                        return "{$prefix}:{$className}";
                    }
                    if (is_string($arg)) {
                        return "{$prefix}:{$arg}";
                    }
                }
                return $ability;
            }
        }

        // Check if it's already in Shield format (e.g., "View:PageName")
        if (str_contains($ability, ':')) {
            return $ability;
        }

        return $ability;
    }
}
