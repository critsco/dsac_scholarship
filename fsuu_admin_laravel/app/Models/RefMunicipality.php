<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefMunicipality extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function ref_barangays()
    {
        return $this->hasMany(RefBarangay::class, "municipality_id");
    }

    public function ref_province()
    {
        return $this->belongsTo(RefProvince::class, "province_id");
    }
}
