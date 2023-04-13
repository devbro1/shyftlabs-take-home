<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class CountryController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/countries/",
     *     summary="get list of all countries",
     *     tags={"Countries"},
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/v1/countries/{country_code}",
     *     summary="get info on about a country",
     *     tags={"Countries"},
     *     @OA\Parameter(
     *         description="Country Code",
     *         in="path",
     *         name="country_code",
     *         required=true, @OA\Schema(
     *             type="string"
     *         ),
     *         @OA\Examples(
     *             example="string",
     *             value="CA",
     *             summary="Canada"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     * )
     */
    public function show(Country $country)
    {
        $country->load('provinces');

        return $country;
    }
}
