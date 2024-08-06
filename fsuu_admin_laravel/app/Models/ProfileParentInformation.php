<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfileParentInformation extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $table = 'profile_parent_informations';

    public function profile()
    {
        return $this->belongsTo(Profile::class, "profile_id");
    }
}
