<?php

namespace App\Http\Requests;

use App\Enums\GenderEnum;
use App\Models\Configuration;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConfigurationsRequest extends FormRequest
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

        $configurationIds =  Configuration::where('key', $this->key)->pluck('id')->toArray();

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('configuration_translations')
                    ->whereIn('configuration_id', $configurationIds)
                    ->where(function ($query) {
                        return $query->where('locale', app()->getLocale());
                    })->ignore($this->id,'configuration_id'),
            ],
            'key'=>['required', 'string','max:255'],
            'score'=>['nullable', 'integer','min:0', 'max:100'],

        ];
    }
}
