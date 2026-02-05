<?php

namespace App\Http\Requests;

use App\Enums\GenderEnum;
use App\Models\Configuration;
use App\Models\Country;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CountriesRequest extends FormRequest
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

        $countriesIds =  Country::where('code', $this->code)->pluck('id')->toArray();

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('country_translations')
                    ->whereIn('country_id', $countriesIds)
                    ->where(function ($query) {
                        return $query->where('locale', app()->getLocale());
                    })->ignore($this->id,'country_id'),
            ],

            'phone_extension'=>['required', 'string','max:255'],
            'code'=>['required', 'string','min:2', 'max:4'],
            'flag' => [ $this->id ? 'nullable' : 'required','file', 'max:4096', 'mimes:jpeg,png,jpg,svg'],
        ];
    }
}
