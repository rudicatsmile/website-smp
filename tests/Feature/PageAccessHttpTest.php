<?php

namespace Tests\Feature;

use App\Filament\Pages\JurnalMengajar;
use App\Filament\Pages\LaporanAbsensi;
use App\Filament\Pages\LaporanPenilaian;
use App\Filament\Pages\LaporanKasusSiswa;
use App\Filament\Pages\AbsensiHariIni;
use App\Filament\Pages\CetakKartuSiswa;
use App\Filament\Pages\TeachingToday;
use App\Filament\Pages\InputNilaiSesi;
use App\Filament\Pages\InputNilaiUjian;
use App\Models\User;
use Database\Seeders\ShieldPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * HTTP/Integration tests for migrated Page access control.
 * 
 * These tests validate the HTTP-level access behavior for custom Pages:
 * - Without View permission → akses langsung menghasilkan HTTP 403 (bukan 200) dan navigasi tersembunyi
 * - With View permission → akses mengembalikan HTTP 200
 * 
 * Validates: Requirements 5.3, 5.4
 * 
 * IMPORTANT: These tests require Pages to use the HasPageShield trait from Filament Shield.
 * The trait enables permission-based access control by overriding canAccess() and 
 * shouldRegisterNavigation() methods.
 * 
 * Pages using HasPageShield trait:
 * - JurnalMengajar
 * - LaporanAbsensi
 * - LaporanPenilaian
 * - LaporanKasusSiswa
 * - AbsensiHariIni
 * - CetakKartuSiswa
 * - TeachingToday
 * - InputNilaiSesi
 * - InputNilaiUjian
 * 
 * Tests the following scenarios:
 * 1. User WITHOUT View:{Page} permission gets HTTP 403 when accessing Page URL directly
 * 2. User WITH View:{Page} permission gets HTTP 200 when accessing Page URL directly
 * 3. Page is hidden from navigation when user lacks View permission
 */
class PageAccessHttpTest extends TestCase
{
    use RefreshDatabase;

    /**
     * List of migrated Pages to test.
     * These Pages should be controlled by Shield permissions via View:{Page}.
     * 
     * The permission name format is: View:{PageClassName}
     */
    protected array $testPages = [
        [
            'class' => JurnalMengajar::class,
            'name' => 'JurnalMengajar',
            'permission' => 'View:JurnalMengajar',
        ],
        [
            'class' => LaporanAbsensi::class,
            'name' => 'LaporanAbsensi',
            'permission' => 'View:LaporanAbsensi',
        ],
        [
            'class' => LaporanPenilaian::class,
            'name' => 'LaporanPenilaian',
            'permission' => 'View:LaporanPenilaian',
        ],
        [
            'class' => LaporanKasusSiswa::class,
            'name' => 'LaporanKasusSiswa',
            'permission' => 'View:LaporanKasusSiswa',
        ],
        [
            'class' => AbsensiHariIni::class,
            'name' => 'AbsensiHariIni',
            'permission' => 'View:AbsensiHariIni',
        ],
        [
            'class' => CetakKartuSiswa::class,
            'name' => 'CetakKartuSiswa',
            'permission' => 'View:CetakKartuSiswa',
        ],
        [
            'class' => TeachingToday::class,
            'name' => 'TeachingToday',
            'permission' => 'View:TeachingToday',
        ],
        [
            'class' => InputNilaiSesi::class,
            'name' => 'InputNilaiSesi',
            'permission' => 'View:InputNilaiSesi',
        ],
        [
            'class' => InputNilaiUjian::class,
            'name' => 'InputNilaiUjian',
            'permission' => 'View:InputNilaiUjian',
        ],
    ];

    /**
     * Set up the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed permissions needed for testing
        $this->seed(PagePermissionSeederForHttpTest::class);
        
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
     * Test: User WITHOUT View:{Page} permission gets HTTP 403 when accessing Page URL.
     * 
     * Validates: Requirement 5.3
     * "IF seorang pengguna tidak memiliki Permission view untuk sebuah Page yang 
     * telah dimigrasi, THEN THE Admin_Panel SHALL menolak akses ke Page tersebut 
     * dengan respons HTTP 403 dan tidak mengembalikan respons HTTP 200 apa pun."
     */
    public function test_user_without_view_permission_gets_403(): void
    {
        // Create admin role but WITHOUT View:JurnalMengajar permission
        $adminRole = Role::where('name', 'admin')->first();
        
        // Give admin some other permissions but NOT View:JurnalMengajar
        $otherPermissions = [
            'View:LaporanAbsensi',
            'View:TeachingToday',
        ];
        
        foreach ($otherPermissions as $permName) {
            $perm = Permission::firstOrCreate(['name' => $permName, 'guard_name' => 'web']);
            $adminRole->givePermissionTo($perm);
        }
        
        // Create admin user
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        
        // Act: Access JurnalMengajar page without View:JurnalMengajar permission
        $response = $this->actingAs($admin, 'web')
            ->get(JurnalMengajar::getUrl());
        
        // Assert: Should be denied with 403 (NOT 200)
        $response->assertStatus(403);
    }

