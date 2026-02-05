<?php

namespace Database\Seeders;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ClinicPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear permission cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create clinic management permissions (for Admin)
        $adminPermissions = [
            PermissionEnum::MANAGE_CLINICS,
            PermissionEnum::MANAGE_CLINICS_VIEW,
            PermissionEnum::MANAGE_CLINICS_APPROVE,
            PermissionEnum::MANAGE_CLINICS_REJECT,
        ];

        foreach ($adminPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create patient management permissions (for Doctor)
        $doctorPermissions = [
            PermissionEnum::MANAGE_PATIENTS,
            PermissionEnum::MANAGE_PATIENTS_ADD,
            PermissionEnum::MANAGE_PATIENTS_VIEW,
            PermissionEnum::MANAGE_PATIENTS_UPDATE,
            PermissionEnum::MANAGE_PATIENTS_DELETE,
            PermissionEnum::MANAGE_EXAMINATIONS,
            PermissionEnum::MANAGE_EXAMINATIONS_ADD,
            PermissionEnum::MANAGE_EXAMINATIONS_VIEW,
            PermissionEnum::MANAGE_EXAMINATIONS_UPDATE,
            PermissionEnum::MANAGE_EXAMINATIONS_DELETE,
        ];

        foreach ($doctorPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Get or create roles
        $adminRole = Role::firstOrCreate(['name' => RoleEnum::ADMIN, 'guard_name' => 'web']);
        $doctorRole = Role::firstOrCreate(['name' => RoleEnum::DOCTOR, 'guard_name' => 'web']);
        $patientRole = Role::firstOrCreate(['name' => RoleEnum::PATIENT, 'guard_name' => 'web']);

        // Assign permissions to Admin role
        $adminRole->givePermissionTo($adminPermissions);

        // Assign permissions to Doctor role
        $doctorRole->givePermissionTo($doctorPermissions);

        // Patient role doesn't need special permissions for now
        // They can only view their own records (controlled by controllers)

        $this->command->info('Clinic permissions seeded successfully!');
    }
}
