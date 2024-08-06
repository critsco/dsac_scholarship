<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormUserRole extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function form()
    {
        return $this->belongsTo(Form::class, 'form_id');
    }

    public function user_role()
    {
        return $this->belongsTo(UserRole::class, 'user_role_id');
    }
}