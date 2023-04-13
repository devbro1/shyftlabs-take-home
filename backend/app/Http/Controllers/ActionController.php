<?php

namespace App\Http\Controllers;

use App\Models\Action;
use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\LeadActionHistory;
use App\Models\WorkflowNode;

/**
 * This class is meant to manage Actions per Lead.
 */
class ActionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Lead $lead)
    {
        $current_status = $lead->status;

        if ($current_status->actions->count() > 0) {
            $current_status->actions->load('action');

            $current_status->actions->filter(function ($a) {
                if (null == $a->action->permission_id) {
                    return true;
                }

                return user()->can($a->action->permission->name);
            });

            return $current_status->actions;
        }

        $rc = [];
        foreach ($current_status->source_edges as &$edge) {
            $edge->load('target_node');
        }

        return $current_status->source_edges;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Lead $lead)
    {
        $status_id = $request->input('status_id');
        if (!empty($lead->status->actions) || !$lead->status->canChangeTo($status_id)) {
            throw new \Exception('Direct Status Update Not allowed');
        }
        $old_lead_status = $lead->status;
        $lead->status()->associate(WorkflowNode::findOrFail($status_id));
        $lead->save();

        $la_history = new LeadActionHistory();
        $la_history->lead_id = $lead->id;
        $la_history->status_id = $old_lead_status->id;
        $la_history->user_id = request()->user()->id ?? null;
        $la_history->short_message = 'Direct Status change from '.$old_lead_status->label.' to '.$lead->status->label;
        $la_history->values = json_encode(['type' => 'direct status change', 'new_status' => $status_id]);
        $la_history->save();

        return ['message' => 'Lead status was updated successfully', 'data' => []];
    }

    /**
     * Display the specified resource.
     *
     * @param mixed $id
     * @param mixed $action_id
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Lead $lead, $action_id)
    {
        $rc = null;
        $current_status = $lead->status;

        if ($current_status->actions->count() > 0) {
            $current_status->actions->load('action');

            $rc = $current_status->actions->where('id', $action_id)->first();
        }

        return $rc ?? response(['message' => 'not found'], 404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Action $action)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param mixed $action_id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lead $lead, $action_id)
    {
        $leadAction = $lead->status->actions->where('id', $action_id)->first();
        "{$leadAction->action->action_class}"::run($lead, $leadAction, $request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Action $action)
    {
    }
}
