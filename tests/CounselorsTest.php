<?php

class CounselorsTest extends ApiTester
{
    use Factory;

    public function getStub()
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
    public function it_fetches_all_counselors()
    {
        //arrange
        $this->times(5)->make('App\Counselor');

        //act
        $response = $this->getJson('counselors');

        //assert
        $this->assertResponseOk();
        $this->assertObjectHasAttributes($response, 'data');
        $this->assertCount(5, $response->data);
    }

    /**
     * @test
     */
    public function it_fetches_a_single_counselor()
    {
        //arrange
        $this->make('App\Counselor');

        //act
        $counselor = $this->getJson('counselors/1')->data;

        //assert
        $this->assertResponseOk();
        $this->assertObjectHasAttributes($counselor,
            'id',
            'identifier',
            'first_name',
            'last_name',
            'suffix',
            'hire_date',
            'termination_date'
        );
    }

    /**
     * @test
     */
    public function it_throws_a_404_if_a_counselor_is_not_found()
    {
        //act
        $response = $this->getJson('counselors/x');

        //assert
        $this->assertResponseStatus(404);
        $this->assertObjectHasAttributes($response, 'error');
        $this->assertObjectHasAttributes($response->error, 'message');
        $this->assertContains('Counselor does not exist.', $response->error->message);
    }

    /**
     * @test
     */
    public function it_creates_a_new_counselor_given_valid_parameters()
    {
        //act
        $this->getJson('counselors', 'POST', $this->getStub());

        //assert
        $this->assertResponseStatus(201);
    }

    /**
     * @test
     */
    public function it_throws_a_422_if_a_new_counselor_request_fails_validation()
    {
        //act
        $response = $this->getJson('counselors', 'POST');

        //assert
        $this->assertResponseStatus(422);
        $this->assertObjectHasAttributes($response, 'error');
        $this->assertObjectHasAttributes($response->error, 'message');
        $this->assertContains('Parameters failed validation for a counselor.', $response->error->message);
    }

    /**
     * @test
     */
    public function it_updates_a_counselor_with_a_first_and_last_name()
    {
        //arrange
        $this->make('App\Counselor');
        $counselor = App\Counselor::first();
        $old_first_name = $counselor->first_name;
        $old_last_name = $counselor->last_name;

        //act
        $response = $this->getJson('counselors/' . $counselor->id, 'PATCH', ['first_name' => 'Test', 'last_name' => 'Tester']);
        $counselor = App\Counselor::first();

        //assert
        $this->assertResponseStatus(200);
        $this->assertObjectHasAttributes($response, 'message');
        $this->assertContains('The counselor has been successfully updated.', $response->message);
        $this->assertEquals($counselor->first_name, 'Test');
        $this->assertEquals($counselor->last_name, 'Tester');

    }

    /**
     * @test
     */
    public function it_throws_a_404_if_a_counselor_is_not_found_when_attempting_to_update_a_counselor()
    {
        //act
        $response = $this->getJson('counselors/x', 'PATCH', ['first_name' => 'John', 'last_name' => 'Doe']);

        //assert
        $this->assertResponseStatus(404);
        $this->assertObjectHasAttributes($response, 'error');
        $this->assertObjectHasAttributes($response->error, 'message');
        $this->assertContains('Counselor does not exist.', $response->error->message);
    }

    /**
     * @test
     */
    public function it_deletes_a_counselor()
    {
        //arrange
        $this->make('App\Counselor');
        $counselor = App\Counselor::first();

        //act
        $response = $this->getJson('counselors/' . $counselor->id, 'DELETE');
        $counselor = App\Counselor::onlyTrashed()->find($counselor->id);

        //assert
        $this->assertResponseStatus(200);
        $this->assertObjectHasAttributes($response, 'message');
        $this->assertContains('The counselor has been deleted.', $response->message);
        $this->assertEquals($counselor->trashed(), True);

    }

    /**
     * @test
     */
    public function it_throws_a_404_if_a_counselor_is_not_found_when_attempting_to_delete_it()
    {
        //act
        $response = $this->getJson('counselors/x', 'DELETE');

        //assert
        $this->assertResponseStatus(404);
        $this->assertObjectHasAttributes($response, 'error');
        $this->assertObjectHasAttributes($response->error, 'message');
        $this->assertContains('Counselor does not exist.', $response->error->message);
    }
}
