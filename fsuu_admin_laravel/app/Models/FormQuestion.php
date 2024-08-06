<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormQuestion extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function form_question_category()
    {
        return $this->belongsTo(FormQuestionCategory::class, 'form_question_category_id');
    }

    public function form_question_options()
    {
        return $this->hasMany(FormQuestionOption::class, 'form_question_id');
    }

    public function form_question_answers()
    {
        return $this->hasMany(FormQuestionAnswer::class, 'form_question_id');
    }
}