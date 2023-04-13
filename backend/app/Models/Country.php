<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    use BaseModel;

    protected $primaryKey = 'code';
    public $timestamps = false;

    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['provinces'];

    public function provinces()
    {
        return $this->hasMany(Province::class, 'country_code', 'code');
    }

    public static function getValidationRules(array $values, self $model = null)
    {
        return [
        ];
    }
}
