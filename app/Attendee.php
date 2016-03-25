<?php

namespace App;

use DB;
use Carbon\Carbon;

/**
 * Class Attendee
 */
class Attendee extends \Eloquent {
    /**
     * @var array
     */
    protected $fillable = ['first_name', 'last_name', 'suffix', 'identifier'];

    /**
     * @return A relationship of all the counselors
     */
    public function counselors()
    {
        return $this->belongsToMany('App\Counselor')
            ->whereNull('attendee_counselor.deleted_at')
            ->withTimestamps()
            ->orderBy('created_at', 'desc');
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
}
