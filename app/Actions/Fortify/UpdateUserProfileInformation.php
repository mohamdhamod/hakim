<?php

namespace App\Actions\Fortify;

use App\Enums\RoleEnum;
use App\Models\User;
use App\Traits\FileHandler;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    use FileHandler;
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, string>  $input
     */
    public function update(User $user, array $input): void
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'image' => ['nullable', 'file', 'max:4076', 'mimes:jpg,jpeg,png'],
        ];

        // Add clinic validation rules for doctors
        if ($user->hasRole(RoleEnum::DOCTOR) && $user->clinic) {
            $rules['specialty_id'] = ['required', 'integer', 'exists:specialties,id'];
            $rules['clinic_address'] = ['nullable', 'string', 'max:500'];
            $rules['clinic_logo'] = ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'];
        }

        Validator::make($input, $rules)->validateWithBag('updateProfileInformation');


        $data = [
            'name' => $input['name'],
            'phone' => $input['phone'],
            'email' => $input['email'],
        
        ];

        if ($input['email'] !== $user->email && $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $user->forceFill($data)->save();
        }

        // Update clinic info for doctors
        if ($user->hasRole(RoleEnum::DOCTOR) && $user->clinic) {
            $clinicData = [
                'specialty_id' => $input['specialty_id'] ?? $user->clinic->specialty_id,
                'address' => $input['clinic_address'] ?? $user->clinic->address,
            ];

            // Handle clinic logo upload using FileHandler
            $clinicData['logo'] = $this->processClinicLogo(
                $input['clinic_logo'] ?? null, 
                $user->clinic->logo
            );

            $user->clinic->update($clinicData);
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  array<string, string>  $input
     */
    protected function updateVerifiedUser(User $user, array $data): void
    {
        $data = [
            'name' => $data['name'],
            'phone' => $data['phone'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'email_verified_at' => null,
            'image' => $data['image'],
        ];
        $user->forceFill($data)->save();

        $user->sendEmailVerificationNotification();
    }

    protected function processImage($imageFile, $oldImage)
    {
        if (!$oldImage && $imageFile && $imageFile->isValid() && $imageFile->isFile()) {
            return $this->storeFile($imageFile, 'users', false);
        } elseif($oldImage && $imageFile && $imageFile->isValid() && $imageFile->isFile()){
            return $this->updateFile($imageFile, $oldImage, 'users',false);
        }
        return $oldImage;
    }

    /**
     * Process clinic logo using FileHandler trait.
     */
    protected function processClinicLogo($logoFile, $oldLogo)
    {
        if (!$oldLogo && $logoFile && $logoFile->isValid() && $logoFile->isFile()) {
            return $this->storeFile($logoFile, 'clinics/logos');
        } elseif ($oldLogo && $logoFile && $logoFile->isValid() && $logoFile->isFile()) {
            return $this->updateFile($logoFile, $oldLogo, 'clinics/logos');
        }
        return $oldLogo;
    }
}
