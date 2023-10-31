<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Resources\CompanyCollection;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CompanyController extends Controller
{
    public function index(Request $request): CompanyCollection
    {
        return new CompanyCollection(Company::when($request->has('ids'), function ($builder) use ($request) {
            $builder->whereIn(Company::PRIMARY_KEY, explode(',', $request->get('ids')));
        })->get());
    }

    public function store(StoreCompanyRequest $request): CompanyResource
    {
        return new CompanyResource(Company::create($request->all()));
    }

    public function show(Company $company): CompanyResource
    {
        return new CompanyResource($company);
    }

    public function update(UpdateCompanyRequest $request, Company $company): Response
    {
        $company->update($request->all());

        return response([], 200);
    }

    public function destroy(Company $company): Response
    {
        $company->delete();

        return response([], 200);
    }
}
