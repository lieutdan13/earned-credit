<?php

namespace App\Transformers;

class EnrollmentTransformer extends Transformer
{
    /**
     *  This function transforms a single enrollment.
     *
     *  @param $item An enrollment
     *  @return array Returns an enrollment,
     *  according to specified fields.
     */
    public function transform($item)
    {
        return [
            'id'                => $item['id'],
            'attendee_id'       => $item['attendee_id'],
            'program_id'        => $item['program_id'],
            'start_date'        => $item['start_date'],
            'completion_date'   => $item['completion_date'],
            'termination_date'  => $item['termination_date'],
        ];
    }

}
