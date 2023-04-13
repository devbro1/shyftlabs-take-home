<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\User;
use App\Rules\HasPermission;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class CompanyController extends Controller
{
    /**
     * @OA\GET(
     *     path="/api/v1/companies",
     *     summary="get all companies",
     *     tags={"Companies"},
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *     @OA\Parameter(
     *         description="Owner ID",
     *         in="query",
     *         name="user_id",
     *         required=false, @OA\Examples(
     *             example="int",
     *             value="1",
     *             summary="Owner Id"
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        return QueryBuilder::for(Company::class)
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::partial('name'),
                AllowedFilter::exact('active'),
            ])
            ->allowedSorts(['id', 'name', 'active'])
            ->jsonPaginate()
        ;
    }

    /**
     * @OA\POST(
     *     path="/api/v1/companies",
     *     summary="create a company",
     *     tags={"Companies"},
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 ref="#/components/schemas/Company"
     *             )
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated_data = Company::validate($request);
        $company = new Company($validated_data);
        $users = $request->validate([
            'owner_ids' => ['required', 'array'],
            'owner_ids.*' => ['integer', new HasPermission(User::class, 'Service Lead')],
            'employee_ids' => ['required', 'array'],
            'employee_ids.*' => ['integer'],
        ]);
        $company->save();
        $company->owners()->syncWithPivotValues($users['owner_ids'], ['position' => 'owner']);
        $company->employees()->syncWithPivotValues($users['employee_ids'], ['position' => 'employee']);

        $company->load('owners', 'employees');

        return ['message' => 'Company was created successfully', 'data' => $company];
    }

    /**
     * @OA\Get(
     *     tags={"Companies"},
     *     path="/api/v1/companies/{id}",
     *     summary="shows a company",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true, @OA\Schema(
     *             type="int"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     )
     * )
     */
    public function show(Company $company)
    {
        $company->load('owners', 'employees');

        return $company;
    }

    /**
     * @OA\PUT(
     *     path="/api/v1/companies/{company_id}",
     *     summary="create a company",
     *     tags={"Companies"},
     *     @OA\Parameter(
     *         name="company_id",
     *         in="path",
     *         required=true, @OA\Schema(
     *             type="int"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 ref="#/components/schemas/Company"
     *             ),
     *             @OA\Property(
     *                 property="employee_ids",
     *                 description="list if user_ids that are employees",
     *                 example="",
     *                 type="array",
     *
     *             )
     *         )
     *     )
     * )
     */
    public function update(Request $request, Company $company)
    {
        $validated_data = Company::validate($request, $company);
        $users = $request->validate([
            'owner_ids' => ['required', 'array'],
            'owner_ids.*' => ['integer', new HasPermission(User::class, 'Service Lead')],
            'employee_ids' => ['nullable', 'array'],
            'employee_ids.*' => ['integer'],
        ]);

        $company->fill($validated_data);
        $company->save();
        $company->owners()->syncWithPivotValues($users['owner_ids'], ['position' => 'owner']);
        $company->employees()->syncWithPivotValues($users['employee_ids'], ['position' => 'employee']);

        $company->load('owners', 'employees');

        return ['message' => 'Company was updated successfully', 'data' => $company];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
    }
}
