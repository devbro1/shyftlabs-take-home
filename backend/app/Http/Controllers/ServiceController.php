<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class ServiceController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/services/",
     *     summary="get list of all services",
     *     tags={"Services"},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Examples(
     *                 example="result",
     *                 summary="paginated result",
     *                 value={}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Examples(
     *                 example="result",
     *                 summary="Error Message",
     *                 value={"status": "error", "message": "Unauthenticated."}
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        return QueryBuilder::for(Service::class)
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::partial('name'),
                AllowedFilter::partial('description'),
                AllowedFilter::scope('available'),
            ])
            ->allowedSorts(['id', 'name', 'description'])
            ->jsonPaginate()
        ;
    }

    /**
     * @OA\Post(
     *     path="/api/v1/services/",
     *     summary="create new service",
     *     tags={"Services"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 ref="#/components/schemas/Service"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Examples(
     *                 example="result",
     *                 summary="success message and new resource.",
     *                 value={}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Examples(
     *                 example="result",
     *                 summary="Error Message",
     *                 value={"status": "error", "message": "Unauthenticated."}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="validation error",
     *         @OA\JsonContent(
     *             @OA\Examples(
     *                 example="validation error",
     *                 summary="",
     *                 value={"status": "error", "message": "Please check the errors and try again", "errors": {"title": {"Required": {}}, "body": {"Required": {}}}}
     *             )
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $service = new Service();
        $service->fill(Service::validate($request));
        $service->save();

        return ['message' => 'Service was created successfully', 'data' => $service];
    }

    /**
     * @OA\Get(
     *     path="/api/v1/services/{id}",
     *     summary="get a service",
     *     tags={"Services"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true, @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Examples(
     *                 example="result",
     *                 summary="An result object.",
     *                 value={}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Examples(
     *                 example="result",
     *                 summary="Error Message",
     *                 value={"status": "error", "message": "Unauthenticated."}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="not found",
     *         @OA\JsonContent(
     *             @OA\Examples(
     *                 example="result",
     *                 summary="not found",
     *                 value={"status": "error", "message": "Resource not found or access denied"}
     *             )
     *         )
     *     )
     * )
     */
    public function show(Service $service)
    {
        return $service;
    }

    /**
     * @OA\Put(
     *     path="/api/v1/services/{id}",
     *     summary="update a service",
     *     tags={"Services"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true, @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 ref="#/components/schemas/Service"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Examples(
     *                 example="result",
     *                 summary="success message and new resource.",
     *                 value={}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Examples(
     *                 example="result",
     *                 summary="Error Message",
     *                 value={"status": "error", "message": "Unauthenticated."}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="validation error",
     *         @OA\JsonContent(
     *             @OA\Examples(
     *                 example="validation error",
     *                 summary="",
     *                 value={"status": "error", "message": "Please check the errors and try again", "errors": {"title": {"Required": {}}, "body": {"Required": {}}}}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="not found",
     *         @OA\JsonContent(
     *             @OA\Examples(
     *                 example="result",
     *                 summary="not found",
     *                 value={"status": "error", "message": "Resource not found or access denied"}
     *             )
     *         )
     *     )
     * )
     */
    public function update(Request $request, Service $service)
    {
        $input = $service->validate($request);
        $fillable = $service->getFillable();

        foreach ($fillable as $fill) {
            if (isset($input[$fill])) {
                $service->{$fill} = $input[$fill];
            }
        }

        $service->save();

        return ['message' => 'User was updated successfully', 'data' => $service];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service)
    {
    }
}
