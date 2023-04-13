<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\DB;

class LeadScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model)
    {
        if (request()->user()->can('View All Leads')) {
        } elseif (request()->user()->can('Service Lead')) {
            $builder->join(
                DB::raw('(SELECT DISTINCT lead_id, provider_id FROM lead_owners) as lead_owners'),
                'lead_owners.lead_id',
                '=',
                'leads.id'
            );
            $builder->where('lead_owners.provider_id', request()->user()->id);
        } else {
            $builder->where('id', -1);
        }
    }
}
