<?php

class EnrollmentsTest extends ApiTester
{
    use Factory;

    public function getStub()
    {
        return [];
    }

    /**
     * @test
     */
    public function it_fetches_all_enrollments()
    {
        //arrange
        $attendeeTest = new AttendeesTest();
        $programTest = new ProgramsTest();
        $this->times(5)->make('App\Attendee', $attendeeTest->getStub());
        $this->times(1)->make('App\Program', $programTest->getStub());
        $program = App\Program::first();
        foreach(App\Attendee::all() as $attendee)
        {
            App\Enrollment::create([
                'attendee_id' => $attendee->id,
                'program_id'  => $program->id,
                'start_date'  => $this->faker->dateTimeBetween($startDate = '-12 months'),
            ]);
        }

        //act
        $response = $this->getJson('enrollments');

        //assert
        $this->assertResponseOk();
        $this->assertObjectHasAttributes($response, 'data');
        $this->assertCount(5, $response->data);
    }

    /**
     * @test
     */
    public function it_fetches_all_enrollments_of_a_given_attendee()
    {
        //arrange
        $attendeeTest = new AttendeesTest();
        $programTest = new ProgramsTest();
        $this->times(2)->make('App\Attendee', $attendeeTest->getStub());
        $this->times(2)->make('App\Program', $programTest->getStub());
        foreach(App\Program::all() as $program)
        {
            foreach(App\Attendee::all() as $attendee)
            {
                App\Enrollment::create([
                    'attendee_id' => $attendee->id,
                    'program_id'  => $program->id,
                    'start_date'  => $this->faker->dateTimeBetween($startDate = '-12 months'),
                ]);
            }
        }

        //act
        $response = $this->getJson('enrollments', 'GET', ['attendee_id' => App\Attendee::first()->id]);

        //assert
        $this->assertResponseOk();
        $this->assertObjectHasAttributes($response, 'data');
        $this->assertCount(2, $response->data);
    }

    /**
     * @test
     */
    public function it_throws_a_422_when_fetching_all_enrollments_of_a_given_invalid_attendee()
    {
        //arrange
        $attendeeTest = new AttendeesTest();
        $programTest = new ProgramsTest();
        $this->times(1)->make('App\Attendee', $attendeeTest->getStub());
        $this->times(1)->make('App\Program', $programTest->getStub());
        foreach(App\Program::all() as $program)
        {
            foreach(App\Attendee::all() as $attendee)
            {
                App\Enrollment::create([
                    'attendee_id' => $attendee->id,
                    'program_id'  => $program->id,
                    'start_date'  => $this->faker->dateTimeBetween($startDate = '-12 months'),
                ]);
            }
        }

        //act
        $response = $this->getJson('enrollments', 'GET', ['attendee_id' => 'x']);

        //assert
        $this->assertResponseStatus(422);
        $this->assertObjectHasAttributes($response, 'error');
        $this->assertObjectHasAttributes($response->error, 'message');
        $this->assertContains('Attendee provided does not exist.', $response->error->message);
    }

    /**
     * @test
     */
    public function it_fetches_all_enrollments_to_a_given_program()
    {
        //arrange
        $attendeeTest = new AttendeesTest();
        $programTest = new ProgramsTest();
        $this->times(2)->make('App\Attendee', $attendeeTest->getStub());
        $this->times(2)->make('App\Program', $programTest->getStub());
        foreach(App\Program::all() as $program)
        {
            foreach(App\Attendee::all() as $attendee)
            {
                App\Enrollment::create([
                    'attendee_id' => $attendee->id,
                    'program_id'  => $program->id,
                    'start_date'  => $this->faker->dateTimeBetween($startDate = '-12 months'),
                ]);
            }
        }

        //act
        $response = $this->getJson('enrollments', 'GET', ['program_id' => App\Program::first()->id]);

        //assert
        $this->assertResponseOk();
        $this->assertObjectHasAttributes($response, 'data');
        $this->assertCount(2, $response->data);
    }

