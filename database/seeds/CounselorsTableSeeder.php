<?php

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use App\Counselor;

class CounselorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        foreach(range(1, 5) as $index) {
            Counselor::create([
                'identifier' => $faker->regexify('[0-9]{10}'),
                'first_name' => $faker->firstName(),
                'last_name'  => $faker->lastName(),
                'hire_date'  => $faker->dateTimeBetween($startDate = '-3 years'),
            ]);
        }
    }
}
