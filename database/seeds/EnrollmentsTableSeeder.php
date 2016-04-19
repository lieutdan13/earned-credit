<?php

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use App\Attendee;
use App\Program;
use Carbon\Carbon;

class EnrollmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $attendeeIds = Attendee::lists('id')->all();
        $programIds = Program::lists('id')->all();

        $counter = 0;
        foreach($attendeeIds as $attendeeId) {
            $completion_date = $termination_date = NULL;
            if(($counter + 1) % 6 == 0)
            {
                $completion_date = $faker->dateTimeBetween($startDate = '-6 months');
            } else if(($counter + 1) % 13 == 0)
            {
                $termination_date = $faker->dateTimeBetween($startDate = '-6 months');
            }
            DB::table('enrollments')->insert([
                'attendee_id'  => $attendeeId,
                'program_id' => $faker->randomElement($programIds),
                'start_date'  => $faker->dateTimeBetween($startDate = '-12 months'),
                'completion_date'  => $completion_date,
                'termination_date'  => $termination_date,
                'created_at'   => Carbon::now(),
            ]);
            $counter++;
        }
    }
}
