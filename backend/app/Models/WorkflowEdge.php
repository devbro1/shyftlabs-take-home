<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkflowEdge extends Model
{
    use HasFactory;
    use BaseModel;

    protected $attributes = [
        'name' => '',
    ];

    protected $fillable = [
        'source_id',
        'target_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function work_flow()
    {
        return $this->belongsTo(Workflow::class);
    }

    public function source_node()
    {
        return $this->belongsTo(WorkflowNode::class, 'source_id');
    }

    public function target_node()
    {
        return $this->belongsTo(WorkflowNode::class, 'target_id');
    }

    public static function getValidationRules($values, self $model = null)
    {
        return [
            'name' => ['required', 'string', 'max:255'],
        ];
    }
}
