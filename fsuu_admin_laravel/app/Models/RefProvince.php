<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefProvince extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function ref_region()
    {
        return $this->belongsTo(RefRegion::class, "region_id");
    }

    public function ref_municipalities()
    {
        return $this->hasMany(RefMunicipality::class, "province_id");
    }
}
