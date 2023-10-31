<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCompanyRequest extends FormRequest
{

    public function authorize():bool
    {
        return true;
    }

    public function rules(): array
    {
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
    }
}
