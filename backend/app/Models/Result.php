<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Result extends Model
{
    use HasFactory;
    use BaseModel;

    protected $fillable = ['score', 'course_id','student_id'];

    public static function getValidationRules(array $values, self $model = null)
    {
        $rc = [];
        $rc['score'] = ['required', 'string', Rule::in(['A','B','C','D','E','F'])];
        $rc['course_id'] = ['required', 'integer', 'exists:courses,id'];
        $rc['student_id'] = ['required', 'integer', 'exists:students,id'];

        return $rc;
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
