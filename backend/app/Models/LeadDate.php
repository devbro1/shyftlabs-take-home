<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadDate extends Model
{
    use HasFactory;
    use BaseModel;

    public $timestamps = true;

    protected $fillable = [
        'lead_id',
        'dt',
        'date_type',
    ];

    public static function getValidationRules($values, self $model = null)
    {
        return [
            'lead_id' => ['required', 'numeric', 'exists:leads,id'],
            'dt' => ['required', 'min:3', 'max:255'],
            'date_type' => ['required', 'min:3', 'max:255'],
        ];
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}
