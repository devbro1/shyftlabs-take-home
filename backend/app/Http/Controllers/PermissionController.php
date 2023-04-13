<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class PermissionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/permissions",
     *     summary="get list of all permissions",
     *     tags={"Permissions"},
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
        return QueryBuilder::for(Permission::class)
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::partial('name'),
                AllowedFilter::partial('description'),
            ])
            ->allowedSorts(['id', 'name', 'description'])
            ->jsonPaginate()
        ;
    }

    /**
     * @OA\Post(
     *     path="/api/v1/permissions",
     *     summary="create new permission",
     *     tags={"Permissions"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 ref="#/components/schemas/Permission"
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
     *                 value={"message": "Permission was created successfully", "data": {"guard_name": "api", "name": "new name", "description": "meow meow", "updated_at": "2022-02-07T20:16:08.000000Z", "created_at": "2022-02-07T20:16:08.000000Z", "id": 16}}
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
     *                 value={"status": "error", "message": "Please check the errors and try again", "errors": {"description": {"Required": {}}}}
     *             )
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated_data = Permission::validate($request);
        $permission = new Permission($validated_data);
        $permission->save();

        return ['message' => 'Permission was created successfully', 'data' => $permission];
    }

    /**
     * @OA\Get(
     *     path="/api/v1/permissions/{id}",
     *     summary="get a permission",
     *     tags={"Permissions"},
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
     *                 summary="A result object.",
     *                 value={"id": 10, "name": "Update Announcement", "description": "", "guard_name": "api", "created_at": "2022-02-07T19:54:11.000000Z", "updated_at": "2022-02-07T19:54:11.000000Z", "system": true}
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
    public function show(Permission $permission)
    {
        return $permission;
    }

    /**
     * @OA\Put(
     *     path="/api/v1/permissions/{id}",
     *     summary="update a permission",
     *     tags={"Permissions"},
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
     *                 ref="#/components/schemas/Permission"
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
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Examples(
     *                 example="result",
     *                 summary="Error Message",
     *                 value={"status": "error", "message": "Cannot edit system permission"}
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
     *                 value={"status": "error", "message": "Please check the errors and try again", "errors": {"description": {"Required": {}}}}
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
    public function update(Request $request, Permission $permission)
    {
        $validated_data = Permission::validate($request, $permission);
        $permission->update($validated_data);

        return ['message' => 'Permission was updated successfully', 'data' => $permission];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Permission $permission)
    {
    }
}
