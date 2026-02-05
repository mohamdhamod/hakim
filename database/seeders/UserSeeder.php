<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Admin users
        $adminUsers = [
            [
                'name' => "Mohammad Hammoud",
                'phone' => '+963936263906',
                'email' => "mohamdhamod46@gmail.com"
            ],
        ];

        foreach ($adminUsers as $adminUser) {
            $this->createUser($adminUser, RoleEnum::ADMIN);
        }

        // Demo Doctor user
        $doctorUsers = [
            [
                'name' => "د. أحمد الطبيب",
                'phone' => '+963900000001',
                'email' => "doctor@hakim.com"
            ],
        ];

        foreach ($doctorUsers as $doctorUser) {
            $this->createUser($doctorUser, RoleEnum::DOCTOR, 'Doctor@2026');
        }

        // Demo Patient user
        $patientUsers = [
            [
                'name' => "مريض تجريبي",
                'phone' => '+963900000002',
                'email' => "patient@hakim.com"
            ],
        ];

        foreach ($patientUsers as $patientUser) {
            $this->createUser($patientUser, RoleEnum::PATIENT, 'Patient@2026');
        }
    }

    protected function createUser(array $userData, $role, $password = null)
    {
        if (empty($userData['email'])) {
            return;
        }

        DB::transaction(function () use ($userData, $role, $password) {
            $isExistingUser = User::where('email', $userData['email'])->exists();

            $attributesToUpdate = array_merge($userData, [
                'email_verified_at' => now(),
            ]);

            if (!$isExistingUser) {
                $attributesToUpdate['password'] = $password ?? 'Hakim@2026';
            } elseif ($password !== null) {
                $attributesToUpdate['password'] = $password;
            }

            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                $attributesToUpdate
            );

            if ($role) {
                $user->syncRoles([$role]);
            }
        });
    }
}
