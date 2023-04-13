<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="PostalCode",
 *     title="Schema reference for postal code",
 *     @OA\Property(
 *         property="code",
 *         example="",
 *         type="string",
 *
 *     ),
 *     @OA\Property(
 *         property="city",
 *         example="",
 *         description="",
 *         type="string",
 *
 *     ),
 *     @OA\Property(
 *         property="time_zone_id",
 *         example="",
 *         description="",
 *         type="string",
 *
 *     ),
 *     @OA\Property(
 *         property="province_code",
 *         example="",
 *         description="",
 *         type="string",
 *
 *     ),
 *     @OA\Property(
 *         property="longitude",
 *         example="",
 *         description="",
 *         type="string",
 *
 *     ),
 *     @OA\Property(
 *         property="latitude",
 *         example="",
 *         description="",
 *         type="string",
 *
 *     )
 * )
 */
class PostalCode extends Model
{
    use HasFactory;
    use BaseModel;

    protected $primaryKey = 'code';
    protected $keyType = 'string';

    protected $fillable = [
        'code',
        'city',
        'province_code',
        'time_zone_id',
        'longitude',
        'latitude',
    ];

    public static function getValidationRules($values, self $model = null)
    {
        return [
        ];
    }
}
