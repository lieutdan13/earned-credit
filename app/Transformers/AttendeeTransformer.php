<?php

namespace App\Transformers;

class AttendeeTransformer extends Transformer
{
    /**
     *  This function transforms a single attendee.
     *
     *  @param $item And attendee
     *  @return array Returns an attendee,
     *  according to specified fields.
     */
    public function transform($item)
    {
        return [
            'first_name' => $item['first_name'],
            'last_name'  => $item['last_name'],
            'suffix'     => $item['suffix'],
            'identifier' => $item['identifier'],
        ];
    }

}
