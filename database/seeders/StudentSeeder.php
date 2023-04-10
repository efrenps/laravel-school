<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run()
    {
        $courses = Course::all();

        Student::factory()
            ->count(100)
            ->create()
            ->each(function ($student) use ($courses) {
                $student->courses()->attach(
                    $courses->random(rand(1, 4))->pluck('id')->toArray()
                );
            });
    }
}
