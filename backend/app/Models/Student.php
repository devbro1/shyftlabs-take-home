<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Student extends Model
{
    use HasFactory;
    use BaseModel;

    protected $fillable = [
        'first_name',
        'family_name',
        'email',
        'date_of_birth',
    ];


    public static function getValidationRules(array $values, self $model = null)
    {
        $ten_years_ago = Carbon::now()->subYear(10);
        return [
            'first_name' => ['required','min:2','max:255'],
            'family_name' => ['required','min:2','max:255'],
            'email' => ['required','email'],
            'date_of_birth' => ['required','date','before:'. $ten_years_ago->format('Y-m-d')],
        ];
    }
}
