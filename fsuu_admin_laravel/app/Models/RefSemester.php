<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefSemester extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function ref_exam_schedules()
    {
        return $this->hasMany(RefExamSchedule::class, "semester_id");
    }
}
