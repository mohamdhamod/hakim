<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],

            'price' => ['required', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0', 'lte:price'],
            'currency_id' => ['required', 'integer', 'exists:configurations,id'],

            'categories' => ['nullable', 'array'],
            'categories.*' => ['integer', 'exists:categories,id'],

            'images' => ['nullable', 'array'],
            'images.*.id' => ['nullable', 'integer', 'exists:product_images,id'],
            'images.*.image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }
}
