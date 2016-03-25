<?php

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use App\Attendee;

class AttendeesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        foreach(range(1, 30) as $index) {
            Attendee::create([
                'first_name' => $faker->firstNameMale(),
                'last_name'  => $faker->lastName(),
                'suffix'     => $index % 5 ? NULL : $faker->suffix(),
                'identifier' => $faker->regexify('[A-Z]?[0-9]{3}/[0-9]{3}'),
            ]);
        }
    }
}
