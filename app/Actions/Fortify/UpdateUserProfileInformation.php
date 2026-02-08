<?php

namespace App\Actions\Fortify;

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
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],

            'image'       => ['nullable' , 'file', 'max:4076', 'mimes:jpg,jpeg,png'],
            ])->validateWithBag('updateProfileInformation');

        $image = $this->processImage($input['image'] ?? null, $user->image);

        $data = [
            'name' => $input['name'],
            'phone' => $input['phone'],
            'email' => $input['email'],
            'image' => $image,
        ];

        if ($input['email'] !== $user->email && $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $user->forceFill($data)->save();
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
}
