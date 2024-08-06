<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefLanguage extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function profile_languages()
    {
        return $this->hasMany(ProfileLanguage::class, "language_id");
    }
}
