<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return QueryBuilder::for(Student::class)
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::partial('first_name'),
                AllowedFilter::partial('family_name'),
                AllowedFilter::partial('email'),
                AllowedFilter::partial('date_of_birth'),
            ])
            ->allowedSorts(['id', 'first_name','family_name', 'email','date_of_birth'])
            ->jsonPaginate()
        ;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated_data = Student::validate($request);
        $rc = new Student($validated_data);
        $rc->save();

        return ['message' => 'Student was created successfully', 'data' => $rc];
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        return $student;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $validated_data = Student::validate($request, $student);
        $rc->update($validated_data);

        return ['message' => 'Student was updated successfully', 'data' => $rc];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $student->delete();

        return ['message' => 'Student was deleted successfully'];
    }
}
