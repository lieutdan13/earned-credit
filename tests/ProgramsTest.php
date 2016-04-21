<?php

class ProgramsTest extends ApiTester
{
    use Factory;

    public function getStub()
    {
        return [
            'name' => $this->faker->catchPhrase(),
        ];
    }

    /**
     * @test
     */
    public function it_fetches_all_programs()
    {
        //arrange
        $this->times(5)->make('App\Program');

        //act
        $response = $this->getJson('programs');

        //assert
        $this->assertResponseOk();
        $this->assertObjectHasAttributes($response, 'data');
        $this->assertCount(5, $response->data);
    }

    /**
     * @test
     */
    public function it_fetches_a_single_program()
    {
        //arrange
        $this->make('App\Program');

        //act
        $program = $this->getJson('programs/1')->data;

        //assert
        $this->assertResponseOk();
        $this->assertObjectHasAttributes($program,
            'id',
            'name'
        );
    }

    /**
     * @test
     */
    public function it_throws_a_404_if_a_program_is_not_found()
    {
        //act
        $response = $this->getJson('programs/x');

        //assert
        $this->assertResponseStatus(404);
        $this->assertObjectHasAttributes($response, 'error');
        $this->assertObjectHasAttributes($response->error, 'message');
        $this->assertContains('Program does not exist.', $response->error->message);
    }

    /**
     * @test
     */
    public function it_creates_a_new_program_given_valid_parameters()
    {
        //act
        $this->getJson('programs', 'POST', $this->getStub());

        //assert
        $this->assertResponseStatus(201);
    }

    /**
     * @test
     */
    public function it_throws_a_422_if_a_new_program_request_fails_validation()
    {
        //act
        $response = $this->getJson('programs', 'POST');

        //assert
        $this->assertResponseStatus(422);
        $this->assertObjectHasAttributes($response, 'error');
        $this->assertObjectHasAttributes($response->error, 'message');
        $this->assertContains('Parameters failed validation for a program.', $response->error->message);
    }

    /**
     * @test
     */
    public function it_updates_a_program_with_a_first_and_last_name()
    {
        //arrange
        $this->make('App\Program');
        $program = App\Program::first();

        //act
        $response = $this->getJson('programs/' . $program->id, 'PATCH', ['name' => 'Test Program Name']);
        $program = App\Program::first();

        //assert
        $this->assertResponseStatus(200);
        $this->assertObjectHasAttributes($response, 'message');
        $this->assertContains('The program has been successfully updated.', $response->message);
        $this->assertEquals($program->name, 'Test Program Name');

    }

    /**
     * @test
     */
    public function it_throws_a_404_if_a_program_is_not_found_when_attempting_to_update_a_program()
    {
        //act
        $response = $this->getJson('programs/x', 'PATCH', ['name' => 'Test Program Name']);

        //assert
        $this->assertResponseStatus(404);
        $this->assertObjectHasAttributes($response, 'error');
        $this->assertObjectHasAttributes($response->error, 'message');
        $this->assertContains('Program does not exist.', $response->error->message);
    }

    /**
     * @test
     */
    public function it_deletes_a_program()
    {
        //arrange
        $this->make('App\Program');
        $program = App\Program::first();

        //act
        $response = $this->getJson('programs/' . $program->id, 'DELETE');
        $program = App\Program::onlyTrashed()->find($program->id);

        //assert
        $this->assertResponseStatus(200);
        $this->assertObjectHasAttributes($response, 'message');
        $this->assertContains('The program has been deleted.', $response->message);
        $this->assertEquals($program->trashed(), True);

    }

    /**
     * @test
     */
    public function it_throws_a_404_if_a_program_is_not_found_when_attempting_to_delete_it()
    {
        //act
        $response = $this->getJson('programs/x', 'DELETE');

        //assert
        $this->assertResponseStatus(404);
        $this->assertObjectHasAttributes($response, 'error');
        $this->assertObjectHasAttributes($response->error, 'message');
        $this->assertContains('Program does not exist.', $response->error->message);
    }
}
