<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionVariable extends Model
{
    use HasFactory;
    use BaseModel;

    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'description',
        'value',
    ];

    public function relation()
    {
        return $this->morphTo();
    }

    public static function getValidationRules(array $values, self $model = null)
    {
        return [];
    }
}
