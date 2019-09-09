<?php

namespace App\Http\Requests\System;

use Illuminate\Foundation\Http\FormRequest;

class PermissionRequest extends FormRequest
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
            'name' => 'required|unique:permissions',
            'display_name' => 'required|unique:permissions',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Nome da permissão',
        ];
    }
}
