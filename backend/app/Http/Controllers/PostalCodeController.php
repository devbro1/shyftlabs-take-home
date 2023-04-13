<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PostalCode;

class PostalCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StorePostalCodeRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/postal-codes/{postal_code}",
     *     summary="return info about a given postal code or zip code",
     *     tags={"Postal Code"},
     *     @OA\Parameter(
     *         description="Postal Code",
     *         in="path",
     *         name="postal_code",
     *         required=true, @OA\Schema(
     *             type="string"
     *         ),
     *         @OA\Examples(
     *             example="canadian_code",
     *             value="L3T1E8",
     *             summary="canadian postal code"
     *         ),
     *         @OA\Examples(
     *             example="usa_code",
     *             value="60104",
     *             summary="American zip code"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function show(Request $request, PostalCode $postalCode)
    {
        return $postalCode;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(PostalCode $postalCode)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdatePostalCodeRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PostalCode $postalCode)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(PostalCode $postalCode)
    {
    }
}
