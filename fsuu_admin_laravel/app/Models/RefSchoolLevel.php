<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefSchoolLevel extends Model
{
    use HasFactory, SoftDeletes;

    public function ref_schools()
    {
        return $this->hasMany(RefSchool::class, 'school_level_id');
    }

    public function student_academics()
    {
        return $this->hasMany(StudentAcademic::class, 'student_level_id');
    }
}
