<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    public $timestamps = false;
    public const PRIMARY_KEY = 'companyId';

    protected $primaryKey = self::PRIMARY_KEY;

    protected $fillable = [
        "companyName",
        "companyRegistrationNumber",
        "companyFoundationDate",
        "country",
        "zipCode",
        "city",
        "streetAddress",
        "latitude",
        "longitude",
        "companyOwner",
        "employees",
        "activity",
        "active",
        "email",
        "password",
    ];
}
