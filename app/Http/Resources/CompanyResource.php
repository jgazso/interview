<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "companyId" => $this->companyId,
			"companyName" => $this->companyName,
			"companyRegistrationNumber" => $this->companyRegistrationNumber,
			"companyFoundationDate" => $this->companyFoundationDate,
			"country" => $this->country,
			"zipCode" => $this->zipCode,
			"city" => $this->city,
			"streetAddress" => $this->streetAddress,
			"latitude" => $this->latitude,
			"longitude" => $this->longitude,
			"companyOwner" => $this->companyOwner,
			"employees" => $this->employees,
			"activity" => $this->activity,
			"active" => (bool)$this->activ,
			"email" => $this->email,
//			"password" => $this->password
        ];
    }
}
