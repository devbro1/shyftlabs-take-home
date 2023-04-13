<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceAvailability;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class ServiceAvailabilityController extends Controller
{
    /**
     * @OA\GET(
     *     path="/api/v1/service-availabilities/",
     *     summary="get all service availabilities",
     *     tags={"Service Availabilities"},
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *     @OA\Parameter(
     *         description="store ID",
     *         in="query",
     *         name="store_id",
     *         required=false, @OA\Examples(
     *             example="int",
     *             value="1",
     *             summary="Store Id"
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Provider ID for the services",
     *         in="query",
     *         name="company_id",
     *         required=false, @OA\Examples(
     *             example="int",
     *             value="1",
     *             summary="user Id"
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Service",
     *         in="query",
     *         name="service_id",
     *         required=false, @OA\Examples(
     *             example="int",
     *             value="1",
     *             summary="service Id"
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        return QueryBuilder::for(ServiceAvailability::class)
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('company_id'),
                AllowedFilter::exact('service_id'),
            ])
            ->allowedSorts(['id', 'company_id', 'service_id'])
            ->jsonPaginate()
        ;
    }

    /**
     * @OA\Post(
     *     path="/api/v1/service-availabilities/",
     *     summary="create new announcement",
     *     tags={"Service Availabilities"},
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *     @OA\RequestBody(
     *         description="Information for all SAs that are to be created",
     *         required=true, @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     description="ID for stores, it can be an array",
     *                     property="store_id",
     *                     type="integer",
     *                     format="int32",
     *
     *                 ),
     *                 @OA\Property(
     *                     description="user ID for provider, it can be an array. All given user_ids must have 'Service Lead' Permission",
     *                     property="company_id",
     *                     type="integer",
     *                     format="int32",
     *
     *                 ),
     *                 @OA\Property(
     *                     description="ID for service, it can be an array",
     *                     property="service_id",
     *                     type="integer",
     *                     format="int32",
     *
     *                 ),
     *                 @OA\Property(
     *                     description="ID for workflow, it can be an array",
     *                     property="workflow_id",
     *                     type="integer",
     *                     format="int32",
     *
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated_data = ServiceAvailability::validate($request);
        $count = 0;

        foreach (['service_id', 'company_id', 'store_id', 'workflow_id'] as $v) {
            if (!is_array($validated_data[$v])) {
                $validated_data[$v] = [$validated_data[$v]];
            }
        }

        foreach ($validated_data['service_id'] as $service_id) {
            foreach ($validated_data['company_id'] as $company_id) {
                foreach ($validated_data['store_id'] as $store_id) {
                    foreach ($validated_data['workflow_id'] as $workflow_id) {
                        $arr = [];
                        $arr['service_id'] = $service_id;
                        $arr['company_id'] = $company_id;
                        $arr['store_id'] = $store_id;
                        $arr['workflow_id'] = $workflow_id;
                        ServiceAvailability::upsert($arr, ['service_id', 'company_id', 'store_id'], ['workflow_id']);
                        ++$count;
                    }
                }
            }
        }

        return ['message' => 'Service Availability was created successfully', 'count' => $count];
    }

    /**
     * @OA\Get(
     *     path="/api/v1/service-availabilities/{service_availability_id}",
     *     summary="create new announcement",
     *     tags={"Service Availabilities"},
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *     @OA\Parameter(
     *         description="ID",
     *         in="path",
     *         name="service_availability_id",
     *         required=true, @OA\Examples(
     *             example="int",
     *             value="1",
     *             summary="ID"
     *         )
     *     )
     * )
     */
    public function show(ServiceAvailability $serviceAvailability)
    {
        return $serviceAvailability;
    }

    /**
     * @OA\Put(
     *     path="/api/v1/service-availabilities/{service_availability_id}",
     *     summary="create new announcement",
     *     tags={"Service Availabilities"},
     *     @OA\Parameter(
     *         description="ID",
     *         in="path",
     *         name="service_availability_id",
     *         required=true, @OA\Examples(
     *             example="int",
     *             value="1",
     *             summary="ID"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *     @OA\RequestBody(
     *         description="Information for all SAs that are to be created",
     *         required=true, @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     description="ID for stores",
     *                     property="store_id",
     *                     type="integer",
     *                     format="int32",
     *
     *                 ),
     *                 @OA\Property(
     *                     description="user ID for provider",
     *                     property="company_id",
     *                     type="integer",
     *                     format="int32",
     *
     *                 ),
     *                 @OA\Property(
     *                     description="ID for service",
     *                     property="service_id",
     *                     type="integer",
     *                     format="int32",
     *
     *                 ),
     *                 @OA\Property(
     *                     description="ID for workflow",
     *                     property="workflow_id",
     *                     type="integer",
     *                     format="int32",
     *
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function update(Request $request, ServiceAvailability $serviceAvailability)
    {
        $serviceAvailability->fill(ServiceAvailability::validate($request, $serviceAvailability));

        return ['message' => 'Service Availability was updated successfully', 'data' => $serviceAvailability];
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/service-availabilities/{service_availability_id}",
     *     summary="deletes given service Availability",
     *     tags={"Service Availabilities"},
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *     @OA\Parameter(
     *         description="ID",
     *         in="path",
     *         name="service_availability_id",
     *         required=true, @OA\Examples(
     *             example="int",
     *             value="1",
     *             summary="ID"
     *         )
     *     )
     * )
     */
    public function destroy(ServiceAvailability $serviceAvailability)
    {
        $serviceAvailability->delete();

        return ['message' => 'Service Availability was deleted successfully'];
    }
}
