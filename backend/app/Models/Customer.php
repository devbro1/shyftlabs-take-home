<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @OA\Schema(
 *     schema="Customer",
 *     title="Schema reference for customer",
 *     @OA\Property(
 *         property="first_name",
 *         example="",
 *         type="string",
 *         description="",
 *
 *     ),
 *     @OA\Property(
 *         property="last_name",
 *         example="",
 *         type="string",
 *         description="",
 *
 *     ),
 *     @OA\Property(
 *         property="email",
 *         example="",
 *         type="string",
 *         description="",
 *
 *     ),
 *     @OA\Property(
 *         property="phone1",
 *         example="",
 *         type="string",
 *         description="",
 *
 *     ),
 *     @OA\Property(
 *         property="phone2",
 *         example="",
 *         type="string",
 *         description="",
 *
 *     ),
 *     @OA\Property(
 *         property="address",
 *         example="",
 *         type="string",
 *         description="",
 *
 *     ),
 *     @OA\Property(
 *         property="city",
 *         example="",
 *         type="string",
 *         description="",
 *
 *     ),
 *     @OA\Property(
 *         property="postal_code",
 *         example="L3T1E8",
 *         type="string",
 *         description="Valid Postal Code or Zipcode in the system",
 *
 *     ),
 *     @OA\Property(
 *         property="province_code",
 *         example="ON",
 *         type="string",
 *         description="Valid 2 letter Province or state code",
 *
 *     ),
 *     @OA\Property(
 *         property="country_code",
 *         example="CA",
 *         type="string",
 *         description="Valid 2 letter country code",
 *
 *     )
 * )
 */
class Customer extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use BaseModel;

    // addition of timestamp fields created_at and updated_at
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'address',
        'city',
        'postal_code',
        'province_code',
        'country_code',
        'phone1',
        'phone2',
    ];

    public static function getValidationRules($values, self $model = null)
    {
        return [
            'first_name' => ['required'],
            'last_name' => ['required'],
            'email' => ['required', 'email'],
            'address' => ['required'],
            'city' => ['required'],
            'postal_code' => ['required', 'exists:postal_codes,code'],
            'province_code' => ['required', 'exists:provinces,abbreviation'],
            'country_code' => ['required', 'exists:countries,code'],
            'phone1' => ['required', 'regex:/^\+?1?[-\. ]?\(?([0-9]{0,3})\)?[-\. ]?([0-9]{0,3})[-\. ]?([0-9]{0,4})( x[0-9]*)?$/'],
            'phone2' => ['nullable', 'regex:/^\+?1?[-\. ]?\(?([0-9]{0,3})\)?[-\. ]?([0-9]{0,3})[-\. ]?([0-9]{0,4})( x[0-9]*)?$/'],
        ];
    }
}