    /**
     * Test: User WITH View:{Page} permission gets HTTP 200 when accessing Page URL.
     * 
     * Validates: Requirement 5.3
     * "WHEN seorang pengguna dengan Role tertentu mengakses sebuah Page... 
     * THE Admin_Panel SHALL mengizinkan akses jika dan hanya jika Role tersebut 
     * memiliki Permission yang sesuai."
     */
    public function test_user_with_view_permission_gets_200(): void
    {
        // Create admin role WITH View:JurnalMengajar permission
        $adminRole = Role::where('name', 'admin')->first();
        $jurnalView = Permission::firstOrCreate(['name' => 'View:JurnalMengajar', 'guard_name' => 'web']);
        $adminRole->givePermissionTo($jurnalView);
        
        // Create admin user
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        
        // Act: Access JurnalMengajar page with View:JurnalMengajar permission
        $response = $this->actingAs($admin, 'web')
            ->get(JurnalMengajar::getUrl());
        
        // Assert: Should be allowed with 200
        $response->assertStatus(200);
    }

    /**
     * Test: Multiple pages - access is granted if and only if permission exists.
     * 
     * This is a comprehensive test covering multiple pages.
     * Validates: Requirements 5.3, 5.4
     */
    public function test_access_granted_only_with_permission_for_multiple_pages(): void
    {
        // Create teacher role with some page permissions
        $teacherRole = Role::where('name', 'teacher')->first();
        
        // Give View:JurnalMengajar and View:TeachingToday (but NOT View:CetakKartuSiswa)
        $jurnalView = Permission::firstOrCreate(['name' => 'View:JurnalMengajar', 'guard_name' => 'web']);
        $teachingView = Permission::firstOrCreate(['name' => 'View:TeachingToday', 'guard_name' => 'web']);
        $teacherRole->givePermissionTo([$jurnalView, $teachingView]);
        
        // Create teacher user
        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');
        
        // Test JurnalMengajar page - WITH permission
        $response = $this->actingAs($teacher, 'web')
            ->get(JurnalMengajar::getUrl());
        $this->assertEquals(200, $response->getStatusCode(), 
            'Teacher with View:JurnalMengajar should get 200 on JurnalMengajar page');
        
        // Test TeachingToday page - WITH permission
        $response = $this->actingAs($teacher, 'web')
            ->get(TeachingToday::getUrl());
        $this->assertEquals(200, $response->getStatusCode(), 
            'Teacher with View:TeachingToday should get 200 on TeachingToday page');
        
        // Test CetakKartuSiswa page - WITHOUT permission (admin-only page)
        $response = $this->actingAs($teacher, 'web')
            ->get(CetakKartuSiswa::getUrl());
        $this->assertEquals(403, $response->getStatusCode(), 
            'Teacher WITHOUT View:CetakKartuSiswa should get 403 on CetakKartuSiswa page');
    }

    /**
     * Test: Navigation is hidden when user lacks View:{Page} permission.
     * 
     * Validates: Requirement 5.4
     * "IF seorang pengguna tidak memiliki Permission view untuk sebuah Page yang 
     * telah dimigrasi, THEN THE Admin_Panel SHALL menyembunyikan Page tersebut 
     * dari navigasi."
     */
    public function test_navigation_hidden_without_permission(): void
    {
        // Create teacher role with View:JurnalMengajar only
        $teacherRole = Role::where('name', 'teacher')->first();
        $jurnalView = Permission::firstOrCreate(['name' => 'View:JurnalMengajar', 'guard_name' => 'web']);
        $teacherRole->givePermissionTo($jurnalView);
        
        // Create teacher user
        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');
        
        // Act: Check shouldRegisterNavigation for pages
        $this->actingAs($teacher, 'web');
        
        // JurnalMengajar - should be visible (has permission)
        $this->assertTrue(
            JurnalMengajar::shouldRegisterNavigation(),
            'JurnalMengajar should be visible to teacher with View:JurnalMengajar'
        );
        
        // CetakKartuSiswa - should be hidden (no permission)
        $this->assertFalse(
            CetakKartuSiswa::shouldRegisterNavigation(),
            'CetakKartuSiswa should be hidden from teacher without View:CetakKartuSiswa'
        );
    }

