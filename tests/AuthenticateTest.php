<?php

class AuthenticateTest extends ApiTester 
{
    use Factory;

    protected $token;

    public function disableMiddlewareForAllTests()
    {
        //DO NOTHING
    }

    public function withoutMiddleware()
    {
        //DO NOTHING
    }

    public function getStub()
    {
        return [
            'name'     => $this->faker->firstNameMale(),
            'email'    => 'tester',
            'password' => 'tester',
        ];
    }

    public function setUp()
    {
        parent::setUp();
        $this->make('App\User', [
            'email' => $this->getStub()['email'],
            'password' => Hash::make($this->getStub()['password'])]);

        $this->token = (string) JWTAuth::attempt(['email' => $this->getStub()['email'], 'password' => $this->getStub()['password']]);
    }


    /**
     * @test
     */
    public function it_authenticates_with_an_email_and_password_and_receives_a_token()
    {
        //arrange

        //act
        $auth = $this->getJson('authenticate', 'POST', $this->getStub());

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

        //act
        $auth = $this->getJson('authenticate', 'POST',
            ['email' => 'tester', 'password' => 'tester-incorrect']);

        //assert
        $this->assertResponseStatus(401);
        $this->assertObjectHasAttributes($auth->error, 'message', 'status_code');
    }

    /**
     * @disable_test
     * Disabled until I can figure out how to authenticate using tokens
     * and headers within a test.
     */
    public function it_gets_the_authenticated_user()
    {
        //arrange

        //act
        $response = $this->getJson('authenticate/user',
            'GET',
            [],
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer ' . (string) $this->token]
            );

        //assert
        //$this->assertResponseOk();
        $this->assertContains($response->error->message, (string) $this->token);
        $this->assertObjectHasAttributes($response, 'token');
        $this->assertObjectHasAttributes($response, 'data');
        $this->assertObjectHasAttributes($response->data,
            'name',
            'email'
        );
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
        $new_token = $this->getJson('authenticate/refresh-token',
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
        $this->setUriPrefix();

        //act
        $response = $this->getJson('counselors', 'GET', ['token' => 'bad-token']);

        //assert
        $this->assertResponseStatus(400);
    }
}
