<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;

use Faker\Factory as Faker;

abstract class ApiTester extends TestCase
{
    use WithoutMiddleware;

    /**
     * @var Faker\Factory
     */
    protected $faker;

    /**
     *
     */
    function __construct()
    {
        $this->faker = Faker::create();
    }

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();
        Artisan::call('migrate');
    }

    /**
     *
     */
    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }

    /**
     * @param $uri
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    protected function getJson($uri, $method = 'GET', $parameters = [], $cookies = [], $files = [], $server = [])
    {
        return json_decode($this->call($method, $uri, $parameters, $cookies, $files, $server)->getContent());
    }


    /**
     * @param \App\User $user
     */
    protected function headers($user = null)
    {
        $headers = ['Accept' => 'application/json'];

        if (!is_null($user)) {
            $token = JWTAuth::fromUser($user);
            JWTAuth::setToken($token);
            $headers['Authorization'] = 'Bearer '.$token;
        }

        return $headers;
    }

    /**
     *
     */
    protected function assertObjectHasAttributes()
    {
        $args = func_get_args();
        $object = array_shift($args);

        foreach($args as $attribute)
        {
            $this->assertObjectHasAttribute($attribute, $object);
        }
    }
}
