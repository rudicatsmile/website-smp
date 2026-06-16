<?php

namespace App\Filament\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware that logs permission failures with helpful diagnostic information.
 * 
 * This middleware catches authorization failures (403 errors) in the admin panel
 * and logs helpful diagnostic information to help distinguish between:
 * - Permission not being assigned to the user's role
 * - Permission not existing in the database
 * - Configuration issues during migration
 * 
 * Validates: Requirements 10.3, 10.4
 * 
 * Requirement 10.3: "WHILE sebagian Resource, Page, atau Widget telah dimigrasi 
 * dan sebagian belum, THE Admin_Panel SHALL beroperasi tanpa kesalahan otorisasi 
 * pada entitas yang belum dimigrasi."
 * 
 * Requirement 10.4: "IF terjadi kesalahan otorisasi selama migrasi bertahap, 
 * THEN THE Admin_Panel SHALL menyampaikan pesan yang membedakan masalah akibat 
 * migrasi yang belum tuntas dari masalah permission yang sebenarnya."
 */
class LogPermissionFailures
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            return $next($request);
        } catch (AuthorizationException $e) {
            $this->logAuthorizationFailure($request, $e);
            
            // Re-throw to let Filament handle the response (typically 403)
            throw $e;
        }
    }

    /**
     * Log detailed diagnostic information about the authorization failure.
     */
    protected function logAuthorizationFailure(Request $request, AuthorizationException $e): void
    {
        $user = $request->user();
        
        if (!$user) {
            // Not authenticated - not our concern
            return;
        }

        // Extract the permission that was being checked
        $permissionChecked = $this->extractPermissionFromException($e);
        
        // Gather diagnostic information
        $diagnostics = [
            'permission_checked' => $permissionChecked,
            'user_id' => $user->id,
            'user_email' => $user->email,
            'user_roles' => $user->roles->pluck('name')->toArray(),
            'user_has_any_permissions' => $user->getAllPermissions()->count() > 0,
            'user_permission_count' => $user->getAllPermissions()->count(),
            'permission_exists_in_db' => $permissionChecked ? $this->permissionExists($permissionChecked) : null,
            'request_url' => $request->fullUrl(),
            'request_method' => $request->method(),
        ];

        // Determine the likely cause
        $diagnostics['likely_cause'] = $this->determineLikelyCause($diagnostics);

        // Log with helpful context
        Log::warning('Authorization failed in admin panel', $diagnostics);

        // Also log suggestions for resolution
        $this->logSuggestions($diagnostics);
    }

    /**
     * Extract the permission name from the AuthorizationException.
     */
    protected function extractPermissionFromException(AuthorizationException $e): ?string
    {
        // Try to extract from the exception message
        $message = $e->getMessage();
        
        // Common patterns in Laravel/Filament authorization messages
        if (preg_match('/permission ["\']?([^"\']+)["\']?/i', $message, $matches)) {
            return $matches[1];
        }

        // Try to get from the gate/ability that was checked
        if (property_exists($e, 'ability')) {
            return $e->ability;
        }

        // Check the response status code
        if (method_exists($e, 'getStatus')) {
            $status = $e->getStatus();
            if ($status === 403) {
                // Generic 403 - permission info not available
                return null;
            }
        }

        return null;
    }

    /**
     * Check if a permission exists in the database.
     */
    protected function permissionExists(string $permission): bool
    {
        return Permission::where('name', $permission)
            ->where('guard_name', 'web')
            ->exists();
    }

    /**
     * Determine the likely cause of the authorization failure.
     */
    protected function determineLikelyCause(array $diagnostics): string
    {
        $permission = $diagnostics['permission_checked'];
        $permissionExists = $diagnostics['permission_exists_in_db'];
        $userHasPermissions = $diagnostics['user_has_any_permissions'];
        $roles = $diagnostics['user_roles'];

        // Check if user has super_admin role
        if (in_array('super_admin', $roles)) {
            return 'super_admin_role_but_gate_intercept_not_working';
        }

        // Check if permission doesn't exist in database
        if ($permission && !$permissionExists) {
            return 'permission_not_found_in_database';
        }

        // Check if user has no permissions at all
        if (!$userHasPermissions) {
            return 'user_has_no_permissions_assigned';
        }

        // Permission exists but user doesn't have it
        if ($permission && $permissionExists) {
            return 'permission_not_assigned_to_user_role';
        }

        // Could not determine specific cause
        return 'unknown_cause';
    }

    /**
     * Log suggestions for resolving the authorization failure.
     */
    protected function logSuggestions(array $diagnostics): void
    {
        $cause = $diagnostics['likely_cause'];
        $permission = $diagnostics['permission_checked'];
        $roles = $diagnostics['user_roles'];
        
        $suggestions = [];

        switch ($cause) {
            case 'permission_not_found_in_database':
                $suggestions[] = "Permission '{$permission}' does not exist in the database.";
                $suggestions[] = 'Run: php artisan shield:generate --all';
                $suggestions[] = 'This will create the missing permissions.';
                break;

            case 'user_has_no_permissions_assigned':
                $suggestions[] = 'User has no permissions assigned to their role(s): ' . implode(', ', $roles);
                $suggestions[] = 'Run: php artisan db:seed --class=ShieldPermissionSeeder';
                $suggestions[] = 'Or assign permissions manually via Shield Role Management at /admin/shield/roles';
                break;

            case 'permission_not_assigned_to_user_role':
                $suggestions[] = "Permission '{$permission}' exists but is not assigned to role(s): " . implode(', ', $roles);
                $suggestions[] = 'Assign the permission via Shield Role Management at /admin/shield/roles/{id}/edit';
                $suggestions[] = 'Or run: php artisan db:seed --class=ShieldPermissionSeeder';
                break;

            case 'super_admin_role_but_gate_intercept_not_working':
                $suggestions[] = 'User has super_admin role but gate intercept is not working.';
                $suggestions[] = 'Check config/filament-shield.php: super_admin.intercept_gate should be "before"';
                $suggestions[] = 'Clear cache: php artisan permission:cache-reset && php artisan cache:clear';
                break;

            default:
                $suggestions[] = 'Investigate the authorization failure manually.';
                $suggestions[] = 'Check Shield Role Management at /admin/shield/roles';
                break;
        }

        Log::info('Authorization failure resolution suggestions', [
            'cause' => $cause,
            'suggestions' => $suggestions,
        ]);
    }
}
