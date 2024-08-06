<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Form extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function form_question_answers()
    {
        return $this->hasMany(FormQuestionAnswer::class, 'form_id');
    }

    public function form_question_categories()
    {
        return $this->hasMany(FormQuestionCategory::class, 'form_id');
    }

    public function form_user_roles()
    {
        return $this->hasMany(FormUserRole::class, 'form_id');
    }

    public function school_year()
    {
        return $this->belongsTo(RefSchoolYear::class, 'school_year_id');
    }

    public function semester()
    {
        return $this->belongsTo(RefSemester::class, 'semester_id');
    }
}