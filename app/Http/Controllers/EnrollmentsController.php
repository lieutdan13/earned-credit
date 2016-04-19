<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use App\Attendee;
use App\Enrollment;
use App\Program;
use App\Transformers\EnrollmentTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EnrollmentsController extends ApiController
{

    protected $enrollmentTransformer;

    function __construct(EnrollmentTransformer $enrollmentTransformer)
    {
        $this->enrollmentTransformer = $enrollmentTransformer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $enrollments = new Enrollment();

        if ($attendee_id = Input::get('attendee_id'))
        {
            try {
                $attendee = Attendee::findOrFail($attendee_id);
            } catch (ModelNotFoundException $e) {
                return $this->respondUnprocessableEntity('Attendee provided does not exist.');
            }
            $enrollments = $enrollments->where('attendee_id', $attendee->id);
        }

        if ($program_id = Input::get('program_id'))
        {
            try {
                $program = Program::findOrFail($program_id);
            } catch (ModelNotFoundException $e) {
                return $this->respondUnprocessableEntity('Program provided does not exist.');
            }
            $enrollments = $enrollments->where('program_id', $program->id);
        }

        return $this->respond([
            'data' => $this->enrollmentTransformer->transformCollection($enrollments->get()->all())
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        if(!Input::get('attendee_id') or !Input::get('program_id'))
        {
            return $this->respondUnprocessableEntity('Parameters failed validation for an enrollment.');
        }

        try {
            $attendee = Attendee::findOrFail(Input::get('attendee_id'));
        } catch (ModelNotFoundException $e) {
            return $this->respondUnprocessableEntity('Attendee provided does not exist.');
        }

        try {
            $program = Program::findOrFail(Input::get('program_id'));
        } catch (ModelNotFoundException $e) {
            return $this->respondUnprocessableEntity('Program provided does not exist.');
        }

        $existingEnrollment = Enrollment::where('attendee_id', $attendee->id)
            ->where('program_id', $program->id)
            ->whereNull('enrollments.deleted_at')
            ->get();
        if(count($existingEnrollment))
        {
            return $this->respondUnprocessableEntity('The enrollment of the attendee to the program already exists.');
        }

        //We pass validation. Now add the enrollment.
        Enrollment::create(Input::all());
        return $this->respondCreated('Enrollment created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $enrollment = Enrollment::find($id);

        if(!$enrollment)
        {
            return $this->respondNotFound('Enrollment does not exist.');
        }

        return $this->respond([
            'data' => $this->enrollmentTransformer->transform($enrollment)
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
        $enrollment = Enrollment::find($id);

        if(!$enrollment)
        {
            return $this->respondNotFound('Enrollment does not exist.');
        }

        if($enrollment->update(Input::except('attendee_id', 'program_id')))
        {
            return $this->respond(['message' => 'The enrollment has been updated successfully.']);
        }
        else {
            return $this->respondUnprocessableEntity('There was a problem updating the enrollment.');
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
        $enrollment = Enrollment::find($id);

        if(!$enrollment)
        {
            return $this->respondNotFound('Enrollment does not exist.');
        }

        if($enrollment->delete())
        {
            return $this->respond(['message' => 'The enrollment has been deleted.']);
        }
        else {
            return $this->respondUnprocessableEntity('There was a problem deleting the enrollment.');
        }
    }
}
