<?php

namespace App\Transformers;

class ProgramLevelTransformer extends Transformer
{
    /**
     *  This function transforms a single program level.
     *
     *  @param $item And program level
     *  @return array Returns an program level,
     *  according to specified fields.
     */
    public function transform($item)
    {
        return [
            'id'         => $item['id'],
            'name'       => $item['name'],
            'program_id' => $item['program_id'],
        ];
    }

}
