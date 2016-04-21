<?php

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use App\Program;
use App\ProgramLevels;
use Carbon\Carbon;

class ProgramLevelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $programIds = Program::lists('id')->all();

        foreach($programIds as $programId) {
            for($l = 0; $l < 5; $l++)
            {
                DB::table('program_levels')->insert([
                    'program_id'  => $programId,
                    'name'        => $faker->safeColorName(),
                    'created_at'  => Carbon::now(),
                ]);
            }
        }
    }
}
