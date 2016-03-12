<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response as IlluminateResponse;
use JWTAuth;

/**
 * Class ApiController
 */
class ApiController extends Controller
{

    /**
     * @var int Status Code
     */
    protected $statusCode = 200;

    /**
     * Getter method to return State Code
     *
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Setter method for Status Code.
     * Returns the current object for chaining.
     *
     * @param mixed $statusCode
     * @return current object.
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = (int) $statusCode;

        return $this;
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function respondBadRequestError($message = 'Bad Request!')
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_BAD_REQUEST)
            ->respondWithError($message);
    }

    /**
     * Method to be used for an Unauthorized response.
     *
     * @param string $message
     * @return mixed
     */
    public function respondUnauthorizedError($message = 'Unauthorized!')
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_UNAUTHORIZED)
            ->respondWithError($message);
    }

    /**
     * Method to be used for a Forbidden response.
     *
     * @param string $message
     * @return mixed
     */
    public function respondForbiddenError($message = 'Forbidden!')
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_FORBIDDEN)
            ->respondWithError($message);
    }

    /**
     * Method to be used for a Not Found response.
     *
     * @param string $message
     * @return mixed
     */
    public function respondNotFound($message = 'Not Found')
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_NOT_FOUND)
            ->respondWithError($message);
    }

    /**
     * Method to be used for an Internal Error response.
     *
     * @param string $message
     * @return mixed
     */
    public function respondInternalError($message = 'Internal Error!')
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_INTERNAL_SERVER_ERROR)
            ->respondWithError($message);
    }

    /**
     * Method to be used for a Service Unavailable response.
     *
     * @param string $message
     * @return mixed
     */
    public function respondServiceUnavailable($message = "Service Unavailable!")
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_SERVICE_UNAVAILABLE)
            ->respondWithError($message);
    }

    /**
     * Returns a reponse in json form.
     *
     * @param $data Data to be used in the response
     * @param array $headers Headers to be used in response
     * @return mixed Return the response
     */
    public function respond($data, $headers = [])
    {
        $data['token'] = (string) JWTAuth::getToken();
        $data['status_code'] = $this->getStatusCode();
        $headers['Authorization'] = "Bearer " . JWTAuth::getToken();
        return response()->json($data, $this->getStatusCode(), $headers);
    }

    /**
     * Returns a response with an error code and message.
     *
     * @param $message
     * @return mixed
     */
    public function respondWithError($message, $extra_data=[])
    {
        $response_data = [
            'error' => [
                'message' => $message,
                'status_code' => $this->getStatusCode()
            ]
        ];
        if (!empty($extra_data))
        {
            $response_data['extra'] = $extra_data;
        }
        return $this->respond($response_data);
    }

    /**
     * @param $message
     * @return mixed
     */
    protected function respondCreated($message)
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_CREATED)
            ->respond(['message' => $message]);
    }

    /**
     * @param $message
     * @return mixed
     */
    protected function respondUnprocessableEntity($message)
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->respond(['message' => $message]);
    }

    /**
     * 
     */
    public function getAuthenticatedUser()
    {
        if (! $user = JWTAuth::parseToken()->authenticate())
        {
            return $this->respondNotFound("user not found with given token");
        }

        // the token is valid and we have found the user via the sub claim
        $this->user = $user;
    }
}
