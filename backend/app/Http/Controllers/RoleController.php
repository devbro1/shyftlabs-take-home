<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class RoleController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/roles/",
     *     summary="get list of all roles",
     *     tags={"Roles"},
     *     @OA\Response(
     *         response=200,
     *         description="OK"
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
        return QueryBuilder::for(Role::class)
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
     *     path="/api/v1/roles/",
     *     summary="create new role",
     *     tags={"Roles"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 ref="#/components/schemas/Role"
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
     *                 value={}
     *             )
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validated_data = Role::validate($request);
        $role = new Role($validated_data);
        $role->save();
        $role->syncPermissions($input['permissions'] ?? []);

        return ['message' => 'Role was created successfully', 'data' => $role];
    }

    /**
     * @OA\Get(
     *     path="/api/v1/roles/{id}",
     *     summary="get a role",
     *     tags={"Roles"},
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
    public function show(Role $role)
    {
        $role->load('permissions');

        return $role;
    }

    /**
     * @OA\Put(
     *     path="/api/v1/roles/{id}",
     *     summary="update an role",
     *     tags={"Roles"},
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
     *                 ref="#/components/schemas/Role"
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
    public function update(Request $request, Role $role)
    {
        $input = $request->all();
        $validated_data = Role::validate($request, $role);
        $role->update($validated_data);
        $role->syncPermissions($input['permissions']);

        return ['message' => 'Role was updated successfully', 'data' => $role];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Role $role)
    {
        $role->delete();

        return ['message' => 'Role was deleted successfully'];
    }

    // public function all()
    // {
    //     return Role::all();
    // }

    // public function default()
    // {
    //     return new Role();
    // }
}
