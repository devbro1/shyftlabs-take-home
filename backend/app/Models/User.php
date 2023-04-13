<?php

namespace App\Models;

use App\Notifications\PasswordResetRequest;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Validation\Rule;
use Laravel\Passport\HasApiTokens;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Redactors\RightRedactor;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Exceptions\UnauthorizedException;
use LVR\Phone\Phone;

/**
 * @OA\Schema(
 *     schema="User",
 *     title="Schema reference for User",
 *     @OA\Property(
 *         property="active",
 *         example="",
 *         type="boolean",
 *
 *     ),
 *     @OA\Property(
 *         property="username",
 *         example="",
 *         description="",
 *         type="string",
 *
 *     ),
 *     @OA\Property(
 *         property="full_name",
 *         example="",
 *         description="",
 *         type="string",
 *
 *     ),
 *     @OA\Property(
 *         property="email",
 *         example="",
 *         description="",
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
 *         property="country_code",
 *         example="",
 *         description="",
 *         type="string",
 *
 *     ),
 *     @OA\Property(
 *         property="phone1",
 *         example="",
 *         description="",
 *         type="string",
 *
 *     ),
 *     @OA\Property(
 *         property="phone2",
 *         example="",
 *         description="",
 *         type="string",
 *
 *     ),
 *     @OA\Property(
 *         property="roles",
 *         example="",
 *         description="",
 *         type="string",
 *
 *     ),
 *     @OA\Property(
 *         property="permissions",
 *         example="",
 *         description="",
 *         type="string",
 *
 *     )
 * )
 */
class User extends Authenticatable implements Auditable, Authorizable, CanResetPassword, MustVerifyEmail
{
    use Notifiable;
    use HasFactory;
    use HasApiTokens;
    use \OwenIt\Auditing\Auditable;
    use HasRoles; // spatie laravel-permission
    use HasPermissions; // spatie laravel-permission
    use BaseModel;

    // addition of timestamp fields created_at and updated_at
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'full_name',
        'email',
        'active',
        'address',
        'city',
        'postal_code',
        'province_code',
        'country_code',
        'phone1',
        'phone2',
        'roles',
        'email_verified_at',
    ];

    public $search_conditions = [
        'username' => 'startsWith',
        'id' => 'equals',
        'city' => 'contains',
        'active' => 'equals',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'active' => true,
        'country_code' => 'CA',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime:Y-m-d',
        'active' => 'boolean',
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    // protected $with = ['roles', 'permissions'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['all_permissions'];

    protected $attributeModifiers = [
        'password' => RightRedactor::class,
        'remember_token' => RightRedactor::class,
    ];

    public function findForPassport($username)
    {
        return $this->where('username', $username)->first() ?? $this->where('email', $username)->first();
    }

    public static function getValidationRules($values, self $model = null)
    {
        $rules = [
            'username' => ['required'],
            'full_name' => ['required'],
            'email' => ['required', 'email'],
            'active' => [],
            'address' => [],
            'city' => [],
            'postal_code' => [],
            'province_code' => ['nullable', 'exists:provinces,abbreviation'],
            'country_code' => ['nullable', 'exists:countries,code'],
            'phone1' => ['nullable', new Phone()],
            'phone2' => ['nullable', new Phone()],
            'roles' => [],
            'permissions' => [],
            'email_verified_at' => [],
        ];

        $rules['username']['unique'] = Rule::unique('users', 'username');
        $rules['email']['unique'] = Rule::unique('users', 'email');

        if (!empty($values['id']) && $id = (int) $values['id']) {
            $rules['username']['unique'] = $rules['username']['unique']->ignore($id);
            $rules['email']['unique'] = $rules['email']['unique']->ignore($id);
        }

        if (null === request()->user()) {
            $rules['password'] = ['required', 'min:8', 'max:32', 'regex:/^[\w\d\\/\$\@\-\_\!\?\(\)\+\*\#\&]*$/'];
        }

        return $rules;
    }

    protected $fullAddress = '';

    public function getFullAddressAttribute($value)
    {
        return $fullAddress = $this->address.' '.$this->province_code.' '.$this->city.' '.$this->postal_code.' '.$this->country_code;
    }

    public function getAvailableRolesAttribute()
    {
        return Role::all();
    }

    public function setRolesAttribute($value)
    {
        $this->syncRoles($value);
    }

    public function getAllPermissionsAttribute()
    {
        $rc = [];
        $perms = $this->getAllPermissions();
        foreach ($perms as $perm) {
            $rc[] = $perm->name;
        }

        return $rc;
    }

    public function getAvailablePermissionsAttribute()
    {
        return Permission::all();
    }

    public function save(array $options = [])
    {
        parent::save($options);
        $this->assignRole($this->getRoleNames());
    }

    /**
     * {@inheritdoc}
     */
    public function transformAudit(array $data): array
    {
        // in case need to transform audit
        return $data;
    }

    public function isAllowedAll($roles_and_perms)
    {
        return $this->isAllowed($roles_and_perms);
    }

    public function isAllowed($roles_and_perms)
    {
        if (is_array($roles_and_perms)) {
            foreach ($roles_and_perms as $v) {
                $rc = $this->isAllowed($v);
                if (!$rc) {
                    return false;
                }
            }

            return true;
        }

        if (count(Role::where('name', $roles_and_perms)->get()) > 0) {
            return $this->hasRole($roles_and_perms);
        }
        if (count(Permission::where('name', $roles_and_perms)->get()) > 0) {
            return $this->hasPermissionTo($roles_and_perms);
        }

        return false;
    }

    public function isAllowedAny($roles_and_perms)
    {
        if (is_array($roles_and_perms)) {
            foreach ($roles_and_perms as $v) {
                $rc = $this->isAllowed($v);
                if ($rc) {
                    return true;
                }
            }

            return false;
        }

        return $this->hasRole($roles_and_perms) || $this->hasPermissionTo($roles_and_perms);
    }

    public function forceAllow($roles_and_perms, array $gate_context = [])
    {
        if (!$this->isAllowed($roles_and_perms)) {
            // throw UnauthorizedException::forRolesOrPermissions($roles_and_perms);
            throw new UnauthorizedException(403);
        }

        if (!is_array($roles_and_perms)) {
            $roles_and_perms = [$roles_and_perms];
        }

        $gates = Gate::abilities();

        foreach ($roles_and_perms as $p) {
            if (in_array($p, $gates)) {
                Gate::authorize($p, $gate_context);
            }
        }
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordResetRequest($token));
    }

    public function setActiveAttribute($val)
    {
        if ('boolean' === gettype($val)) {
            $val = $val;
        } elseif ('false' === strtolower($val)) {
            $val = false;
        } elseif ('off' === strtolower($val)) {
            $val = false;
        } elseif ('no' === strtolower($val)) {
            $val = false;
        } elseif ('negative' === strtolower($val)) {
            $val = false;
        } else {
            $val = (bool) !empty($val);
        }

        $this->attributes['active'] = $val;
    }

    public function scopeActive($query, $active = true)
    {
        return $query->where('active', $active);
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class);
    }
}
