<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $fillable = [
        'key',
        'total',
        'lead_id',
        'items',
    ];

    protected $casts = [
        'items' => 'array',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}
