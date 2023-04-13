<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;
    use BaseModel;

    public static function getValidationRules(array $values, self $model = null)
    {
        return [];
    }
}
