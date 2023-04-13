<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Laravel\Scout\Searchable;
use App\Models\Scopes\LeadScope;

/**
 * @OA\Schema(
 *     schema="Lead",
 *     title="Schema reference for lead",
 *     @OA\Property(
 *         property="source",
 *         example="",
 *         type="string",
 *
 *     ),
 *     @OA\Property(
 *         property="owner_id",
 *         example="",
 *         description="",
 *         type="string",
 *
 *     ),
 *     @OA\Property(
 *         property="notes",
 *         example="",
 *         description="note cannot be set as part of direct lead update",
 *         type="string",
 *
 *     )
 * )
 */
class Lead extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use Searchable;
    use BaseModel;

    // addition of timestamp fields created_at and updated_at
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'source',
        'store_id',
        'stale',
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['customer', 'service', 'status', 'workflow'];

    protected static function booted()
    {
        static::addGlobalScope(new LeadScope());
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function status()
    {
        return $this->belongsTo(WorkflowNode::class, 'status_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function workflow()
    {
        return $this->belongsTo(Workflow::class, 'workflow_id');
    }

    public function history()
    {
        return $this->hasMany(LeadActionHistory::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function dates()
    {
        return $this->hasMany(LeadDate::class);
    }

    public function owners()
    {
        return $this->hasMany(LeadOwner::class);
    }

    public function main_owner()
    {
        return $this->hasMany(LeadOwner::class)->where('main_provider', 'true');
    }

    /**
     * Get the name of the index associated with the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'lead_index';
    }

    /**
     * Get the value used to index the model.
     *
     * @return mixed
     */
    public function getScoutKey()
    {
        return $this->id;
    }

    /**
     * Get the key name used to index the model.
     *
     * @return mixed
     */
    public function getScoutKeyName()
    {
        return 'id';
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return $this->toArray();
        // Customize the data array...
    }

    public static function getValidationRules($values, self $model = null)
    {
        $rc = [
            'source' => ['required'],
        ];

        if (request()->user()) {
            $rc['store_id'] = ['required'];
        }

        return $rc;
    }
}
