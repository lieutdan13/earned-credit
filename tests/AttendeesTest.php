<?php


class AttendeesTest extends ApiTester
{
    use Factory;

    public function getStub()
    {
        return [
            'first_name' => $this->faker->firstNameMale(),
            'last_name'  => $this->faker->lastName(),
            'suffix'     => $this->faker->suffix(),
            'identifier' => $this->faker->regexify('[A-Z]?[0-9]{3}/[0-9]{3}'),
        ];
    }

    public function getCounselorStub()
    {
        return [
            'identifier' => $this->faker->regexify('[0-9]{10}'),
            'first_name' => $this->faker->firstName(),
            'last_name'  => $this->faker->lastName(),
            'hire_date'  => $this->faker->dateTimeBetween($startDate = '-3 years'),
        ];
    }

    /**
     * @test
     */
    public function it_fetches_all_attendees()
    {
        //arrange
        $this->times(5)->make('App\Attendee');

        //act
        $response = $this->getJson('attendees');

        //assert
        $this->assertResponseOk();
        $this->assertObjectHasAttributes($response, 'data');
        $this->assertCount(5, $response->data);
    }

    /**
     * @test
     */
    public function it_fetches_a_single_attendee()
    {
        //arrange
        $this->make('App\Attendee');

        //act
        $response = $this->getJson('attendees/1')->data;

        //assert
        $this->assertResponseOk();
        $this->assertObjectHasAttributes($response, 'first_name', 'last_name', 'suffix', 'identifier');
    }

    /**
     * @test
     */
    public function it_throws_a_404_if_an_attendee_is_not_found()
    {
        //act
        $response = $this->getJson('attendees/x');

        //assert
        $this->assertResponseStatus(404);
        $this->assertObjectHasAttributes($response, 'error');
        $this->assertObjectHasAttributes($response->error, 'message');
        $this->assertContains('Attendee does not exist.', $response->error->message);
    }

    /**
     * @test
     */
    public function it_creates_a_new_attendee_given_valid_parameters()
    {
        //act
        $this->getJson('attendees', 'POST', $this->getStub());

        //assert
        $this->assertResponseStatus(201);
    }

    /**
     * @test
     */
    public function it_throws_a_422_if_a_new_attendee_request_fails_validation()
    {
        //act
        $response = $this->getJson('attendees', 'POST');

        //assert
        $this->assertResponseStatus(422);
        $this->assertObjectHasAttributes($response, 'error');
        $this->assertObjectHasAttributes($response->error, 'message');
        $this->assertContains('Parameters failed validation for an attendee.', $response->error->message);

    }

    /**
     * @test
     */
    public function it_updates_an_attendee_counselor_assignment()
    {
        //arrange
        $this->make('App\Attendee');
        $this->times(2)->make('App\Counselor', $this->getCounselorStub());
        $attendee = App\Attendee::first();
        $attendee->counselors()->attach([1]);

        //act
        $response = $this->getJson('attendees/' . $attendee->id . '/counselor', 'PUT', ['counselor_id' => 2]);

        //assert
        $this->assertResponseStatus(200);
        $this->assertObjectHasAttributes($response, 'message');
        $this->assertContains('The attendee/counselor assignment has been updated.', $response->message);
    }

    /**
     * @test
     */
    public function it_keeps_the_attendee_counselor_assignment_unchanged()
    {
        //arrange
        $this->make('App\Attendee');
        $this->times(1)->make('App\Counselor', $this->getCounselorStub());
        $attendee = App\Attendee::first();
        $attendee->counselors()->attach([1]);

        //act
        $response = $this->getJson('attendees/' . $attendee->id . '/counselor', 'PUT', ['counselor_id' => 1]);

        //assert
        $this->assertResponseStatus(200);
        $this->assertObjectHasAttributes($response, 'message');
        $this->assertContains('The attendee/counselor assignment remains unchanged.', $response->message);
    }

    /**
     * @test
     */
    public function it_throws_a_422_when_failing_to_find_a_counselor_while_updating_an_attendee_counselor_assignment()
    {
        //arrange
        $this->make('App\Attendee');
        $attendee = App\Attendee::first();

        //act
        $response = $this->getJson('attendees/' . $attendee->id . '/counselor', 'PUT', ['counselor_id' => 1]);

        //assert
        $this->assertResponseStatus(422);
        $this->assertObjectHasAttributes($response, 'error');
        $this->assertObjectHasAttributes($response->error, 'message');
        $this->assertContains('Counselor provided does not exist', $response->error->message);
    }

    /**
     * @test
     */
    public function it_throws_a_422_when_validation_fails_while_updating_an_attendee_counselor_assignment()
    {
        //arrange
        $this->make('App\Attendee');
        $attendee = App\Attendee::first();

        //act
        $response = $this->getJson('attendees/' . $attendee->id . '/counselor', 'PUT', []);

        //assert
        $this->assertResponseStatus(422);
        $this->assertObjectHasAttributes($response, 'error');
        $this->assertObjectHasAttributes($response->error, 'message');
        $this->assertContains('Parameters failed validation for an attendee.', $response->error->message);
    }

    /**
     * @test
     */
    public function it_throws_a_404_when_an_attendee_does_not_exist_while_updating_an_attendee_counselor_assignment()
    {
        //act
        $response = $this->getJson('attendees/1/counselor', 'PUT', ['counselor_id' => 1]);

        //assert
        $this->assertResponseStatus(404);
        $this->assertObjectHasAttributes($response, 'error');
        $this->assertObjectHasAttributes($response->error, 'message');
        $this->assertContains('Attendee does not exist.', $response->error->message);
    }
}
