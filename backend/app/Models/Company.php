<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Company",
 *     title="Schema reference for Company",
 *     @OA\Property(
 *         property="active",
 *         example="",
 *         type="boolean",
 *
 *     ),
 *     @OA\Property(
 *         property="name",
 *         example="",
 *         description="Company name",
 *         type="string",
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
 *         property="phone",
 *         @OA\Schema(
 *             type="integer"
 *         ),
 *         example="1234567890",
 *
 *     ),
 *     @OA\Property(
 *         property="owner_id",
 *         type="integer",
 *         example="1",
 *         description="Valid user ID in the system",
 *
 *     ),
 *     @OA\Property(
 *         property="logo",
 *         type="integer",
 *         example="1",
 *         description="Valid File ID in the system",
 *
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         format="email",
 *         example="noemail@email.com",
 *         required={"true"}
 *     ),
 *     @OA\Property(
 *         property="postal_code",
 *         type="string",
 *         example="L3T1E8",
 *         description="Valid Postal Code or Zipcode in the system",
 *
 *     )
 * )
 */
class Company extends Model
{
    use HasFactory;
    use BaseModel;

    public static function getValidationRules(array $values, self $model = null)
    {
        $rc = [];
        $rc['name'] = ['required', 'string', 'max:255'];
        $rc['active'] = ['required', 'boolean'];
        $rc['address'] = [];
        $rc['city'] = [];
        $rc['province_code'] = ['required', 'exists:provinces,abbreviation'];
        $rc['country_code'] = ['required', 'exists:countries,code'];
        $rc['postal_code'] = ['required', 'exists:postal_codes,code'];
        $rc['phone1'] = ['required', 'regex:/^\+?1?[-\. ]?\(?([0-9]{0,3})\)?[-\. ]?([0-9]{0,3})[-\. ]?([0-9]{0,4})( x[0-9]*)?$/'];
        $rc['email'] = ['required', 'email'];
        $rc['website'] = [];
        $rc['logo_file_id'] = ['nullable', 'numeric', 'exists:files,id'];

        return $rc;
    }

    protected $fillable = [
        'name', 'active', 'address', 'city', 'province_code', 'country_code', 'zip', 'phone1', 'email', 'website', 'logo_file_id',
    ];

    public function owners()
    {
        return $this->belongsToMany(User::class)
            ->wherePivot('position', 'owner')
        ;
    }

    public function employees()
    {
        return $this->belongsToMany(User::class)
            ->wherePivot('position', 'employee')
        ;
    }
}
