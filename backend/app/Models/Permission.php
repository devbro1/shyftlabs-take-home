<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission as SpatiePermission;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @OA\Schema(
 *     schema="Permission",
 *     title="Schema reference for Permission",
 *     @OA\Property(
 *         property="name",
 *         example="",
 *         type="string",
 *
 *     ),
 *     @OA\Property(
 *         property="description",
 *         example="",
 *         type="string",
 *
 *     )
 * )
 */
class Permission extends SpatiePermission implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    use BaseModel;

    protected $fillable = [
        'name', 'description',
    ];

    protected $attributes = [
        'guard_name' => 'api',
    ];

    public $search_conditions = [
        'id' => 'equals',
        'name' => 'startsWith',
        'description' => 'contains',
    ];

    public static function getValidationRules(array $values, self $model = null)
    {
        return [
            'name' => ['required'],
            'description' => ['required'],
        ];
    }
}
