<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Program
 */
class Program extends \Eloquent {

    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get the program levels for the program
     */
    public function program_levels()
    {
        return $this->hasMany('App\ProgramLevel');
    }
}
