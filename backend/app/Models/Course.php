<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    use BaseModel;

    protected $fillable = [
        'name',
    ];


    public static function getValidationRules(array $values, self $model = null)
    {
        return [
            'name' => ['required','min:2','max:255'],
        ];
    }
}
