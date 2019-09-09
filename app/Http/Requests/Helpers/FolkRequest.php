<?php

namespace App\Http\Requests\Percursu;

use Illuminate\Foundation\Http\FormRequest;

class FolkRequest extends FormRequest
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
            'name' => 'bail',
            'lastname' => '',
            'gender' => '',
            'birthdate' => '',
            'ic' => '',
            'nif' => '',
            'avatar' => '',
            'cover' => '',
            'color' => '',
            'status' => '',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Nome do cargo',
        ];
    }
}
