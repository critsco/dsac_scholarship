<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRolePermission extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user_role()
    {
        return $this->belongsTo(User::class, "user_role_id");
    }

    public function module_button()
    {
        return $this->belongsTo(ModuleButton::class, "mod_button_id");
    }
}
