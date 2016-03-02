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
        $this->getJson('counselors');

        //assert
        $this->assertResponseOk();
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
        $this->getJson('counselors/x');

        //assert
        $this->assertResponseStatus(404);
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
        $this->getJson('counselors', 'POST');

        //assert
        $this->assertResponseStatus(422);
    }
}
