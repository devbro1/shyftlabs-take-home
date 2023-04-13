<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;
    use BaseModel;

    protected $fillable = [
        'active',
        'description',
        'body',
    ];

    public static function getValidationRules($values, self $model = null)
    {
        return [
            'name' => ['required'],
            'description' => [],
            'active' => [],
        ];
    }
}
