<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadActionHistory extends Model
{
    use HasFactory;
    use BaseModel;

    protected $with = [];
    protected $table = 'lead_action_histories';

    protected $fillable = [
        'lead_id',
        'values',
        'status',
        'changed_status_to',
        'action',
    ];

    public function lead()
    {
        return $this->hasOne(Lead::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public static function getValidationRules(array $values, self $model = null)
    {
        return [];
    }
}
