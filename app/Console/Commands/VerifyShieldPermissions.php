<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class VerifyShieldPermissions extends Command
{
    protected $signature = 'shield:verify {--panel=admin : The panel ID to verify}';

    protected $description = 'Verify that all Filament panel entities have corresponding permissions';

    public function handle(): int
    {
        $panelId = $this->option('panel');
        
        $this->info("Verifying permissions for panel: {$panelId}");
        
        $panel = $this->getPanel($panelId);
        
        if (!$panel) {
            $this->error("Panel [{$panelId}] not found.");
            return Command::FAILURE;
        }
        
        $resources = $this->getResources($panel);
        $pages = $this->getPages($panel);
        $widgets = $this->getWidgets($panel);
        
        $this->info("Found {$resources->count()} Resources, {$pages->count()} Pages, {$widgets->count()} Widgets");
        
        $allEntities = $resources->concat($pages)->concat($widgets);
        
        $requiredPermissions = $this->generateRequiredPermissions($allEntities);
        
        $existingPermissions = Permission::where('guard_name', 'web')
            ->pluck('name')
            ->toArray();
        
        $failures = [];
        $successCount = 0;
        
        foreach ($requiredPermissions as $permission) {
            if (in_array($permission, $existingPermissions)) {
                $successCount++;
            } else {
                $failures[] = $permission;
            }
        }
        
        $this->info("Permissions found: {$successCount}");
        
        if (!empty($failures)) {
            $this->warn("Missing permissions (" . count($failures) . "):");
            foreach ($failures as $failure) {
                $this->warn("  - {$failure}");
            }
            $this->newLine();
            $this->warn("Run 'php artisan shield:generate --all' to generate missing permissions.");
        } else {
            $this->info("All required permissions exist.");
        }
        
        // Additional verification: Check for orphaned permissions (in DB but not in code)
        $extraPermissions = array_diff($existingPermissions, $requiredPermissions);
        
        if (!empty($extraPermissions)) {
            $this->newLine();
            $this->info("Extra permissions in database (not in current code): " . count($extraPermissions));
            foreach (array_slice($extraPermissions, 0, 10) as $extra) {
                $this->line("  - {$extra}");
            }
            if (count($extraPermissions) > 10) {
                $this->line("  ... and " . (count($extraPermissions) - 10) . " more");
            }
        }
        
        return empty($failures) ? Command::SUCCESS : Command::FAILURE;
    }
    
    protected function getPanel(string $panelId): ?\Filament\Panel
    {
        $panels = filament()->getPanels();
        return $panels[$panelId] ?? null;
    }
    
    protected function getResources(\Filament\Panel $panel): Collection
    {
        $resources = [];
        
        foreach ($panel->getResources() as $resource) {
            $resources[] = [
                'class' => $resource,
                'slug' => (new \ReflectionClass($resource))->getShortName(),
            ];
        }
        
        return collect($resources);
    }
    
    protected function getPages(\Filament\Panel $panel): Collection
    {
        $pages = [];
        
        foreach ($panel->getPages() as $page) {
            $pages[] = [
                'class' => $page,
                'slug' => (new \ReflectionClass($page))->getShortName(),
            ];
        }
        
        return collect($pages);
    }
    
    protected function getWidgets(\Filament\Panel $panel): Collection
    {
        $widgets = [];
        
        foreach ($panel->getWidgets() as $widget) {
            $widgets[] = [
                'class' => $widget,
                'slug' => (new \ReflectionClass($widget))->getShortName(),
            ];
        }
        
        return collect($widgets);
    }
    
    protected function generateRequiredPermissions(Collection $entities): array
    {
        $permissions = [];
        
        // Map of plural resource names to their singular permission names
        // These are exceptions where the Filament resource name doesn't match the permission name
        $pluralToSingular = [
            'Programs' => 'Program',
            'News' => 'News',
            'SchoolClass' => 'SchoolClass',
            'SchoolClasses' => 'SchoolClass',
            'TahfidzClass' => 'TahfidzClass',
            'TahfidzClasses' => 'TahfidzClass',
        ];
        
        foreach ($entities as $entity) {
            $slug = $entity['slug'];
            
            if (isset($entity['class'])) {
                $reflection = new \ReflectionClass($entity['class']);
                $parent = $reflection->getParentClass();
                
                // Check if it's a Resource (extends FilamentResource)
                if ($parent && $parent->getName() === 'Filament\Resources\Resource') {
                    // For Resources, remove "Resource" suffix
                    $entityName = preg_replace('/Resource$/', '', $slug);
                    
                    // Use predefined mapping or just remove trailing 's' if present
                    $permissionName = $pluralToSingular[$entityName] ?? $entityName;
                    
                    // Resource permissions using Filament Shield format (camelCase)
                    $permissions[] = "View:{$permissionName}";
                    $permissions[] = "ViewAny:{$permissionName}";
                    $permissions[] = "Create:{$permissionName}";
                    $permissions[] = "Update:{$permissionName}";
                    $permissions[] = "Delete:{$permissionName}";
                    $permissions[] = "DeleteAny:{$permissionName}";
                } else {
                    // Page and Widget permissions - just use the name
                    $permissions[] = $slug;
                }
            } else {
                // Fallback for entities without class
                $permissions[] = $slug;
            }
        }
        
        return array_unique($permissions);
    }
}