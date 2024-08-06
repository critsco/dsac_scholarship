<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentExam extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function student_exam_results()
    {
        return $this->hasMany(StudentExamResult::class, "student_exam_id");
    }

    public function ref_exam_schedules()
    {
        return $this->belongsTo(RefExamSchedule::class, "exam_schedule_id");
    }

    public function ref_exam_categories()
    {
        return $this->belongsTo(RefExamCategory::class, "exam_category_id");
    }

    public function student_academic()
    {
        return $this->belongsTo(StudentAcademic::class, "student_academic_id");
    }
}
