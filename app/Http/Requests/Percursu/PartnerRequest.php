<?php

namespace App\Http\Requests\Percursu;

use Illuminate\Foundation\Http\FormRequest;

class PartnerRequest extends FormRequest
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
            
        ];
    }

    public function attributes()
    {
        return [
        ];
    }
}


// formData: {
//       partner: {
//         name: "",
//         lastname: "",
//         gender: "",
//         birthdate: "",
//         ic: "",
//         nif: "",
//         avatar: "default.svg",
//         cover: "default.png",
//         color: "",
//         status: "false",
//         charges: []
//       },

//       charge: {
//         name: "",
//         description: ""
//       },

//       experiences: [
//         {
//           task: "",
//           from: "",
//           to: "",
//           ongoing: false,
//           employer: "",
//           responsibility: "",
//           attachment: ""
//         }
//       ],
//       formations: [
//         {
//           designation: "",
//           from: "",
//           to: "",
//           ongoing: false,
//           institution: "",
//           subjects: "",
//           level: "",
//           country: "",
//           city: "",
//           attachment: ""
//         }
//       ],
//       phones: [
//         {
//           number: "",
//           type: ""
//         }
//       ],
//       skills: [
//         {
//           name: "",
//           description: ""
//         }
//       ],

//       sites: [],
//       socials: [],
//       couriers: [
//         {
//           email: "",
//           type: ""
//         }
//       ],
//       address: {
//         country: "Cabo Verde",
//         city: "",
//         street: "",
//         postcode: ""
//       },
//       location: {
//         lat: "22",
//         lng: "29"
//       }
//     },
