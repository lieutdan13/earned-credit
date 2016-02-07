<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Attendee;

class AttendeesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $attendees = Attendee::all();

        return response()->json([
            'data' => $this->transformCollection($attendees->toArray())
        ], 200);
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
        //
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
            return response()->json([
                'error' => [
                    'message' => 'Attendee does not exist'
                ]
            ], 404);
        }

        return response()->json([
            'data' => $this->transform($attendee->toArray())
        ], 200);

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

    private function transformCollection($attendees)
    {
        return array_map([$this, 'transform'], $attendees->toArray());
    }

    private function transform($attendee)
    {
        return [
            'first_name' => $attendee['first_name'],
            'last_name'  => $attendee['last_name'],
            'suffix'     => $attendee['suffix'],
            'identifier' => $attendee['identifier'],
        ];
    }
}
