<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;
    use BaseModel;

    protected $fillable = [
        'title',
        'body',
    ];

    public static function getValidationRules(array $values, self $model = null)
    {
        return [
            'title' => ['required'],
            'body' => ['required'],
        ];
    }
}
