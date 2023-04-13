<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadOwner extends Model
{
    use HasFactory;
    use BaseModel;

    public $timestamps = false;

    protected $fillable = [
        'lead_id',
        'provider_id',
        'main_provider',
    ];

    protected $with = ['provider'];

    public static function getValidationRules($values, self $model = null)
    {
        return [
            'lead_id' => ['required', 'numeric', 'exists:leads,id'],
            'provider_id' => ['required', 'numeric', 'exists:users,id'],
            'main_provider' => ['nullable', 'boolean'],
        ];
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }
}
