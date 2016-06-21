<?php

namespace App\Http\Controllers;

use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;


class AuthenticateController extends ApiController
{

    protected $userTransformer;

    function __construct(UserTransformer $userTransformer)
    {
        $this->userTransformer = $userTransformer;
    }

    public function authenticate(Request $request)
    {
        // grab credentials from the request
        $credentials = $request->only('email', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return $this->respondUnauthorizedError();
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return $this->respondInternalError();
        }

        // all good so return the token
        return response()->json(compact('token'));
    }

    public function refresh_token()
    {
        $token = JWTAuth::parseToken()->refresh();
        $token = JWTAuth::refresh($token);
        return response()->json(compact('token'));
    }

    public function getAuthenticatedUser()
    {
        $user = JWTAuth::parseToken()->authenticate();

        return $this->respond([
            'data' => $this->userTransformer->transform($user)
        ]);
    }
}
