<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

/**
 * @group Roles and Permissions
 */
class RoleController extends Controller
{
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

    public function store(Request $request)
    {
        $input = $request->all();
        $validated_data = Role::validate($request);
        $role = new Role($validated_data);
        $role->save();
        $role->syncPermissions($input['permissions'] ?? []);

        return ['message' => 'Role was created successfully', 'data' => $role];
    }

    public function show(Role $role)
    {
        $role->load('permissions');

        return $role;
    }

    public function update(Request $request, Role $role)
    {
        $input = $request->all();
        $validated_data = Role::validate($request, $role);
        $role->update($validated_data);
        $role->syncPermissions($input['permissions']);

        return ['message' => 'Role was updated successfully', 'data' => $role];
    }

    public function destroy(Request $request, Role $role)
    {
        $role->delete();

        return ['message' => 'Role was deleted successfully'];
    }
}
