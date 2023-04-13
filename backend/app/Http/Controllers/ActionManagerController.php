<?php

namespace App\Http\Controllers;

use App\Models\Action;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

/**
 * this class is meant to manage Actions for Status or as independently as possible.
 */
class ActionManagerController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/actions/",
     *     summary="get list of all actions for a workflow",
     *     tags={"Workflows"},
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     */
    public function index(Request $request)
    {
        return QueryBuilder::for(Action::class)
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::partial('name'),
                AllowedFilter::partial('type'),
            ])
            ->allowedSorts(['id', 'name', 'type'])
            ->jsonPaginate()
        ;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/actions/{id}",
     *     summary="get an action",
     *     tags={"Workflows"},
     *     @OA\Parameter(
     *         description="Action ID",
     *         in="path",
     *         name="id",
     *         required=true, @OA\Schema(
     *             type="number"
     *         ),
     *         @OA\Examples(
     *             example="int",
     *             value="1",
     *             summary="An int value."
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     * )
     */
    public function show(Action $action)
    {
        $action->load('action_variables');
        $action->load('workflow_node_variables');

        return $action;
    }

    /**
     * @OA\Put(
     *     path="/api/v1/actions/{id}",
     *     summary="update action",
     *     tags={"Workflows"},
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
     * )
     */
    public function update(Request $request, Action $action)
    {
        $action->load('action_variables');
        $action->fill($request->all());
        $action->save();
        foreach ($request->get('action_variables', []) as $k => $v) {
            $var = $action->action_variables->where('name', $v['name'])->where('is_action_variable', true)->first();
            if ($var) {
                $var->fill(['value' => $v['value']]);
                $var->save();
            }
        }
        $action->load('action_variables');

        return ['status' => 'OK', 'message' => 'Lead Action updated successfully', 'data' => $action];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }
}
