<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefCourse extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function ref_department()
    {
        return $this->belongsTo(RefDepartment::class, "ref_department_id");
    }

    public function current_course_id()
    {
        return $this->belongsTo(StudentAcademic::class, "current_course_id");
    }

    public function first_course_id()
    {
        return $this->belongsTo(StudentAcademic::class, "first_course_id");
    }

    public function second_course_id()
    {
        return $this->belongsTo(StudentAcademic::class, "second_course_id");
    }

    public function third_course_id()
    {
        return $this->belongsTo(StudentAcademic::class, "third_course_id");
    }
}
