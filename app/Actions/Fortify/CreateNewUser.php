<?php

namespace App\Actions\Fortify;

use App\Enums\RoleEnum;
use App\Models\Clinic;
use App\Models\User;
use App\Traits\FileHandler;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;
    use FileHandler;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        $rules = [
            'registration_token' => ['required', 'string', 'min:10'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)],
            'term_and_policy' => ['required', 'accepted'],
            'phone' => ['nullable', 'string', 'max:20'],
            'user_type' => ['required', 'in:doctor,patient'],
        ];

        // Add clinic validation rules for doctors
        if (isset($input['user_type']) && $input['user_type'] === 'doctor') {
            $rules['specialty_id'] = ['required', 'integer', 'exists:specialties,id'];
            $rules['clinic_address'] = ['nullable', 'string', 'max:500'];
            $rules['clinic_services'] = ['nullable', 'array'];
            $rules['clinic_services.*'] = ['integer', 'exists:clinic_services,id'];
        }

        $validator = Validator::make($input, $rules);
        $validator->validate();

        DB::beginTransaction();
        try {

            $email = strtolower((string) $input['email']);
            $tokenHash = hash('sha256', (string) $input['registration_token']);

            $link = DB::table('registration_links')
                ->where('email', $email)
                ->where('token_hash', $tokenHash)
                ->where('expires_at', '>', now())
                ->lockForUpdate()
                ->first();

            if (!$link) {
                throw ValidationException::withMessages([
                    'email' => [__('translation.auth.continue_registration_invalid_or_expired')],
                ]);
            }

            $googleRegistration = Session::get('google_registration');
            $googleId = null;
            if (is_array($googleRegistration)
                && isset($googleRegistration['email'])
                && strtolower((string) $googleRegistration['email']) === $email
                && isset($googleRegistration['google_id'])
                && is_string($googleRegistration['google_id'])
                && $googleRegistration['google_id'] !== ''
            ) {
                $googleId = $googleRegistration['google_id'];
            }

            // Generate random password
            $randomPassword = Str::random(16);

            $userType = $input['user_type'];

            $user = User::create([
                'name' => $input['name'],
                'email' => $email,
                'phone' => $input['phone'] ?? null,
                'password' => $randomPassword,
                'term_and_policy' => $input['term_and_policy'] ?? 0,
                'email_verified_at' => now(),
                'google_id' => $googleId,
            ]);

            // Assign appropriate role
            $role = $userType === 'doctor' ? RoleEnum::DOCTOR : RoleEnum::PATIENT;
            $user->assignRole($role);

            // Create clinic for doctor
            if ($userType === 'doctor') {
                $clinic = Clinic::create([
                    'user_id' => $user->id,
                    'specialty_id' => $input['specialty_id'],
                    'name' => $input['name'], // استخدام اسم المستخدم كاسم للعيادة
                    'address' => $input['clinic_address'] ?? null,
                    'status' => 'pending',
                ]);

                // Attach services if provided
                if (!empty($input['clinic_services'])) {
                    $clinic->services()->attach($input['clinic_services']);
                }
            }

            DB::table('registration_links')
                ->where('email', $email)
                ->where('token_hash', $tokenHash)
                ->delete();

            if ($googleId !== null) {
                Session::forget('google_registration');
            }

            DB::commit();
            return $user;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

}
