<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Enrollment
 */
class Enrollment extends \Eloquent {

    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = [
        'attendee_id',
        'program_id',
        'start_date',
        'completion_date',
        'termination_date'
    ];

    /**
     * @return A relationship to the attendee
     */
    public function attendee()
    {
        return $this->belongsTo('App\Attendee')
            ->whereNull('deleted_at')
            ->withTimestamps();
    }

    /**
     * @return A relationship to the program
     */
    public function program()
    {
        return $this->belongsTo('App\Program')
            ->whereNull('deleted_at')
            ->withTimestamps();
    }
}
