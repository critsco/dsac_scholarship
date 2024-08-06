<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefFloor extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function ref_building()
    {
        return $this->belongsTo(RefBuilding::class, "building_id");
    }

    public function ref_rooms()
    {
        return $this->hasMany(RefRoom::class, "floor_id");
    }
}
