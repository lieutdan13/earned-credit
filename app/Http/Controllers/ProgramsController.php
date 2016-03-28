<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use App\Program;
use App\Transformers\ProgramTransformer;

class ProgramsController extends ApiController
{

    protected $programTransformer;

    function __construct(ProgramTransformer $programTransformer)
    {
        $this->programTransformer = $programTransformer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $programs = Program::all();

        return $this->respond([
            'data' => $this->programTransformer->transformCollection($programs->all())
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
        if(!Input::get('name'))
        {
            return $this->respondUnprocessableEntity('Parameters failed validation for a program.');
        }

        Program::create(Input::all());
        return $this->respondCreated('Program successfully created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $program = Program::find($id);

        if(!$program)
        {
            return $this->respondNotFound('Program does not exist.');
        }

        return $this->respond([
            'data' => $this->programTransformer->transform($program)
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
        $program = Program::find($id);

        if(!$program)
        {
            return $this->respondNotFound('Program does not exist.');
        }

        if($program->update(Input::all()))
        {
            return $this->respond(['message' => 'The program has been successfully updated.']);
        }
        else {
            return $this->respondUnprocessableEntity('There was a problem updating the program.');
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
        $program = Program::find($id);

        if(!$program)
        {
            return $this->respondNotFound('Program does not exist.');
        }

        if($program->delete())
        {
            return $this->respond(['message' => 'The program has been deleted.']);
        }
        else {
            return $this->respondUnprocessableEntity('There was a problem deleting the program.');
        }
    }
}
