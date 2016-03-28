<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Counselor
 */
class Counselor extends \Eloquent {

    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = [
        'identifier',
        'first_name',
        'last_name',
        'suffix',
        'hire_date',
        'termination_date',
    ];

    /**
     * @return A relationship of all attendees that the counselor has.
     */
    public function attendees()
    {
        return $this->belongsToMany('App\Attendee');
    }
}
