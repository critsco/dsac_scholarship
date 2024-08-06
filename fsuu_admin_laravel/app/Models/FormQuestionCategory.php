<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormQuestionCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function form()
    {
        return $this->belongsTo(Form::class, 'form_id');
    }

    public function form_questions()
    {
        return $this->hasMany(FormQuestion::class, 'form_question_category_id');
    }
}