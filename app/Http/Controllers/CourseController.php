<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Schedule;
use Inertia\Inertia;
use Carbon\Carbon;

class CourseController extends Controller
{
    public function formatSchedule($schedule) {
        $daysOfWeek = ["sunday", "monday", "tuesday", "wednesday", "thursday", "friday", "saturday"];
        $activeDays = [];
        $firstConsecutiveDay = null;        
        $lastDay = "sunday";
        
        foreach ($daysOfWeek as $day) {
            if ($schedule->$day) {
                // If this is the first consecutive day, set $firstConsecutiveDay to the current day
                if ($firstConsecutiveDay === null) {
                    $firstConsecutiveDay = $day;
                }
                // Set $lastDay to the current day (it will be updated each time we encounter an active day)
                $lastDay = $day;
            } else {
                // If the current day is not active, and we've already seen consecutive days,
                // add the range of consecutive days to the $activeDays array
                if ($firstConsecutiveDay !== null) {
                    // If the first and last day of the consecutive days are the same, just add that day to the $activeDays array
                    if ($firstConsecutiveDay === $lastDay) {
                        $activeDays[] = $firstConsecutiveDay;
                    // Otherwise, add the range of days (e.g. "Monday to Friday") to the $activeDays array
                    } else {
                        $activeDays[] = "$firstConsecutiveDay to $lastDay";
                    }
                    // Reset $firstConsecutiveDay so we can look for more consecutive days
                    $firstConsecutiveDay = null;
                }
            }
        }
        
        // If we reached the end of the loop and there were consecutive days that we didn't add to the $activeDays array,
        // add them now
        if ($firstConsecutiveDay !== null) {
            // If the first and last day of the consecutive days are the same, just add that day to the $activeDays array
            if ($firstConsecutiveDay === $lastDay) {
                $activeDays[] = $firstConsecutiveDay;
            // Otherwise, add the range of days (e.g. "Monday to Friday") to the $activeDays array
            } else {
                $activeDays[] = "$firstConsecutiveDay to $lastDay";
            }
        }

        // Format as "hh:mm AM/PM"
        $startTime = date('h:i A', strtotime($schedule->start_time));
        $endTime = date('h:i A', strtotime($schedule->end_time));
        
        // Join the names of the active days together into a comma-separated string and formatted dates
        return ucwords(implode(", ", $activeDays) . " " . $startTime . " - " . $endTime);
    }

    
    public function formatDate(string $stringDate)
    {
        $date = \DateTime::createFromFormat('Y-m-d', $stringDate);
        
        // Full month name, day of the month with ordinal suffix, year
        return $date->format('M j Y');
    }

    
    /**
     * Fetches all courses from the database along with their schedule data, formats the data into a more
     * user-friendly format and renders a view with the formatted data using the Inertia framework.
    */
    public function index(Request $request)
    {
        $data = Course::with('students', 'schedule')
                ->withCount('students')
                ->orderBy('start_date')
                ->orderByDesc('students_count')
                ->get();



        $courses = array();
        foreach ($data as $record) {
            $course = new \stdClass();
            $course->id = $record['id'];
            $course->name = $record['name'];
            $course->start_date = $this->formatDate($record['start_date']);
            $course->end_date = $this->formatDate($record['end_date']);
            $course->schedule = $this->formatSchedule($record['schedule']);
            $course->students_count = $record['students_count'];
            array_push($courses, $course);
        }

        return Inertia::render('Courses', [
            'courses' => $courses
        ]);
    }

    public function get($id)
    {
        $data = Course::with('students', 'schedule')
                ->withCount('students')
                ->findOrFail($id);

        $scheduleData = Schedule::all();
        $schedules = array();
        foreach ($scheduleData as $record) {
            $schedule = new \stdClass();
            $schedule->id = $record['id'];
            $schedule->name = $this->formatSchedule($record);
            array_push($schedules, $schedule);
        }

        return Inertia::render('CourseDetail', [
            'course' => $data,
            'schedules' => $schedules,
        ]);
    }

    public function create()
    {
        $scheduleData = Schedule::all();
        $schedules = array();
        foreach ($scheduleData as $record) {
            $schedule = new \stdClass();
            $schedule->id = $record['id'];
            $schedule->name = $this->formatSchedule($record);
            array_push($schedules, $schedule);
        }

        return Inertia::render('CourseDetail', [
            'schedules' => $schedules,
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'schedule_id' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $course = Course::create($validatedData);
        return redirect()->route('courses');
    }

    public function update(Request $request, Course $course)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'schedule_id' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);
        $course->update($validatedData);

        return redirect('/courses/' . $course['id']);
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('courses')->with('message', 'course Deleted successfully');
    }

    public function dashboard(Request $request)
    {
        $limitDate = Carbon::now()->subMonths(6);

        $courses = Course::withCount('students')
                            ->where('start_date', '>=', $limitDate)
                            ->orderBy('students_count', 'desc')
                            ->take(3)
                            ->get();
        
        return Inertia::render('Dashboard', [
            'courses' => $courses
        ]);

    }
}
