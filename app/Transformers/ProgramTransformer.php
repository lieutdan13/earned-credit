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
        $data = [
            'id'   => $item['id'],
            'name' => $item['name'],
        ];
        if(isset($item['pivot']))
        {
            if(isset($item['pivot']['start_date']))
            {
                $data['start_date'] = $item['pivot']['start_date'];
            }
            if(isset($item['pivot']['completion_date']))
            {
                $data['completion_date'] = $item['pivot']['completion_date'];
            }
            if(isset($item['pivot']['termination_date']))
            {
                $data['termination_date'] = $item['pivot']['termination_date'];
            }
        }
        return $data;
    }

}
