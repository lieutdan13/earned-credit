<?php

use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ProgramsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        foreach(range(1, 2) as $index) {
            DB::table('programs')->insert([
                'name'       => $faker->catchPhrase(),
                'created_at' => Carbon::now(),
            ]);
        }
    }
}
