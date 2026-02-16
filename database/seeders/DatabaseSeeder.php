<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Core seeders
            RoleSeeder::class,
            UserSeeder::class,
            
            // Configuration seeders
            ConfigTitleTextSeeder::class,
            ConfigImagesSeeder::class,
            ConfigEmailLinkSeeder::class,
            ConfigurationOptionsSeeder::class,
            CountrySeeder::class,
            
            // Clinic system seeders
            ClinicServicesSeeder::class,
            SpecialtiesSeeder::class,
            ClinicPermissionsSeeder::class,
            LabTestTypeSeeder::class,
            VaccinationTypeSeeder::class,

            
            // Demo data (optional - comment out in production)
            DemoUsersSeeder::class,
            DemoPatientsSeeder::class,
        ]);
    }
}