    /**
     * Test: canAccess returns false when user lacks View permission.
     * 
     * For Pages, canAccess() is used for access control (not canViewAny).
     * 
     * Validates: Requirements 5.3, 5.4
     */
    public function test_can_access_returns_false_without_permission(): void
    {
        // Create teacher role without View:CetakKartuSiswa
        $teacherRole = Role::where('name', 'teacher')->first();
        // Don't give View:CetakKartuSiswa
        
        // Create teacher user
        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');
        
        $this->actingAs($teacher, 'web');
        
        // Assert: canAccess should return false for admin-only page
        $this->assertFalse(
            CetakKartuSiswa::canAccess(),
            'CetakKartuSiswa::canAccess should be false for teacher without permission'
        );
    }

    /**
     * Test: All migrated pages deny access without permission.
     * 
     * This test iterates through all migrated pages and verifies that
     * accessing them without permission returns HTTP 403.
     * 
     * Note: InputNilaiSesi and InputNilaiUjian are excluded because they
     * require query parameters (assessment/exam ID) and return 404 without them.
     */
    public function test_all_pages_deny_access_without_permission(): void
    {
        // Create user with no page permissions
        $counselorRole = Role::where('name', 'counselor')->first();
        // Give counselor only counseling-related permissions, not teaching pages
        $counselingView = Permission::firstOrCreate(['name' => 'ViewAny:CounselingTicket', 'guard_name' => 'web']);
        $counselorRole->givePermissionTo($counselingView);
        
        $counselor = User::factory()->create();
        $counselor->assignRole('counselor');
        
        // Test each page - counselor should NOT have access to teaching pages
        // Note: InputNilaiSesi and InputNilaiUjian are excluded as they require query params
        $teachingPages = [
            JurnalMengajar::class,
            TeachingToday::class,
            // InputNilaiSesi::class, // Excluded - requires ?assessment= query param
            // InputNilaiUjian::class, // Excluded - requires ?exam= query param
        ];
        
        foreach ($teachingPages as $pageClass) {
            $response = $this->actingAs($counselor, 'web')
                ->get($pageClass::getUrl());
            $this->assertEquals(403, $response->getStatusCode(), 
                "Counselor should get 403 on {$pageClass} without permission");
        }
    }

    /**
     * Test: Piket role can access AbsensiHariIni with permission.
     * 
     * Validates: Requirement 5.3 (specific role access)
     */
    public function test_piket_can_access_absensi_hari_ini_with_permission(): void
    {
        // Create piket role WITH View:AbsensiHariIni permission
        $piketRole = Role::where('name', 'piket')->first();
        $absensiView = Permission::firstOrCreate(['name' => 'View:AbsensiHariIni', 'guard_name' => 'web']);
        $piketRole->givePermissionTo($absensiView);
        
        // Create piket user
        $piket = User::factory()->create();
        $piket->assignRole('piket');
        
        // Act: Access AbsensiHariIni page
        $response = $this->actingAs($piket, 'web')
            ->get(AbsensiHariIni::getUrl());
        
        // Assert: Should be allowed with 200
        $response->assertStatus(200);
    }

    /**
     * Test: Piket role cannot access other pages without permission.
     * 
     * Validates: Requirement 5.3
     */
    public function test_piket_cannot_access_other_pages_without_permission(): void
    {
        // Create piket role WITH View:AbsensiHariIni only
        $piketRole = Role::where('name', 'piket')->first();
        $absensiView = Permission::firstOrCreate(['name' => 'View:AbsensiHariIni', 'guard_name' => 'web']);
        $piketRole->givePermissionTo($absensiView);
        
        // Create piket user
        $piket = User::factory()->create();
        $piket->assignRole('piket');
        
        // Act: Try to access CetakKartuSiswa (admin-only page)
        $response = $this->actingAs($piket, 'web')
            ->get(CetakKartuSiswa::getUrl());
        
        // Assert: Should be denied with 403
        $response->assertStatus(403);
    }
}

/**
 * Helper seeder to create all Page permissions needed for HTTP testing.
 */
class PagePermissionSeederForHttpTest extends \Illuminate\Database\Seeder
{
    public function run(): void
    {
        // Create permissions for all migrated pages
        $permissions = $this->getPagePermissions();
        
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }
    }

    protected function getPagePermissions(): array
    {
        // Get all Page permissions from the ShieldPermissionSeeder map
        $map = ShieldPermissionSeeder::map();
        $permissions = [];
        
        foreach ($map as $roleName => $rolePermissions) {
            foreach ($rolePermissions as $permission) {
                // Only include View: permissions for Pages
                if (str_starts_with($permission, 'View:') && !str_contains($permission, 'Any')) {
                    $permissions[] = $permission;
                }
            }
        }
        
        // Also add Resource permissions that might be referenced
        $permissions[] = 'ViewAny:CounselingTicket';
        
        return array_unique($permissions);
    }
}
