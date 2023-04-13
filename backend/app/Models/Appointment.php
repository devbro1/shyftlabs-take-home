<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Appointment extends Model implements Auditable
{
    /**
     * @OA\Schema(
     *     schema="Appointment",
     *     title="Schema reference for an appointment",
     *     @OA\Property(
     *         property="id",
     *         example="1",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Property(
     *         property="owner_id",
     *         description="who's appointment it is",
     *         example="1",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Property(
     *         property="created_by",
     *         example="1",
     *         description="who created this appointment",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Property(
     *         property="created_at",
     *         example="",
     *         description="",
     *         type="string",
     *     ),
     *     @OA\Property(
     *         property="updated_at",
     *         example="",
     *         description="",
     *         type="string",
     *     ),
     *     @OA\Property(
     *         property="dt_start",
     *         example="",
     *         description="",
     *         type="string",
     *     ),
     *     @OA\Property(
     *         property="dt_end",
     *         example="",
     *         description="",
     *         type="string",
     *     ),
     *     @OA\Property(
     *         property="services",
     *         example="[1,2,3]",
     *         description="",
     *         type="string",
     *     ),
     *     @OA\Property(
     *         property="stores",
     *         example="[1,2,3]",
     *         description="",
     *         type="string",
     *     ),
     *     @OA\Property(
     *         property="lead_id",
     *         example="",
     *         description="",
     *         type="string",
     *     ),
     * )
     */
    use HasFactory;
    use BaseModel;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'created_by',
        'owner_id',
        'dt_start',
        'dt_end',
        'services',
        'stores',
    ];

    public $search_conditions = [
        'id' => 'equals',
        'dt_start' => 'between',
        'owner_id' => 'equals',
        'lead_id' => 'equals',
    ];

    protected $casts = [
        'services' => 'array',
        'stores' => 'array',
    ];

    public static function getValidationRules($values, self $model = null)
    {
        return [
            'created_by' => ['required', 'numeric', 'exists:users,id'],
            'owner_id' => ['required', 'numeric', 'exists:users,id'],
            'services' => ['required', 'array'],
            'services.*' => ['required', 'numeric', 'exists:services,id'],
            'stores' => ['required', 'array'],
            'stores.*' => ['required', 'numeric', 'exists:stores,id'],
            'dt_start' => ['required'],
            'dt_end' => ['required'],
        ];
    }

    public function scopeCovers($query, $from, $to)
    {
        $query->whereBetween('dt_start', [$from, $to]);

        return $query;
    }

    public function scopeOn($query, $date)
    {
        $query->whereBetween('dt_start', ["{$date} 00:00:00", "{$date} 23:59:59"]);

        return $query;
    }

    public function scopeAvailable($query)
    {
        return $query->whereNull('lead_id');
    }
}
