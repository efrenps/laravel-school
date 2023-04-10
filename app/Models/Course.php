<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'schedule_id',
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class)
                    ->using(CourseStudent::class)
                    ->withPivot('course_id')
                    ->withTimestamps();
    }

}
