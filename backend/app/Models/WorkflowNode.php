<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkflowNode extends Model
{
    use HasFactory;
    use BaseModel;

    protected $fillable = [
        'workflow_id',
        'label',
        'position_x',
        'position_y',
        'type',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public static function getValidationRules($values, self $model = null)
    {
        return [
            'id' => [],
            'workflow_id' => ['required'],
            'label' => ['required'],
            'type' => ['required'],
            'position_x' => [],
            'position_y' => [],
            'actions' => [],
        ];
    }

    public function workflow()
    {
        return $this->belongsTo(Workflow::class);
    }

    public function target_edges()
    {
        return $this->hasMany(WorkflowEdge::class, 'target_id');
    }

    public function source_edges()
    {
        return $this->hasMany(WorkflowEdge::class, 'source_id');
    }

    public function getNextStatuses()
    {
        return $this->source_edges->pluck('target_node');
    }

    public function getAllEdges()
    {
        throw new \Exception('Not implemented');
    }

    public function canChangeTo($node_id)
    {
        $options = $this->source_edges->where('target_id', $node_id);

        return (bool) $options->count();
    }

    public function actions()
    {
        return $this->hasMany(ActionWorkflowNode::class);
    }
}
