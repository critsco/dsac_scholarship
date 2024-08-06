<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefSchool extends Model
{
    use HasFactory, SoftDeletes;

    public function school_level_id()
    {
        return $this->belongsTo(RefSchoolLevel::class, 'school_level_id');
    }
}
