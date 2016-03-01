<?php

namespace App\Http\Controllers;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

use Illuminate\Http\Request;

class AuthenticateController extends ApiController
{
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

    public function refresh_token(Request $request)
    {
        $token = JWTAuth::parseToken()->refresh();
        $token = JWTAuth::refresh($token);
        return response()->json(compact('token'));
    }
}
