<?php

namespace Database\Seeders;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Clear cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles for clinic management system
        $roles = [
            ["name" => RoleEnum::ADMIN, 'guard_name' => "web"],
            ["name" => RoleEnum::DOCTOR, 'guard_name' => "web"],
            ["name" => RoleEnum::PATIENT, 'guard_name' => "web"],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate($role);
        }

        $permissions = [
            // User Management
            ["name" => PermissionEnum::USERS_ADD, 'guard_name' => "web", 'page' => PermissionEnum::USERS],
            ["name" => PermissionEnum::USERS_VIEW, 'guard_name' => "web", 'page' => PermissionEnum::USERS],
            ["name" => PermissionEnum::USERS_UPDATE, 'guard_name' => "web", 'page' => PermissionEnum::USERS],
            ["name" => PermissionEnum::USERS_DELETE, 'guard_name' => "web", 'page' => PermissionEnum::USERS],

            // Settings
            ["name" => PermissionEnum::SETTING_ADD, 'guard_name' => "web", 'page' => PermissionEnum::SETTING],
            ["name" => PermissionEnum::SETTING_VIEW, 'guard_name' => "web", 'page' => PermissionEnum::SETTING],
            ["name" => PermissionEnum::SETTING_UPDATE, 'guard_name' => "web", 'page' => PermissionEnum::SETTING],
            ["name" => PermissionEnum::SETTING_DELETE, 'guard_name' => "web", 'page' => PermissionEnum::SETTING],

            // Role Management
            ["name" => PermissionEnum::MANAGE_ROLES, 'guard_name' => "web", 'page' => ''],

            // Specialties Management (Admin)
            ["name" => PermissionEnum::MANAGE_SPECIALTIES, 'guard_name' => "web", 'page' => ''],
            ["name" => PermissionEnum::MANAGE_SPECIALTIES_ADD, 'guard_name' => "web", 'page' => PermissionEnum::MANAGE_SPECIALTIES],
            ["name" => PermissionEnum::MANAGE_SPECIALTIES_VIEW, 'guard_name' => "web", 'page' => PermissionEnum::MANAGE_SPECIALTIES],
            ["name" => PermissionEnum::MANAGE_SPECIALTIES_UPDATE, 'guard_name' => "web", 'page' => PermissionEnum::MANAGE_SPECIALTIES],
            ["name" => PermissionEnum::MANAGE_SPECIALTIES_DELETE, 'guard_name' => "web", 'page' => PermissionEnum::MANAGE_SPECIALTIES],

            // Clinic Management (Admin)
            ["name" => PermissionEnum::MANAGE_CLINICS, 'guard_name' => "web", 'page' => ''],
            ["name" => PermissionEnum::MANAGE_CLINICS_VIEW, 'guard_name' => "web", 'page' => PermissionEnum::MANAGE_CLINICS],
            ["name" => PermissionEnum::MANAGE_CLINICS_APPROVE, 'guard_name' => "web", 'page' => PermissionEnum::MANAGE_CLINICS],
            ["name" => PermissionEnum::MANAGE_CLINICS_REJECT, 'guard_name' => "web", 'page' => PermissionEnum::MANAGE_CLINICS],

            // Patient Management (Doctor)
            ["name" => PermissionEnum::MANAGE_PATIENTS, 'guard_name' => "web", 'page' => ''],
            ["name" => PermissionEnum::MANAGE_PATIENTS_ADD, 'guard_name' => "web", 'page' => PermissionEnum::MANAGE_PATIENTS],
            ["name" => PermissionEnum::MANAGE_PATIENTS_VIEW, 'guard_name' => "web", 'page' => PermissionEnum::MANAGE_PATIENTS],
            ["name" => PermissionEnum::MANAGE_PATIENTS_UPDATE, 'guard_name' => "web", 'page' => PermissionEnum::MANAGE_PATIENTS],
            ["name" => PermissionEnum::MANAGE_PATIENTS_DELETE, 'guard_name' => "web", 'page' => PermissionEnum::MANAGE_PATIENTS],

            // Examination Management (Doctor)
            ["name" => PermissionEnum::MANAGE_EXAMINATIONS, 'guard_name' => "web", 'page' => ''],
            ["name" => PermissionEnum::MANAGE_EXAMINATIONS_ADD, 'guard_name' => "web", 'page' => PermissionEnum::MANAGE_EXAMINATIONS],
            ["name" => PermissionEnum::MANAGE_EXAMINATIONS_VIEW, 'guard_name' => "web", 'page' => PermissionEnum::MANAGE_EXAMINATIONS],
            ["name" => PermissionEnum::MANAGE_EXAMINATIONS_UPDATE, 'guard_name' => "web", 'page' => PermissionEnum::MANAGE_EXAMINATIONS],
            ["name" => PermissionEnum::MANAGE_EXAMINATIONS_DELETE, 'guard_name' => "web", 'page' => PermissionEnum::MANAGE_EXAMINATIONS],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate($permission);
        }

        // Admin gets all permissions
        $allPermissionIds = Permission::all()->pluck('id')->toArray();
        $adminRole = Role::whereName(RoleEnum::ADMIN)->first();
        $adminRole->permissions()->detach();
        $adminRole->permissions()->attach($allPermissionIds);

        // Doctor gets patient and examination management permissions
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
        $doctorRole = Role::whereName(RoleEnum::DOCTOR)->first();
        if ($doctorRole) {
            $doctorPermissionIds = Permission::whereIn('name', $doctorPermissions)->pluck('id')->toArray();
            $doctorRole->permissions()->sync($doctorPermissionIds);
        }

        // Patient role - no special permissions (view own records only)
        $patientRole = Role::whereName(RoleEnum::PATIENT)->first();
        if ($patientRole) {
            $patientRole->permissions()->sync([]);
        }
    }
}
