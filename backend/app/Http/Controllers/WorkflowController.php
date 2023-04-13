<?php

namespace App\Http\Controllers;

use App\Models\Workflow;
use App\Models\WorkflowNode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class WorkflowController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/workflows/",
     *     summary="list all workflows",
     *     tags={"Workflows"},
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     */
    public function index(Request $request)
    {
        return QueryBuilder::for(Workflow::class)
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
     *     path="/api/v1/workflows",
     *     summary="Create new workflow",
     *     tags={"Workflows"},
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $workflow = new Workflow();
        $fillable = $workflow->getFillable();
        $validatedData = Workflow::validate($request);

        foreach ($fillable as $fill) {
            if (isset($validatedData[$fill])) {
                $workflow->{$fill} = $validatedData[$fill] ?? null;
            }
        }

        $workflow->save();

        return ['data' => $workflow, 'message' => 'Workflow created successfully'];
    }

    /**
     * @OA\Get(
     *     path="/api/v1/workflows/{id}",
     *     summary="get a workflow",
     *     tags={"Workflows"},
     *     @OA\Parameter(
     *         description="workflow ID",
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
    public function show(Workflow $workflow)
    {
        $workflow->load('nodes');
        $workflow->load('edges');

        return $workflow;
    }

    /**
     * @OA\Put(
     *     path="/api/v1/workflows/{id}",
     *     summary="update workflow",
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
    public function update(Request $request, Workflow $workflow)
    {
        $fillable = $workflow->getFillable();
        $workflow->load('nodes');
        $workflow->load('edges');

        $validatedData = Workflow::validate($request);

        foreach ($fillable as $fill) {
            if (isset($validatedData[$fill])) {
                $workflow->{$fill} = $validatedData[$fill] ?? null;
            }
        }

        $nodes = $request->get('nodes', []);
        $edges = $request->get('edges', []);
        $renaming_nodes = [];

        foreach ($nodes as &$node) {
            $node['position_x'] = $node['position']['x'];
            $node['position_y'] = $node['position']['y'];
            $node['workflow_id'] = $workflow->id;
            $node['label'] = $node['data']['label'];
            unset($node['data'], $node['position']);

            if (!is_numeric($node['id']) || !$workflow->nodes()->find($node['id'])) {
                $nodew = $node;
                unset($nodew['id']);
                $new_node = WorkflowNode::create($nodew);
                $renaming_nodes[$node['id']] = $new_node->id;
                $node['id'] = $new_node->id;
            }
        }

        foreach ($renaming_nodes as $key => $value) {
            foreach ($edges as &$edge) {
                foreach (['source', 'target'] as $m) {
                    if ($edge[$m] === $key) {
                        $edge[$m] = $value;
                    }
                }
            }
        }

        foreach ($edges as &$edge) {
            if (!is_numeric($edge['id'])) {
                unset($edge['id']);
            }

            $edge['source_id'] = $edge['source'];
            $edge['target_id'] = $edge['target'];

            unset($edge['source'], $edge['target']);
        }

        try {
            DB::beginTransaction();
            $workflow->save();
            $workflow->nodes()->sync($nodes);
            $workflow->edges()->sync($edges);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            throw $e;
        }

        $workflow->load('nodes');
        $workflow->load('edges');

        return ['data' => $workflow, 'message' => 'Workflow was updated successfully'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Workflow $workflow)
    {
    }
}
