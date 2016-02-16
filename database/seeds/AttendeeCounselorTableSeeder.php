<?php

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use App\Attendee;
use App\Counselor;

class AttendeeCounselorTableSeeder extends Seeder
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
        $counselorIds = Counselor::lists('id')->all();

        foreach($attendeeIds as $attendeeId) {
            DB::table('attendee_counselor')->insert([
                'attendee_id'  => $attendeeId,
                'counselor_id' => $faker->randomElement($counselorIds),
            ]);
        }
    }
}
