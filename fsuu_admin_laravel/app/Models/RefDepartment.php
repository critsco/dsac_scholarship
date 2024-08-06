<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefDepartment extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function profile_departments()
    {
        return $this->hasMany(RefDepartment::class, "department_id");
    }

    public function ref_courses()
    {
        return $this->hasMany(RefCourse::class, "ref_department_id");
    }
}
