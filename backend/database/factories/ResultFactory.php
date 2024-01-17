<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Course;
use App\Models\Student;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Result>
 */
class ResultFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $course = Course::all()->random();
        $student = Student::all()->random();
        $scores = collect(['A','B','C','D','E','F']);

        return [
            'score' => $scores->random(),
            'course_id' => $course->id,
            'student_id' => $student->id,
        ];
    }
}
