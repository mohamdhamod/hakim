<?php

namespace App\Http\Requests;

use App\Enums\ProductStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductsRequest extends FormRequest
{
	public function authorize(): bool
	{
		// Authorization is handled by dashboard middleware/permissions.
		return true;
	}

	public function rules(): array
	{
		return [
			'user_id' => ['required', 'integer', 'exists:users,id'],
			'title' => ['required', 'string', 'max:255'],
			'short_description' => ['nullable', 'string'],
			'description' => ['nullable', 'string'],
			'details' => ['nullable', 'string'],
			'status' => ['required', Rule::in(ProductStatusEnum::ALL)],
			'price' => ['required', 'numeric', 'min:0'],
			'discount' => ['nullable', 'numeric', 'min:0', 'lte:price'],

			'currency_id' => ['required', 'integer', 'exists:configurations,id'],

			'categories' => ['required', 'array','min:1'],
			'categories.*' => ['integer', 'exists:categories,id'],

			'images' => ['nullable', 'array'],
			'images.*.id' => ['nullable', 'integer', 'exists:product_images,id'],
			'images.*.image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'], // 5MB each

		];
	}
}
