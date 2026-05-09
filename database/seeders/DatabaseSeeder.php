<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            AdminUserSeeder::class,
            StaffCategorySeeder::class,
            StaffMemberSeeder::class,
            MaterialCategorySeeder::class,
            MaterialSeeder::class,
            StaffScheduleSeeder::class,
            InternalAnnouncementSeeder::class,
            PortalContentSeeder::class,
            QuizContentSeeder::class,
            CounselingSeeder::class,
            ParentPortalSeeder::class,
            DemoContentSeeder::class,
        ]);
    }
}
