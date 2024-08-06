<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefExamSchedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];


    public function student_exams()
    {
        return $this->hasMany(StudentExam::class, "exam_schedule_id");
    }

    public function ref_semester()
    {
        return $this->belongsTo(RefSemester::class, "semester_id");
    }
}
