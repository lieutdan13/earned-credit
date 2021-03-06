<?php

namespace App;

use DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Attendee
 */
class Attendee extends \Eloquent {

    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'suffix',
        'identifier'
    ];

    /**
     * @return A relationship of all the counselors
     */
    public function counselors()
    {
        return $this->belongsToMany('App\Counselor')
            ->whereNull('attendee_counselor.deleted_at')
            ->withTimestamps()
            ->orderBy('attendee_counselor.created_at', 'desc');
    }

    /**
     * @return The current counselor.
     */
    public function getCounselorAttribute()
    {
        return $this->counselors()->first();
    }

    public function reassignCounselor($counselor_id)
    {
        DB::table('attendee_counselor')
            ->where('attendee_id', $this->id)
            ->where('counselor_id', $this->counselor->id)
            ->whereNull('deleted_at')
            ->update(array('deleted_at' => Carbon::now()));
        $this->counselors()->attach([$counselor_id]);
    }

    /**
     * @return A relationship of all the program enrollments
     */
    public function enrollments()
    {
        return $this->hasMany('App\Enrollment')
            ->whereNull('enrollments.deleted_at')
            ->withTimestamps()
            ->withPivot('start_date', 'completion_date', 'termination_date')
            ->orderBy('enrollments.created_at', 'asc');
    }
}
