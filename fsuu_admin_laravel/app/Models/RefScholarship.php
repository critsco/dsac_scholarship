<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefScholarship extends Model
{
    use HasFactory, SoftDeletes;

    protected $guared = [];

    public function student_academics()
    {
        return $this->hasMany(StudentAcademic::class, "scholarship_id");
    }
}