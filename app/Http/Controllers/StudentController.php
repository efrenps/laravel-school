<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Student;
use App\Models\Schedule;
use App\Models\CourseStudent;
use Inertia\Inertia;
use Carbon\Carbon;
use App\Http\Controllers\CourseController;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $students = Student::with('courses')
                    ->withCount('courses')
                    ->orderBydesc('created_at')
                    ->orderBy('last_name')
                    ->paginate(10);
        
        return Inertia::render('Students', [
            'students' => $students,
        ]);
    }

    public function get($id)
    {
        $data =Student::with('courses')
                ->withCount('courses')
                ->findOrFail($id);

        $currentCourses = array();
        foreach($data->courses as $record) {
            array_push($currentCourses, $record['id']);
        }

        $courseData = Course::with('schedule')
                    ->whereNotIn('id', $currentCourses)
                    ->get();

        $courses = array();
        foreach ($courseData as $record) {
            $course = new \stdClass();
            $course->id = $record['id'];
            $course->name = $record['name'];
            $course->start_date = (new CourseController)->formatDate($record['start_date']);
            $course->end_date = (new CourseController)->formatDate($record['end_date']);
            $course->schedule = (new CourseController)->formatSchedule($record['schedule']);
            array_push($courses, $course);
        }

        return Inertia::render('StudentDetail', [
            'student' => $data,
            'courses' => $courses,
        ]);
    }

    public function create()
    {
        $scheduleData = Schedule::all();
        $schedules = array();
        foreach ($scheduleData as $record) {
            $schedule = new \stdClass();
            $schedule->id = $record['id'];
            array_push($schedules, $schedule);
        }

        return Inertia::render('StudentDetail', [
            'schedules' => $schedules,
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'age' => 'required',
            'email' => 'required',
        ]);

        $student = Student::create($validatedData);
        return redirect()->route('students');
    }

    public function update(Request $request, Student $student)
    {
        $validatedData = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'age' => 'required',
            'email' => 'required',
        ]);
        $student->update($validatedData);

        return redirect('/students/' . $student['id']);
    }

    public function enroll(Request $request, Student $student)
    {
        $validatedData = $request->validate([
            'course_id' => 'required',
        ]);

        $studentId = $student['id'];
        $courseStudent = new CourseStudent;
        $courseStudent['course_id'] = $validatedData['course_id'];
        $courseStudent['student_id'] = $studentId;
        $courseStudent->save();

        return redirect('/students/' . $studentId);
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('students')->with('message', 'course Deleted successfully');
    }
}