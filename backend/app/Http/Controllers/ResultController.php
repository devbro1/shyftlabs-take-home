<?php

namespace App\Http\Controllers;

use App\Models\Result;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class ResultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return QueryBuilder::for(Result::class)
            ->allowedFilters([
                AllowedFilter::exact('id'),
            ])
            ->allowedSorts(['id'])
            ->with(['course','student'])
            ->jsonPaginate()
        ;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated_data = Result::validate($request);
        $rc = new Result($validated_data);
        $rc->save();

        return ['message' => 'Result was created successfully', 'data' => $rc];
    }

    /**
     * Display the specified resource.
     */
    public function show(Result $result)
    {
        $result->load('student');
        $result->load('course');
        return $result;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Result $result)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Result $result)
    {
        //
    }
}
