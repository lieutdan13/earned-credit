<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use App\Attendee;
use App\Counselor;
use App\Transformers\CounselorTransformer;

class CounselorsController extends ApiController
{

    protected $counselorTransformer;

    function __construct(CounselorTransformer $counselorTransformer)
    {
        $this->counselorTransformer = $counselorTransformer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $counselors = Counselor::all();

        return $this->respond([
            'data' => $this->counselorTransformer->transformCollection($counselors->all())
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!Input::get('identifier') or !Input::get('first_name') or !Input::get('last_name'))
        {
            return $this->respondUnprocessableEntity('Parameters failed validation for a counselor.');
        }

        Counselor::create(Input::all());
        return $this->respondCreated('Counselor successfully created.');
    }

    /**
     * Displays the counselor that an attendee is assigned to.
     *
     * @return \Illuminate\Http\Response
     */
    public function byAttendee($attendeeId = null)
    {
        $counselor = $attendeeId ? Attendee::findOrFail($attendeeId)->counselor : null;

        return $this->respond([
            'data' => $this->counselorTransformer->transform($counselor)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $counselor = Counselor::find($id);

        if(!$counselor)
        {
            return $this->respondNotFound('Counselor does not exist.');
        }

        return $this->respond([
            'data' => $this->counselorTransformer->transform($counselor)
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
        $counselor = Counselor::find($id);

        if(!$counselor)
        {
            return $this->respondNotFound('Counselor does not exist.');
        }

        if($counselor->update(Input::all()))
        {
            return $this->respond(['message' => 'The counselor has been successfully updated.']);
        }
        else {
            return $this->respondUnprocessableEntity('There was a problem updating the counselor.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $counselor = Counselor::find($id);

        if(!$counselor)
        {
            return $this->respondNotFound('Counselor does not exist.');
        }

        if($counselor->delete())
        {
            return $this->respond(['message' => 'The counselor has been deleted.']);
        }
        else {
            return $this->respondUnprocessableEntity('There was a problem deleting the counselor.');
        }
    }
}
