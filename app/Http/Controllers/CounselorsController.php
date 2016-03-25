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
        if(!Input::get('identifier') or !Input::get('first_name') or !Input::get('last_name'))
        {
            return $this->respondUnprocessableEntity('Parameters failed validation for an counselor.');
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
            return $this->respondNotFound('Counselor does not exist');
        }

        return $this->respond([
            'data' => $this->counselorTransformer->transform($counselor)
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
