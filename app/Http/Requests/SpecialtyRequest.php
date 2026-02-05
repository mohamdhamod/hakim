<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SpecialtyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
     
        return [
            'key' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-z_]+$/',
            ],
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'icon' => ['nullable', 'string', 'max:50'],
            'color' => ['nullable', 'string', 'max:20', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'key.required' => __('translation.specialties.validation.key_required'),
            'key.unique' => __('translation.specialties.validation.key_unique'),
            'key.regex' => __('translation.specialties.validation.key_format'),
            'name.required' => __('translation.specialties.validation.name_required'),
            'color.regex' => __('translation.specialties.validation.color_format'),
            'topics.*.name.required_with' => __('translation.specialties.validation.topic_name_required'),
        ];
    }
}
