<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use App\Attendee;
use App\Counselor;
use App\Program;
use App\Transformers\AttendeeTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AttendeesController extends ApiController
{

    protected $attendeeTransformer;

    function __construct(AttendeeTransformer $attendeeTransformer)
    {
        $this->attendeeTransformer = $attendeeTransformer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $attendees = Attendee::all();

        return $this->respond([
            'data' => $this->attendeeTransformer->transformCollection($attendees->all())
        ]);
    }

    /**
     * Displays a listing of the attendees that the counselor has.
     *
     * @return \Illuminate\Http\Response
     */
    public function byCounselor($counselorId = null)
    {
        $attendees = $counselorId ? Counselor::findOrFail($counselorId)->attendees->all() : [];

        return $this->respond([
            'data' => $this->attendeeTransformer->transformCollection($attendees)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        if(!Input::get('first_name') or !Input::get('last_name') or !Input::get('identifier'))
        {
            return $this->respondUnprocessableEntity('Parameters failed validation for an attendee.');
        }

        Attendee::create(Input::all());
        return $this->respondCreated('Attendee successfully created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $attendee = Attendee::find($id);

        if(!$attendee)
        {
            return $this->respondNotFound('Attendee does not exist.');
        }

        return $this->respond([
            'data' => $this->attendeeTransformer->transform($attendee)
        ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $attendee = Attendee::find($id);

        if(!$attendee)
        {
            return $this->respondNotFound('Attendee does not exist.');
        }

        if($attendee->update(Input::all()))
        {
            return $this->respond(['message' => 'The attendee has been successfully updated.']);
        }
        else {
            return $this->respondUnprocessableEntity('There was a problem updating the attendee.');
        }
    }

    /**
     * Update the counselor associated with the attendee.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateCounselor($id)
    {
        $attendee = Attendee::find($id);

        if(!$attendee)
        {
            return $this->respondNotFound('Attendee does not exist.');
        }

        $counselorId = Input::get('counselor_id');
        if(!$counselorId)
        {
            return $this->respondUnprocessableEntity('Parameters failed validation for an attendee.');
        }

        try {
            $newCounselor = Counselor::findOrFail($counselorId);
        } catch (ModelNotFoundException $e) {
            return $this->respondUnprocessableEntity('Counselor provided does not exist');
        }

        //We pass validation. Now softDelete the existing counselor and replace it.
        $currentCounselor = $attendee->counselor;
        if ($currentCounselor->id != $newCounselor->id)
        {
            $attendee->reassignCounselor($newCounselor->id);
            return $this->respond(['message' => "The attendee/counselor assignment has been updated."]);
        }

        return $this->respond(['message' => "The attendee/counselor assignment remains unchanged."]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $attendee = Attendee::find($id);

        if(!$attendee)
        {
            return $this->respondNotFound('Attendee does not exist.');
        }

        if($attendee->delete())
        {
            return $this->respond(['message' => 'The attendee has been deleted.']);
        }
        else {
            return $this->respondUnprocessableEntity('There was a problem deleting the attendee.');
        }
    }
}
