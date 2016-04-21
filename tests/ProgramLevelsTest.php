<?php

class ProgramLevelsTest extends ApiTester
{
    use Factory;

    public function getStub()
    {
        return [
            'program_id'  => $this->faker->numberBetween(1,5),
            'name'        => $this->faker->safeColorName(),
        ];
    }

    /**
     * @test
     */
    public function it_fetches_all_program_levels()
    {
        //arrange
        $this->times(5)->make('App\ProgramLevel');

        //act
        $response = $this->getJson('program_levels');

        //assert
        $this->assertResponseOk();
        $this->assertObjectHasAttributes($response, 'data');
        $this->assertCount(5, $response->data);
    }

    /**
     * @test
     */
    public function it_fetches_a_single_program_level()
    {
        //arrange
        $this->make('App\ProgramLevel');

        //act
        $program_level = $this->getJson('program_levels/1')->data;

        //assert
        $this->assertResponseOk();
        $this->assertObjectHasAttributes($program_level,
            'id',
            'program_id',
            'name'
        );
    }

    /**
     * @test
     */
    public function it_throws_a_404_if_a_program_level_is_not_found()
    {
        //act
        $response = $this->getJson('program_levels/x');

        //assert
        $this->assertResponseStatus(404);
        $this->assertObjectHasAttributes($response, 'error');
        $this->assertObjectHasAttributes($response->error, 'message');
        $this->assertContains('Program level does not exist.', $response->error->message);
    }

    /**
     * @test
     */
    public function it_creates_a_new_program_level_given_valid_parameters()
    {
        //arrange
        $this->make('App\Program');
        $program = App\Program::first();

        //act
        $this->getJson('program_levels', 'POST', ['name' => 'Test Program Level', 'program_id' => $program->id]);

        //assert
        $this->assertResponseStatus(201);
    }

    /**
     * @test
     */
    public function it_throws_a_404_when_trying_to_create_a_program_level_with_an_invalid_program_id()
    {
        //act
        $this->getJson('program_levels', 'POST', ['name' => 'Test Program Level', 'program_id' => 'x']);

        //assert
        $this->assertResponseStatus(404);
    }

    /**
     * @test
     */
    public function it_throws_a_422_if_a_new_program_level_request_fails_validation()
    {
        //act
        $response = $this->getJson('program_levels', 'POST');

        //assert
        $this->assertResponseStatus(422);
        $this->assertObjectHasAttributes($response, 'error');
        $this->assertObjectHasAttributes($response->error, 'message');
        $this->assertContains('Parameters failed validation for a program level.', $response->error->message);
    }

    /**
     * @test
     */
    public function it_updates_a_program_with_a_name()
    {
        //arrange
        $this->make('App\ProgramLevel');
        $program_level = App\ProgramLevel::first();

        //act
        $response = $this->getJson('program_levels/' . $program_level->id, 'PATCH', ['name' => 'Test Program Level Name']);
        $program_level = App\ProgramLevel::first();

        //assert
        $this->assertResponseStatus(200);
        $this->assertObjectHasAttributes($response, 'message');
        $this->assertContains('The program level has been successfully updated.', $response->message);
        $this->assertEquals($program_level->name, 'Test Program Level Name');

    }

    /**
     * @test
     */
    public function it_throws_a_404_if_a_program_level_is_not_found_when_attempting_to_update_a_program_level()
    {
        //act
        $response = $this->getJson('program_levels/x', 'PATCH', ['name' => 'Test Program Level Name']);

        //assert
        $this->assertResponseStatus(404);
        $this->assertObjectHasAttributes($response, 'error');
        $this->assertObjectHasAttributes($response->error, 'message');
        $this->assertContains('Program level does not exist.', $response->error->message);
    }

    /**
     * @test
     */
    public function it_deletes_a_program_level()
    {
        //arrange
        $this->make('App\ProgramLevel');
        $program_level = App\ProgramLevel::first();

        //act
        $response = $this->getJson('program_levels/' . $program_level->id, 'DELETE');
        $program_level = App\ProgramLevel::onlyTrashed()->find($program_level->id);

        //assert
        $this->assertResponseStatus(200);
        $this->assertObjectHasAttributes($response, 'message');
        $this->assertContains('The program level has been deleted.', $response->message);
        $this->assertEquals($program_level->trashed(), True);

    }

    /**
     * @test
     */
    public function it_throws_a_404_if_a_program_level_is_not_found_when_attempting_to_delete_it()
    {
        //act
        $response = $this->getJson('program_levels/x', 'DELETE');

        //assert
        $this->assertResponseStatus(404);
        $this->assertObjectHasAttributes($response, 'error');
        $this->assertObjectHasAttributes($response->error, 'message');
        $this->assertContains('Program level does not exist.', $response->error->message);
    }
}
