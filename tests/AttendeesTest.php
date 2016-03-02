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

    /**
     * @test
     */
    public function it_fetches_all_attendees()
    {
        //arrange
        $this->times(5)->make('App\Attendee');

        //act
        $this->getJson('attendees');

        //assert
        $this->assertResponseOk();
    }

    /**
     * @test
     */
    public function it_fetches_a_single_attendee()
    {
        //arrange
        $this->make('App\Attendee');

        //act
        $attendee = $this->getJson('attendees/1')->data;

        //assert
        $this->assertResponseOk();
        $this->assertObjectHasAttributes($attendee, 'first_name', 'last_name', 'suffix', 'identifier');
    }

    /**
     * @test
     */
    public function it_throws_a_404_if_an_attendee_is_not_found()
    {
        //act
        $this->getJson('attendees/x');

        //assert
        $this->assertResponseStatus(404);
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
        $this->getJson('attendees', 'POST');

        //assert
        $this->assertResponseStatus(422);
    }
}
