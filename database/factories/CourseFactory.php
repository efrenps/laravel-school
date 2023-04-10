<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Schedule;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Course::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $schedule = Schedule::inRandomOrder()->first();

        return [
            'name' => $this->faker->sentence(),
            'start_date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'end_date' => $this->faker->dateTimeBetween('+1 month', '+6 months'),
            'schedule_id' => Schedule::inRandomOrder()->first()->id,
        ];
    }
}
