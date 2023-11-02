<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
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
        $method = $this->method();
        if($method == 'PUT') {
            return [
                "companyName" => ['required'],
                "companyRegistrationNumber" => ['required', 'min:11', 'max:11'],
                "companyFoundationDate" => ['required'],
                "country" => ['required'],
                "zipCode" => ['required'],
                "city" => ['required'],
                "streetAddress" => ['required'],
                "latitude" => ['required'],
                "longitude" => ['required'],
                "companyOwner" => ['required'],
                "employees" => ['required', 'int'],
                "activity" => ['required'],
                "active" => ['required', 'bool'],
                "email" => ['required', 'email'],
                "password" => ['required'],
            ];
        }else{
            return [
                "companyName" => ['sometimes','required'],
                "companyRegistrationNumber" => ['sometimes','required', 'min:11', 'max:11'],
                "companyFoundationDate" => ['sometimes','required'],
                "country" => ['sometimes','required'],
                "zipCode" => ['sometimes','required'],
                "city" => ['sometimes','required'],
                "streetAddress" => ['sometimes','required'],
                "latitude" => ['sometimes','required'],
                "longitude" => ['sometimes','required'],
                "companyOwner" => ['sometimes','required'],
                "employees" => ['sometimes','required', 'int'],
                "activity" => ['sometimes','required'],
                "active" => ['sometimes','required', 'bool'],
                "email" => ['sometimes','required', 'email'],
                "password" => ['sometimes','required'],
            ];
        }
    }
}
