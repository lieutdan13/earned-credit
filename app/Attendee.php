<?php

namespace App;

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
        return $this->belongsToMany('App\Counselor')->orderBy('created_at', 'desc');
    }

    /**
     * @return The current counselor.
     */
    public function getCounselorAttribute()
    {
        return $this->counselors()->first();
    }
}
