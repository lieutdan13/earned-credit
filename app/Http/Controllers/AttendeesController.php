<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use App\Attendee;
use App\Transformers\AttendeeTransformer;

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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
            return $this->respondNotFound('Attendee does not exist');
        }

        return $this->respond([
            'data' => $this->attendeeTransformer->transform($attendee)
        ]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
