<?php
use App\Http\Controllers\ApiController;

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
Route::post('auth', ['uses' => 'AuthenticateController@authenticate']);
Route::post('auth/refresh-token', ['uses' => 'AuthenticateController@refresh_token']);

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
