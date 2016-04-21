<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use App\Program;
use App\ProgramLevel;
use App\Transformers\ProgramLevelTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProgramLevelsController extends ApiController
{

    protected $programLevelTransformer;

    function __construct(ProgramLevelTransformer $programLevelTransformer)
    {
        $this->programLevelTransformer = $programLevelTransformer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $program_levels = ProgramLevel::all();

        return $this->respond([
            'data' => $this->programLevelTransformer->transformCollection($program_levels->all())
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        if(!Input::get('name') or !Input::get('program_id'))
        {
            return $this->respondUnprocessableEntity('Parameters failed validation for a program level.');
        }

        try {
            $program = Program::findOrFail(Input::get('program_id'));
        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound('Program provided does not exist');
        }

        ProgramLevel::create(Input::all());
        return $this->respondCreated('Program level successfully created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $program_level = ProgramLevel::find($id);

        if(!$program_level)
        {
            return $this->respondNotFound('Program level does not exist.');
        }

        return $this->respond([
            'data' => $this->programLevelTransformer->transform($program_level)
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
        $program_level = ProgramLevel::find($id);

        if(!$program_level)
        {
            return $this->respondNotFound('Program level does not exist.');
        }

        if($program_level->update(['name' => Input::get('name')]))
        {
            return $this->respond(['message' => 'The program level has been successfully updated.']);
        }
        else {
            return $this->respondUnprocessableEntity('There was a problem updating the program level.');
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
        $program_level = ProgramLevel::find($id);

        if(!$program_level)
        {
            return $this->respondNotFound('Program level does not exist.');
        }

        if($program_level->delete())
        {
            return $this->respond(['message' => 'The program level has been deleted.']);
        }
        else {
            return $this->respondUnprocessableEntity('There was a problem deleting the program level.');
        }
    }
}
