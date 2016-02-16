<?php

namespace App;

class Counselor extends \Eloquent {
    protected $fillable = ['identifier', 'first_name', 'last_name', 'suffix', 'hire_date', 'termination_date'];
}
