<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfileLanguage extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function profile()
    {
        return $this->belongsTo(Profile::class, "profile_id");
    }

    public function ref_language()
    {
        return $this->belongsTo(RefLanguage::class, "language_id");
    }
}
