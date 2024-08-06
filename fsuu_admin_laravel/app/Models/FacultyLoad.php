<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FacultyLoad extends Model
{

    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function profile()
    {
        return $this->belongsTo(Profile::class, "profile_id");
    }

    public function ref_subject()
    {
        return $this->belongsTo(RefSubject::class, "subject_id");
    }

    public function ref_section()
    {
        return $this->belongsTo(RefSection::class, "section_id");
    }

    public function ref_room()
    {
        return $this->belongsTo(RefRoom::class, "room_id");
    }

    public function ref_school_year()
    {
        return $this->belongsTo(RefSchoolYear::class, "school_year_id");
    }

    public function ref_semester()
    {
        return $this->belongsTo(RefSemester::class, "semester_id");
    }

    public function grade_files()
    {
        return $this->hasMany(GradeFile::class, "faculty_load_id");
    }

    public function faculty_load_monitorings()
    {
        return $this->hasMany(FacultyLoadMonitoring::class, "faculty_load_id");
    }

    public function faculty_load_schedules()
    {
        return $this->hasMany(FacultyLoadSchedule::class, "faculty_load_id");
    }

    public function form_question_answers()
    {
        return $this->hasMany(FormQuestionAnswer::class, "faculty_load_id");
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachmentable');
    }
}
