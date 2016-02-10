<?php

use Illuminate\Database\Seeder;
use App\Attendee;
use App\User;

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
        User::truncate();

        $this->call(AttendeesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
    }
}
