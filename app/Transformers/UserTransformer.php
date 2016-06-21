<?php

namespace App\Transformers;

class UserTransformer extends Transformer
{
    /**
     *  This function transforms a single user.
     *
     *  @param $item A user
     *  @return array Returns a user,
     *  according to specified fields.
     */
    public function transform($item)
    {
        return [
            'id'    => $item['id'],
            'name'  => $item['name'],
            'email' => $item['email'],
        ];
    }

}
