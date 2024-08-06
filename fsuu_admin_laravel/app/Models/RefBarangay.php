<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefBarangay extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function ref_municipality()
    {
        return $this->belongsTo(RefMunicipality::class, "municipality_id");
    }

    public function profile_addresses()
    {
        return $this->hasMany(ProfileAddress::class, "barangay_id");
    }
}