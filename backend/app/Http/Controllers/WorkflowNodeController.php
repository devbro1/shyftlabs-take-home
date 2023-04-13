<?php

namespace App\Http\Controllers;

use App\Models\WorkflowNode;
use App\Models\ActionWorkflowNode;
use Illuminate\Http\Request;
use App\Http\Resources\WorkflowNodeResource;

class WorkflowNodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(WorkflowNode $workflow_node)
    {
        $workflow_node->load('actions');
        WorkflowNodeResource::withoutWrapping();

        return new WorkflowNodeResource($workflow_node);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/workflow-nodes/{id}",
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
    public function update(Request $request, WorkflowNode $workflow_node)
    {
        $validatedData = WorkflowNode::validate($request->all(), $workflow_node);
        $fillable = $workflow_node->getFillable();

        foreach ($fillable as $fill) {
            $workflow_node->{$fill} = $validatedData[$fill] ?? null;
        }

        $actions = $request->get('actions', []);

        foreach ($actions as &$action) {
            $action['workflow_node_id'] = $workflow_node->id;

            foreach ($action as $k => $v) {
                $status_to_id = $action['status_to_id'] ?? null;
                if (!in_array($k, array_merge((new ActionWorkflowNode())->getFillable(), ['variables']))) {
                    unset($action[$k]);
                }

                if (empty($action['id'])) {
                    unset($action['id']);
                }

                if (!empty($status_to_id)) {
                    $action['status_to_id'] = $status_to_id;
                }
            }
        }

        $workflow_node->save();
        $workflow_node->actions()->sync($actions);
        $workflow_node->load('actions');
        $workflow_node->next_statuses = $workflow_node->getNextStatuses();

        return ['status' => 'OK', 'message' => 'Workflow Node was created successfully', 'data' => $workflow_node];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(WorkflowNode $workflow_node)
    {
    }
}
