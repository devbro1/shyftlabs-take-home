<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Workflow",
 *     title="Schema reference for workflow",
 *     @OA\Property(
 *         property="active",
 *         example="",
 *         type="boolean",
 *
 *     ),
 *     @OA\Property(
 *         property="description",
 *         example="",
 *         description="",
 *         type="string",
 *
 *     )
 * )
 */
class Workflow extends Model
{
    use HasFactory;
    use BaseModel;

    protected $attributes = [
        'active' => true,
        'description' => '',
    ];

    protected $fillable = [
        'name',
        'description',
        'active',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    // protected $with = ['edges','nodes'];

    public static function getValidationRules($values, self $model = null)
    {
        return [
            'name' => ['required'],
            'description' => [],
            'active' => [],
        ];
    }

    public function edges()
    {
        return $this->hasMany(WorkflowEdge::class);
    }

    public function nodes()
    {
        return $this->hasMany(WorkflowNode::class);
    }

    public function getStartNode()
    {
        return $this->nodes()->where('type', 'EditableNodeInput')->first();
    }
}
