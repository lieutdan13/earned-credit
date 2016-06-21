<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

include 'routes.frontend.php';



Route::group(['prefix' => 'api/v1'], function()
{
    Route::post('authenticate', ['uses' => 'AuthenticateController@authenticate']);
    Route::get('authenticate/user', ['uses' => 'AuthenticateController@getAuthenticatedUser']);
    Route::post('authenticate/refresh-token', ['uses' => 'AuthenticateController@refresh_token']);

    Route::group(['middleware' => 'api'], function()
    {

        Route::resource('attendees', 'AttendeesController');
        Route::get('attendees/{attendeeId}/counselor', 'CounselorsController@byAttendee');
        Route::put('attendees/{attendeeId}/counselor', 'AttendeesController@updateCounselor');

        Route::resource('counselors', 'CounselorsController');
        Route::get('counselors/{counselorId}/attendees', 'AttendeesController@byCounselor');

        Route::resource('enrollments', 'EnrollmentsController');

        Route::resource('programs', 'ProgramsController');
        Route::resource('program_levels', 'ProgramLevelsController');
    });
});
