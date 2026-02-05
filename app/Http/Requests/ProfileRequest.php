<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'base_currency_id' => ['required', 'integer', 'exists:configurations,id'],
            'commercial_registration_number' => ['nullable', 'string', 'max:100'],
            'license_number' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:500'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Company name is required.',
            'name.max' => 'Company name cannot exceed 255 characters.',
            'base_currency_id.exists' => 'The selected base currency is invalid.',
            'email.email' => 'Please provide a valid email address.',
            'logo.image' => 'Logo must be an image file.',
            'logo.mimes' => 'Logo must be a jpeg, png, jpg, or gif file.',
            'logo.max' => 'Logo size cannot exceed 2MB.',
            'subscription_expiry_date.date' => 'Please provide a valid date.',
        ];
    }
}
