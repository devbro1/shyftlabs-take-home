<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Disorder extends Model implements Auditable
{
    use HasFactory;
    use BaseModel;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['name', 'category', 'used_for_code'];

    public function drugs()
    {
        return $this->belongsToMany(Drug::class)->withTimestamps();
    }

    public static function getValidationRules(array $values, self $model = null)
    {
        return [
            'name' => ['required', 'string'],
            'category' => ['required', 'string'],
            'used_for_code' => ['required', 'integer'],
        ];
    }
}
