<?php

namespace Database\Seeders;

use App\Models\Schedule;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schedules = [
            [
                'monday' => true,
                'tuesday' => true,
                'wednesday' => true,
                'thursday' => true,
                'friday' => true,
                'saturday' => false,
                'sunday' => false,
                'start_time' => Carbon::createFromTime(9, 0, 0),
                'end_time' => Carbon::createFromTime(11, 0, 0),
            ],
            [
                'monday' => true,
                'tuesday' => true,
                'wednesday' => true,
                'thursday' => true,
                'friday' => true,
                'saturday' => false,
                'sunday' => false,
                'start_time' => Carbon::createFromTime(14, 0, 0),
                'end_time' => Carbon::createFromTime(17, 0, 0),
            ],
            [
                'monday' => false,
                'tuesday' => false,
                'wednesday' => false,
                'thursday' => false,
                'friday' => false,
                'saturday' => true,
                'sunday' => false,
                'start_time' => Carbon::createFromTime(8, 0, 0),
                'end_time' => Carbon::createFromTime(12, 0, 0),
            ],
            [
                'monday' => false,
                'tuesday' => false,
                'wednesday' => false,
                'thursday' => false,
                'friday' => false,
                'saturday' => true,
                'sunday' => false,
                'start_time' => Carbon::createFromTime(13, 0, 0),
                'end_time' => Carbon::createFromTime(17, 0, 0),
            ],
            [
                'monday' => false,
                'tuesday' => false,
                'wednesday' => false,
                'thursday' => false,
                'friday' => false,
                'saturday' => false,
                'sunday' => true,
                'start_time' => Carbon::createFromTime(8, 0, 0),
                'end_time' => Carbon::createFromTime(12, 0, 0),
            ],
            [
                'monday' => false,
                'tuesday' => false,
                'wednesday' => false,
                'thursday' => false,
                'friday' => false,
                'saturday' => false,
                'sunday' => true,
                'start_time' => Carbon::createFromTime(13, 0, 0),
                'end_time' => Carbon::createFromTime(17, 0, 0),
            ]
        ];

        // Insertamos los registros en la tabla 'schedules'
        foreach ($schedules as $schedule) {
            Schedule::create($schedule);
        } 
    }
}