    /**
     * @test
     */
    public function it_throws_a_422_when_fetching_all_enrollments_to_a_given_invalid_program()
    {
        //arrange
        $attendeeTest = new AttendeesTest();
        $programTest = new ProgramsTest();
        $this->times(1)->make('App\Attendee', $attendeeTest->getStub());
        $this->times(1)->make('App\Program', $programTest->getStub());
        foreach(App\Program::all() as $program)
        {
            foreach(App\Attendee::all() as $attendee)
            {
                App\Enrollment::create([
                    'attendee_id' => $attendee->id,
                    'program_id'  => $program->id,
                    'start_date'  => $this->faker->dateTimeBetween($startDate = '-12 months'),
                ]);
            }
        }

        //act
        $response = $this->getJson('enrollments', 'GET', ['program_id' => 'x']);

        //assert
        $this->assertResponseStatus(422);
        $this->assertObjectHasAttributes($response, 'error');
        $this->assertObjectHasAttributes($response->error, 'message');
        $this->assertContains('Program provided does not exist.', $response->error->message);
    }

    /**
     * @test
     */
    public function it_fetches_a_single_enrollment()
    {
        //arrange
        $attendeeTest = new AttendeesTest();
        $programTest = new ProgramsTest();
        $this->times(1)->make('App\Attendee', $attendeeTest->getStub());
        $this->times(1)->make('App\Program', $programTest->getStub());
        $program = App\Program::first();
        $attendee = App\Attendee::first();
        App\Enrollment::create([
            'attendee_id' => $attendee->id,
            'program_id'  => $program->id,
            'start_date'  => $this->faker->dateTimeBetween($startDate = '-12 months'),
        ]);
        $enrollment = App\Enrollment::first();

        //act
        $response = $this->getJson('enrollments/' . $enrollment->id);

        //assert
        $this->assertResponseOk();
        $this->assertObjectHasAttributes($response, 'data');
        $this->assertObjectHasAttributes($response->data,
            'attendee_id',
            'program_id',
            'start_date',
            'completion_date',
            'termination_date'
        );
    }

    /**
     * @test
     */
    public function it_throws_a_404_if_an_enrollment_is_not_found()
    {
        //act
        $response = $this->getJson('enrollments/x');

        //assert
        $this->assertResponseStatus(404);
        $this->assertObjectHasAttributes($response, 'error');
        $this->assertObjectHasAttributes($response->error, 'message');
        $this->assertContains('Enrollment does not exist.', $response->error->message);
    }

    /**
     * @test
     */
    public function it_creates_a_new_enrollment_given_valid_parameters()
    {
        //arrange
        $attendeeTest = new AttendeesTest();
        $programTest = new ProgramsTest();
        $this->times(1)->make('App\Attendee', $attendeeTest->getStub());
        $this->times(1)->make('App\Program', $programTest->getStub());
        $program = App\Program::first();
        $attendee = App\Attendee::first();

        //act
        $response = $this->getJson('enrollments', 'POST', [
            'attendee_id' => $attendee->id,
            'program_id'  => $program->id,
            'start_date'  => $this->faker->dateTimeBetween($startDate = '-12 months'),
        ]);

        //assert
        $this->assertResponseStatus(201);
        $this->assertObjectHasAttributes($response, 'message');
        $this->assertContains('Enrollment created successfully.', $response->message);
    }

    /**
     * @test
     */
    public function it_throws_a_422_if_a_new_enrollment_request_fails_validation()
    {
        //act
        $response = $this->getJson('enrollments', 'POST');

        //assert
        $this->assertResponseStatus(422);
        $this->assertObjectHasAttributes($response, 'error');
        $this->assertObjectHasAttributes($response->error, 'message');
        $this->assertContains('Parameters failed validation for an enrollment.', $response->error->message);

    }

