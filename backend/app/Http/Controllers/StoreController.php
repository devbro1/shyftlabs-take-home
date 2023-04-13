<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class StoreController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/stores/",
     *     summary="get list of all stores",
     *     tags={"Stores"},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     )
     * )
     */
    public function index(Request $request)
    {
        return QueryBuilder::for(Store::class)
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
     * @OA\Post(
     *     path="/api/v1/stores/",
     *     summary="create new store",
     *     tags={"Stores"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 ref="#/components/schemas/Store"
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
     *                 value={"message": "Store was created successfully", "data": {"id": 1, "created_at": "2022-01-17T00:57:37.000000Z", "updated_at": "2022-01-17T00:59:06.000000Z", "active": true, "store_no": "79553", "name": "distinctio qui", "address": "878 Stroman Vista", "city": "Goldaport", "province_code": "ON", "country_code": "CA", "postal_code": "L3T1E1", "longitude": null, "latitude": null}}
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
        $store = new Store();
        $store->fill(Store::validate($request));
        $store->save();

        return ['message' => 'Store was created successfully', 'data' => $store];
    }

    /**
     * @OA\Get(
     *     path="/api/v1/stores/{id}",
     *     summary="get a store",
     *     tags={"Stores"},
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
     *                 value={"id": 1, "created_at": "2022-01-17T00:57:37.000000Z", "updated_at": "2022-01-17T00:59:06.000000Z", "active": true, "store_no": "79553", "name": "distinctio qui", "address": "878 Stroman Vista", "city": "Goldaport", "province_code": "ON", "country_code": "CA", "postal_code": "L3T1E1", "longitude": null, "latitude": null}
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
    public function show(Store $store)
    {
        return $store;
    }

    /**
     * @OA\Put(
     *     path="/api/v1/stores/{id}",
     *     summary="update a store",
     *     tags={"Stores"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true, @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 ref="#/components/schemas/Store"
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
     *                 value={"message": "Store was update successfully", "data": {"id": 1, "created_at": "2022-01-17T00:57:37.000000Z", "updated_at": "2022-01-17T00:59:06.000000Z", "active": true, "store_no": "79553", "name": "distinctio qui", "address": "878 Stroman Vista", "city": "Goldaport", "province_code": "ON", "country_code": "CA", "postal_code": "L3T1E1", "longitude": null, "latitude": null}}
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
     *                 value={}
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
    public function update(Request $request, Store $store)
    {
        $store->fill(Store::validate($request));
        $store->save();

        return ['message' => 'Store was updated successfully', 'data' => $store];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Store $store)
    {
    }
}
