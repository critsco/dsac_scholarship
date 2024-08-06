<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileChildren extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function profile_spouse()
    {
        return $this->belongsTo(ProfileSpouse::class, "spouse_id");
    }

    public function profile()
    {
        return $this->belongsTo(Profile::class, "profile_id");
    }
}