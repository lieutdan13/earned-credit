<?php

use Illuminate\Database\Seeder;
use App\Attendee;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Attendee::truncate();
        // $this->call(UserTableSeeder::class);
        $this->call(AttendeesTableSeeder::class);
    }
}
