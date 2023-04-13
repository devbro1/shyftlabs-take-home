<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    use HasFactory;
    use BaseModel;

    protected $fillable = [
        'language', 'key', 'translation', 'namespace',
    ];

    public static function getValidationRules($values, self $model = null)
    {
        return [
            'key' => ['required', 'string'],
            'translation' => ['required', 'string'],
            'language' => ['required', 'string', 'max:2'],
            'namespace' => ['required', 'string'],
        ];
    }
}
