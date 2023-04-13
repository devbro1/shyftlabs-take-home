<?php

namespace App\Models;

use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role as SRole;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @OA\Schema(
 *     schema="Role",
 *     title="Schema reference for Role",
 *     @OA\Property(
 *         property="name",
 *         example="",
 *         type="string",
 *     ),
 *     @OA\Property(
 *         property="description",
 *         example="",
 *         type="string",
 *     ),
 *     @OA\Property(
 *         property="permissions",
 *         example={"perm1", "perm2", "perm3"},
 *         type="array",
 *         @OA\Items
 *     ),
 * )
 */
class Role extends SRole implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use BaseModel;

    protected $fillable = [
        'name', 'description',
    ];
    protected $with = [];

    protected $attributes = [
        'guard_name' => 'api',
    ];

    public $search_conditions = [
        'id' => 'equals',
        'name' => 'startsWith',
        'description' => 'contains',
    ];

    public static function getValidationRules($values, self $model = null)
    {
        $rc = [
            'name' => ['required', 'min:3', 'max:255'],
            'description' => ['required', 'min:3', 'max:255'],
        ];

        if ($model) {
            $rc['name'][] = Rule::unique('roles', 'name')->ignore($model->id);
        } else {
            $rc['name'][] = Rule::unique('roles', 'name');
        }

        return $rc;
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
}
