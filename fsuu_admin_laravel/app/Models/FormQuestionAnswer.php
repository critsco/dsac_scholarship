<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormQuestionAnswer extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function form()
    {
        return $this->belongsTo(Form::class, 'form_id');
    }

    public function form_question_category()
    {
        return $this->belongsTo(FormQuestionCategory::class, 'form_question_category_id');
    }

    public function form_question()
    {
        return $this->belongsTo(FormQuestion::class, 'form_question_id');
    }
}