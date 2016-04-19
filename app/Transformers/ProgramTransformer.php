<?php

namespace App\Transformers;

class ProgramTransformer extends Transformer
{
    /**
     *  This function transforms a single program.
     *
     *  @param $item And program
     *  @return array Returns an program,
     *  according to specified fields.
     */
    public function transform($item)
    {
        return [
            'id'   => $item['id'],
            'name' => $item['name'],
        ];
    }

}
