<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceAvailability extends Model
{
    use HasFactory;
    use BaseModel;

    public $search_conditions = [
        'id' => 'equals',
        'store_id' => 'equals',
        'service_id' => 'equals',
        'workflow_id' => 'equals',
        'company_id' => 'equals',
    ];

    public static function getValidationRules(array $values, self $model = null)
    {
        $rc = [];
        $fields = ['store' => 'store', 'company' => 'company', 'service' => 'service', 'workflow' => 'workflow'];

        foreach ($fields as $k => $field) {
            $rc[$field.'_id'] = ['required'];
            if (is_array($values[$field.'_id'])) {
                $rc[$field.'_id.*'] = ['required', 'numeric', 'exists:'.str($k)->plural()->toString().',id'];
            } else {
                $rc[$field.'_id'][] = 'numeric';
                $rc[$field.'_id'][] = 'exists:'.str($k)->plural()->toString().',id';
            }
        }

        return $rc;
    }

    public static function findClosestStore($params)
    {
        $coord = null;
        if (!empty($params['postal_code'])) {
            $postal_code = $params['postal_code'];
            $coord = ['longitude' => $postal_code->longitude, 'latitude' => $postal_code->latitude];
        } elseif (empty($coord) && !empty($params['longitude']) && !empty($params['latitude'])) {
            $coord = ['longitude' => $params['longitude'], 'latitude' => $params['latitude']];
        } else {
            throw new \App\Exceptions\ParameterNotFoundException('missing param: need postal_code OR longitude+latitude');
        }

        $query = ServiceAvailability::query()
            ->select('service_availabilities.*')
            ->selectRaw('(point(COALESCE(stores.longitude,postal_codes.longitude),COALESCE(stores.latitude,postal_codes.latitude)) <@> point(?,?)) as distance', [$coord['longitude'], $coord['latitude']])
            ->join('stores', 'service_availabilities.store_id', '=', 'stores.id')
            ->join('postal_codes', 'stores.postal_code', '=', 'postal_codes.code')
            ->join('services', 'services.id', '=', 'service_availabilities.service_id')
            ->join('companies', 'companies.id', '=', 'service_availabilities.company_id')
            ->where('companies.active', true)
            ->where('services.active', true)
            ->where('stores.active', true)
            ->where('services.id', $params['service_id'])
            ->orderBy('distance', 'ASC')
            ->limit(10)
        ;

        if (request()->user() && request()->user()->can('Service Lead')) {
            $companies = request()->user()->companies()->pluck('companies.id')->toArray();
            $query = $query->whereIn('service_availabilities.company_id', $companies);
        } else {
            $query = $query->whereRaw('"stores"."coverage_radius" >= (point(COALESCE(stores.longitude,postal_codes.longitude),COALESCE(stores.latitude,postal_codes.latitude)) <@> point(?,?))', [$coord['longitude'], $coord['latitude']]);
        }

        if (!empty($params['store_id'])) {
            $query = $query->where('stores.id', '=', $params['store_id']);
        }

        return $query->first();
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
