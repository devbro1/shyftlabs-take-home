<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Schema(
 *     schema="Service",
 *     title="Schema reference for service",
 *     @OA\Property(
 *         property="active",
 *         example="",
 *         type="boolean",
 *
 *     ),
 *     @OA\Property(
 *         property="name",
 *         example="",
 *         description="",
 *         type="string",
 *
 *     )
 * )
 */
class Service extends Model implements Auditable
{
    use HasFactory;
    use BaseModel;
    use \OwenIt\Auditing\Auditable;

    // addition of timestamp fields created_at and updated_at
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'active',
        'name',
    ];

    public $search_conditions = [
        'id' => 'equals',
        'name' => 'contains',
    ];

    public static function getValidationRules($values, self $model = null)
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'active' => ['required', 'boolean'],
        ];
    }

    public function scopeAvailable($query)
    {
        $service_ids = DB::table('service_availabilities')->select('service_id')->distinct()->pluck('service_id')->toArray();

        return $query->whereIn('id', $service_ids);
    }
}
