<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScheduleDayTime extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function ref_day_schedule()
    {
        return $this->belongsTo(RefDaySchedule::class, "day_id");
    }
    public function ref_time_schedule()
    {
        return $this->belongsTo(RefTimeSchedule::class, "time_id");
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class, "schedule_id");
    }
}
