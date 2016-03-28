<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    private $tables = [
        'attendees',
        'attendee_counselor',
        'counselors',
        'programs',
        'program_levels',
        'users',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->cleanDatabase();

        $this->call(AttendeesTableSeeder::class);
        $this->call(CounselorsTableSeeder::class);
        $this->call(AttendeeCounselorTableSeeder::class);
        $this->call(ProgramsTableSeeder::class);
        $this->call(ProgramLevelsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
    }

    /**
     * Truncates all tables in $this->tables.
     */
    private function cleanDatabase()
    {
        //DB::statement('SET FOREIGN_KEY_CHECKS=0');
        foreach($this->tables as $tableName)
        {
            DB::table($tableName)->truncate();
        }
        //DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
