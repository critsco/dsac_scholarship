<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FacultyLoadSchedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function faculty_load_monitorings()
    {
        return $this->hasMany(FacultyLoadMonitoring::class, "faculty_load_schedule_id");
    }

    public function faculty_load()
    {
        return $this->belongsTo(FacultyLoad::class, "faculty_load_id");
    }
}