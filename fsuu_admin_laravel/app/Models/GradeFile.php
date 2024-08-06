<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeFile extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function faculty_load()
    {
        return $this->belongsTo(FacultyLoad::class, "faculty_load_id");
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachmentable');
    }
}
