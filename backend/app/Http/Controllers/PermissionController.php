<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

/**
 * @group Roles and Permissions
 */
class PermissionController extends Controller
{
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

    public function store(Request $request)
    {
        $validated_data = Permission::validate($request);
        $permission = new Permission($validated_data);
        $permission->save();

        return ['message' => 'Permission was created successfully', 'data' => $permission];
    }

    public function show(Permission $permission)
    {
        return $permission;
    }

    public function update(Request $request, Permission $permission)
    {
        $validated_data = Permission::validate($request, $permission);
        $permission->update($validated_data);

        return ['message' => 'Permission was updated successfully', 'data' => $permission];
    }

    public function destroy(Request $request, Permission $permission)
    {
    }
}
