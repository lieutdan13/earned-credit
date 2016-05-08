<?php

class AuthenticateTest extends ApiTester 
{
    use Factory;

    public function disableMiddlewareForAllTests()
    {
        //DO NOTHING
    }

    public function withoutMiddleware()
    {
        //DO NOTHING
    }

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();
        $this->setUriPrefix('');
    }

    /**
     *
     */
    public function tearDown()
    {
        $this->setUriPrefix('');
        parent::tearDown();
    }

    public function getStub()
    {
        return [
            'name'     => $this->faker->firstNameMale(),
            'email'    => 'tester',
            'password' => 'tester',
        ];
    }

    /**
     * @test
     */
    public function it_authenticates_with_an_email_and_password_and_receives_a_token()
    {
        //arrange
        $this->make('App\User', [
            'email' => $this->getStub()['email'],
            'password' => Hash::make($this->getStub()['password'])]);

        //act
        $auth = $this->getJson('auth', 'POST', $this->getStub());

        //assert
        $this->assertResponseOk();
        $this->assertObjectHasAttributes($auth, 'token');
    }

    /**
     * @test
     */
    public function it_throws_a_401_with_an_incorrect_password()
    {
        //arrange
        $this->make('App\User', [
            'email' => $this->getStub()['email'],
            'password' => Hash::make($this->getStub()['password'])]);

        //act
        $auth = $this->getJson('auth', 'POST',
            ['email' => 'tester', 'password' => 'tester-incorrect']);

        //assert
        $this->assertResponseStatus(401);
        $this->assertObjectHasAttributes($auth->error, 'message', 'status_code');
    }

    /**
     * @disabled_test
     * Testing auth/refresh-token appears to not be possible with
     * unit tests and should be tested with behavior tests.
     */
    public function it_authenticates_with_a_token_and_refreshes_the_token()
    {
        //arrange
        $this->make('App\User', [
            'email' => $this->getStub()['email'],
            'password' => Hash::make($this->getStub()['password'])]);

        //act
        $new_token = $this->getJson('auth/refresh-token',
            'POST',
            [],
            [],
            [],
            $this->headers(App\User::first()));

        //assert
        $this->assertResponseOk();
        $this->assertObjectHasAttributes($new_token, 'token');
        $this->assertTrue($new_token->token);
        $this->assertTrue($token != $new_token->token);
    }

    /**
     * @test
     */
    public function it_authenticates_with_an_incorrect_token_and_throws_a_400()
    {
        //arrange
        $this->make('App\User', [
            'email' => $this->getStub()['email'],
            'password' => Hash::make($this->getStub()['password'])]);
        $this->setUriPrefix();

        //act
        $response = $this->getJson('counselors', 'GET', ['token' => 'bad-token']);

        //assert
        $this->assertResponseStatus(400);
    }
}
