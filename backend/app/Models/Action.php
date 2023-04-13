<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    use HasFactory;
    use BaseModel;

    protected $with = [];

    protected $fillable = [
        'name',
        'active',
    ];

    public $search_conditions = [
        'id' => 'equals',
        'name' => 'contains',
    ];

    public function workflowNodes()
    {
        return $this->belongsToMany(WorkflowNode::class)->withTimestamps();
    }

    public function variables()
    {
        return $this->morphMany(ActionVariable::class, 'relation');
    }

    public function action_variables()
    {
        return $this->morphMany(ActionVariable::class, 'relation')->where('is_action_variable', true);
    }

    public function workflow_node_variables()
    {
        return $this->morphMany(ActionVariable::class, 'relation')->where('is_workflow_node_variable', true);
    }

    public static function getValidationRules(array $values, self $model = null)
    {
        return [
            'name' => 'required|string|max:255',
            'active' => 'required|boolean',
        ];
    }
}
