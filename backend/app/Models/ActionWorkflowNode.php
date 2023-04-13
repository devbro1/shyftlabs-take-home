<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Jobs\CheckForSchedulableLeadActionsJob;

class ActionWorkflowNode extends Model
{
    use HasFactory;
    use BaseModel;

    public $incrementing = true;
    public $timestamps = true;
    protected $table = 'action_workflow_node';
    protected $variables = [];

    protected $fillable = [
        'id',
        'workflow_node_id',
        'action_id',
        'alternative_name',
        'status_to_id',
        'variables',
        'permission_id',
    ];

    public function action()
    {
        return $this->hasOne(Action::class, 'id', 'action_id');
    }

    public function permission()
    {
        return $this->hasOne(Permission::class, 'id', 'permission_id');
    }

    public function workflow_node()
    {
        return $this->hasOne(WorkflowNode::class, 'id', 'workflow_node_id');
    }

    public function status_to()
    {
        return $this->hasOne(WorkflowNode::class, 'id', 'status_to_id');
    }

    public function postSuccessProcess(Lead $lead, $short_message = '', $details)
    {
        $old_lead_status = $lead->status->label;
        if ($this->status_to) {
            $lead->status_id = $this->status_to->id;
            $lead->stale = false;
            CheckForSchedulableLeadActionsJob::dispatch($lead);
            $lead->save();
            $lead->load('status');
        }

        $la_history = new LeadActionHistory();
        $la_history->short_message = $short_message;
        $la_history->lead_id = $lead->id;
        $la_history->status = $old_lead_status;
        $la_history->changed_status_to = $lead->status->label;
        $la_history->user_id = request()->user()->id ?? null;
        $la_history->action = $this->alternative_name;
        $la_history->values = json_encode($details);
        $la_history->save();
    }

    public function variables()
    {
        return $this->morphMany(ActionVariable::class, 'relation');
    }

    public function getVariablesAttribute($value)
    {
        $action_vars = $this->action->action_variables->pluck('value', 'name')->toArray();

        return array_merge($action_vars, json_decode($value, true));
    }

    public function setVariablesAttribute($vars)
    {
        $allowed_keys = [];
        if (!empty($this->attributes['action_id'])) {
            $la = Action::find($this->attributes['action_id']);
            $la->load('workflow_node_variables');
            $allowed_keys = $la->workflow_node_variables()->pluck('name')->all();
        } else {
            $allowed_keys = array_keys($vars);
        }

        foreach ($vars as $k => $v) {
            if (!in_array($k, $allowed_keys)) {
                unset($vars[$k]);
            }
        }
        $this->attributes['variables'] = json_encode($vars);
    }

    public static function getValidationRules(array $values, self $model = null)
    {
        return [];
    }
}
