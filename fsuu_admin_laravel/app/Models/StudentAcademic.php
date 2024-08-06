<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentAcademic extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function profile()
    {
        return $this->belongsTo(Profile::class, "profile_id");
    }

    public function current_course()
    {
        return $this->belongsTo(RefCourse::class, 'current_course_id');
    }

    public function first_course()
    {
        return $this->belongsTo(RefCourse::class, 'first_course_id');
    }

    public function second_course()
    {
        return $this->belongsTo(RefCourse::class, 'second_course_id');
    }

    public function third_course()
    {
        return $this->belongsTo(RefCourse::class, 'third_course_id');
    }

    public function ref_school_level()
    {
        return $this->belongsTo(RefSchoolLevel::class, "student_level_id");
    }

    public function ref_scholarship()
    {
        return $this->belongsTo(RefScholarship::class, "scholarship_id");
    }

    public function student_exams()
    {
        return $this->hasMany(StudentExam::class, "student_academic_id");
    }
}