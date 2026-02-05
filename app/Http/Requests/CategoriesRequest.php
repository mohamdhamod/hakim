<?php

namespace App\Http\Requests;

use App\Enums\GenderEnum;
use App\Models\Category;
use App\Models\Configuration;
use App\Models\Country;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoriesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('category_translations')
                    ->where(function ($query) {
                        return $query->where('locale', app()->getLocale());
                    })->ignore($this->id,'category_id'),
            ],

            'description'=>['nullable', 'string','max:9000'],
            'short_description'=>['nullable', 'string','max:1200'],

            'image' => [ $this->id ? 'nullable' : 'required','file', 'max:4096', 'mimes:jpeg,png,jpg,svg'],
        ];
    }
}
