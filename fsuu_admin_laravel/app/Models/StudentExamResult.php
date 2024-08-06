<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentExamResult extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function student_exam()
    {
        return $this->belongsTo(StudentExam::class, 'student_exam_id');
    }
}
