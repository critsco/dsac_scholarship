<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefSubject extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function ref_semester()
    {
        return $this->hasMany(RefSemester::class, "semester_id");
    }
}
