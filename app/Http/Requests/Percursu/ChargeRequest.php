<?php

namespace App\Http\Requests\Percursu;

use Illuminate\Foundation\Http\FormRequest;

class ChargeRequest extends FormRequest
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
            'name' => 'bail|required|string|max:60|unique:charges',
            'description' => 'max:500',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Nome do cargo',
        ];
    }
}
