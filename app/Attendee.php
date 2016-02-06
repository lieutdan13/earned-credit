<?php

namespace App;

class Attendee extends \Eloquent {
    protected $fillable = ['first_name', 'last_name', 'suffix', 'identifier'];
}
