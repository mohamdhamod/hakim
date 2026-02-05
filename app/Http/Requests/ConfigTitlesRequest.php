<?php

namespace App\Http\Requests;

use App\Enums\GenderEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConfigTitlesRequest extends FormRequest
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
        $rules = [
            'title'=>  ['required', 'string','max:255'],
            'description' => ['required', 'string','max:65000'],
        ];
        
        // Add validation for key and page when creating (POST request)
        if ($this->isMethod('post')) {
            $rules['key'] = ['required', 'string', 'max:255'];
            $rules['page'] = ['required', 'string', 'max:255'];
        }
        
        return $rules;
    }
}
