<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\Clinic;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DemoUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    
        // Get the first active specialty for the demo doctor
        $specialty = Specialty::active()->ordered()->first();

        // Create Demo Doctor
        $doctor = User::firstOrCreate(
            ['email' => 'doctor@demo.com'],
            [
                'name' => 'د. أحمد الطبيب',
                'email' => 'doctor@demo.com',
                'password' => 'demo1234',
                'email_verified_at' => now(),
            ]
        );

        // Assign doctor role
        if (!$doctor->hasRole(RoleEnum::DOCTOR)) {
            $doctor->assignRole(RoleEnum::DOCTOR);
        }

        // Create clinic for the doctor
        $clinic = Clinic::firstOrCreate(
            ['user_id' => $doctor->id],
            [
                'specialty_id' => $specialty?->id,
                'name' => 'عيادة الشفاء',
                'address' => 'شارع الصحة، مبنى 123، الطابق الثاني',
                'phone' => '+966501234567',
                'description' => 'عيادة متخصصة في الطب العام والباطني',
                'status' => 'approved',
                'approved_at' => now(),
            ]
        );

        $this->command->info('✓ Demo Doctor created:');
        $this->command->info('  Email: doctor@demo.com');
        $this->command->info('  Password: demo1234');
        $this->command->info('  Clinic: ' . $clinic->name);
        if ($specialty) {
            $this->command->info('  Specialty: ' . $specialty->name);
        }

        
        $this->command->newLine();
        $this->command->info('========================================');
        $this->command->info('All demo accounts use password: demo1234');
        $this->command->info('========================================');
    }
}
