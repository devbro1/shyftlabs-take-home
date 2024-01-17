<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

/**
 * @group Country
 */
class CountryController extends Controller
{
    public function index(Request $request)
    {
        return QueryBuilder::for(Country::class)
            ->allowedFilters([
                AllowedFilter::exact('code'),
                AllowedFilter::partial('name'),
            ])
            ->allowedSorts(['code', 'name'])
            ->jsonPaginate()
        ;
    }

    public function show(Country $country)
    {
        $country->load('provinces');

        return $country;
    }
}
