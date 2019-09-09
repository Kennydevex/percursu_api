<?php

namespace App\Http\Requests\Percursu;

use Illuminate\Foundation\Http\FormRequest;

class CreatePartnerRequest extends FormRequest
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
            'name' => 'bail|required|alpha',
            'lastname' => '',
            'birthdate' => '',
            'ic' => '',
            'nif' => '',
            'avatar' => '',
            'cover' => '',
            // --------------
            'type' => '',
            'number' => '',
            'email' => '',
            // --------------
            'link' => '',
            'description' => '',
            // --------------
            'country' => '',
            'city' => '',
            'street' => '',
            'postcode' => '',
            // -------------
            'designation' => '',
            'institution' => '',
            'from' => '',
            'to' => '',
            'subjects' => '',
            'level' => '',
            // ---------------
            'task' => '',
            'employer' => '',
            'responsibility' => '',
            'attachment' => '',
            // --------------
        ];
    }

    public function attributes()
    {
        return [
        ];
    }
}
