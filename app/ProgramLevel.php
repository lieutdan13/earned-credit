<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ProgramLevel
 */
class ProgramLevel extends \Eloquent {

    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = [
        'program_id',
        'name',
    ];
}
