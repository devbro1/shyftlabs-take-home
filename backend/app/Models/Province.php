<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;
    use BaseModel;

    public static function getValidationRules($values, self $model = null)
    {
        return [
            'name' => ['required'],
            'description' => [],
            'active' => [],
        ];
    }
}