    /**
     * @test
     */
    public function it_throws_a_422_when_adding_an_enrollment_that_already_exists()
    {
        //arrange
        $attendeeTest = new AttendeesTest();
        $programTest = new ProgramsTest();
        $this->times(1)->make('App\Attendee', $attendeeTest->getStub());
        $this->times(1)->make('App\Program', $programTest->getStub());
        $program = App\Program::first();
        $attendee = App\Attendee::first();
        App\Enrollment::create([
            'attendee_id' => $attendee->id,
            'program_id'  => $program->id,
            'start_date'  => $this->faker->dateTimeBetween($startDate = '-12 months'),
        ]);
        $enrollment = App\Enrollment::first();

        //act
        $response = $this->getJson('enrollments', 'POST', [
            'attendee_id' => $enrollment->attendee_id,
            'program_id' => $enrollment->program_id
        ]);

        //assert
        $this->assertResponseStatus(422);
        $this->assertObjectHasAttributes($response, 'error');
        $this->assertObjectHasAttributes($response->error, 'message');
        $this->assertContains('The enrollment of the attendee to the program already exists.', $response->error->message);
    }

    /**
     * @test
     */
    public function it_updates_an_enrollment_with_a_completion_date()
    {
        //arrange
        $attendeeTest = new AttendeesTest();
        $programTest = new ProgramsTest();
        $this->times(1)->make('App\Attendee', $attendeeTest->getStub());
        $this->times(1)->make('App\Program', $programTest->getStub());
        $program = App\Program::first();
        $attendee = App\Attendee::first();
        App\Enrollment::create([
            'attendee_id' => $attendee->id,
            'program_id'  => $program->id,
            'start_date'  => $this->faker->dateTimeBetween($startDate = '-12 months'),
        ]);
        $enrollment = App\Enrollment::first();

        //act
        $response = $this->getJson('enrollments/' . $enrollment->id, 'PATCH', ['completion_date' => date('Y-m-d')]);
        $updated_enrollment = App\Enrollment::find($enrollment->id);

        //assert
        $this->assertResponseStatus(200);
        $this->assertObjectHasAttributes($response, 'message');
        $this->assertContains('The enrollment has been updated successfully.', $response->message);
        $this->assertEquals($enrollment->completion_date, NULL);
        $this->assertEquals($updated_enrollment->completion_date, date('Y-m-d'));

    }

    /**
     * @test
     */
    public function it_throws_a_404_if_an_enrollment_is_not_found_when_attempting_to_update_an_enrollment()
    {
        //act
        $response = $this->getJson('enrollments/x', 'PATCH', ['completion_date' => date('Y-m-d')]);

        //assert
        $this->assertResponseStatus(404);
        $this->assertObjectHasAttributes($response, 'error');
        $this->assertObjectHasAttributes($response->error, 'message');
        $this->assertContains('Enrollment does not exist.', $response->error->message);
    }

    /**
     * @test
     */
    public function it_deletes_an_enrollment()
    {
        //arrange
        $attendeeTest = new AttendeesTest();
        $programTest = new ProgramsTest();
        $this->times(1)->make('App\Attendee', $attendeeTest->getStub());
        $this->times(1)->make('App\Program', $programTest->getStub());
        $program = App\Program::first();
        $attendee = App\Attendee::first();
        App\Enrollment::create([
            'attendee_id' => $attendee->id,
            'program_id'  => $program->id,
            'start_date'  => $this->faker->dateTimeBetween($startDate = '-12 months'),
        ]);
        $enrollment = App\Enrollment::first();

        //act
        $response = $this->getJson('enrollments/' . $enrollment->id, 'DELETE');
        $enrollment = App\Enrollment::onlyTrashed()->find($enrollment->id);

        //assert
        $this->assertResponseStatus(200);
        $this->assertObjectHasAttributes($response, 'message');
        $this->assertContains('The enrollment has been deleted.', $response->message);
        $this->assertEquals($enrollment->trashed(), True);
    }

    /**
     * @test
     */
    public function it_throws_a_404_if_an_enrollment_is_not_found_when_attempting_to_delete_it()
    {
        //act
        $response = $this->getJson('enrollments/x', 'DELETE');

        //assert
        $this->assertResponseStatus(404);
        $this->assertObjectHasAttributes($response, 'error');
        $this->assertObjectHasAttributes($response->error, 'message');
        $this->assertContains('Enrollment does not exist.', $response->error->message);
    }
}
