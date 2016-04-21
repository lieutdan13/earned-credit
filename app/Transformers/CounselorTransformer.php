<?php

namespace App\Transformers;

class CounselorTransformer extends Transformer
{
    /**
     *  This function transforms a single counselor.
     *
     *  @param $item And counselor
     *  @return array Returns an counselor,
     *  according to specified fields.
     */
    public function transform($item)
    {
        return [
            'id'               => $item['id'],
            'identifier'       => $item['identifier'],
            'first_name'       => $item['first_name'],
            'last_name'        => $item['last_name'],
            'suffix'           => $item['suffix'],
            'hire_date'        => $item['hire_date'],
            'termination_date' => $item['termination_date'],
        ];
    }

}
