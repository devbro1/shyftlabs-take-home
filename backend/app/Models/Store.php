<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Store",
 *     title="Schema reference for store",
 *     @OA\Property(
 *         property="active",
 *         example="",
 *         type="boolean",
 *
 *     ),
 *     @OA\Property(
 *         property="store_no",
 *         example="",
 *         description="Store Number",
 *         type="string",
 *
 *     ),
 *     @OA\Property(
 *         property="name",
 *         example="",
 *         description="Store Name",
 *         type="string",
 *
 *     ),
 *     @OA\Property(
 *         property="address",
 *         example="",
 *         description="",
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
 *         property="postal_code",
 *         type="string",
 *         example="L3T1E8",
 *         description="Valid Postal Code or Zipcode in the system",
 *
 *     ),
 *     @OA\Property(
 *         property="province_code",
 *         type="string",
 *         example="ON",
 *         description="Valid 2 letter Province or state code",
 *
 *     ),
 *     @OA\Property(
 *         property="country_code",
 *         type="string",
 *         example="CA",
 *         description="Valid 2 letter country code",
 *
 *     ),
 *     @OA\Property(
 *         property="longitude",
 *         type="number",
 *         format="float",
 *         example="-79.3832",
 *         description="",
 *
 *     ),
 *     @OA\Property(
 *         property="latitude",
 *         type="integer",
 *         format="float",
 *         example="43.6532",
 *         description="",
 *     ),
 *     @OA\Property(
 *         property="coverage_radius",
 *         type="integer",
 *         format="float",
 *         example="100",
 *         description="the radius of which this store covers for services",
 *     )
 * )
 */
class Store extends Model
{
    use HasFactory;
    use BaseModel;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'active',
        'store_no',
        'name',

        'address',
        'city',
        'province_code',
        'country_code',
        'postal_code',

        'longitude',
        'latitude',
        'coverage_radius',
    ];

    public $search_conditions = [
        'id' => 'equals',
        'name' => 'contains',
    ];

    public static function getValidationRules(array $values, self $model = null)
    {
        return [
            'active' => ['required', 'boolean'],
            'store_no' => ['required', 'numeric'],
            'name' => ['required'],
            'address' => ['required'],
            'city' => ['required'],
            'postal_code' => ['required'],
            'province_code' => ['required', 'exists:provinces,abbreviation'],
            'country_code' => ['required', 'exists:countries,code'],
            'longitude' => ['nullable', 'required_with:latitude', 'numeric'],
            'latitude' => ['nullable', 'required_with:longitude', 'numeric'],
            'coverage_radius' => ['required', 'numeric'],
        ];
    }

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

    public static function findClosestStore($params)
    {
        $coord = null;
        if (!empty($params['postal_code'])) {
            $postal_code = $params['postal_code'];
            $coord = ['longitude' => $postal_code->longitude, 'latitude' => $postal_code->latitude];
        }

        if (empty($coord) && !empty($params['longitude']) && !empty($params['latitude'])) {
            $coord = ['longitude' => $params['longitude'], 'latitude' => $params['latitude']];
        }

        $query = Store::query()
            ->join('postal_codes', 'stores.postal_code', '=', 'postal_codes.code')
            ->select('stores.*')
            ->selectRaw('(point(COALESCE(stores.longitude,postal_codes.longitude),COALESCE(stores.latitude,postal_codes.latitude)) <@> point(?,?)) as distance', [$coord['longitude'], $coord['latitude']])
            ->where('stores.active', true)
            ->orderBy('distance', 'ASC')
        ;

        return $query->first();
    }
}
