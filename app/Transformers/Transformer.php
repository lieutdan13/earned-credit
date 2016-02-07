<?php

namespace App\Transformers;

use League\Fractal;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

abstract class Transformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var  array
     */
    protected $availableIncludes = [];

    /**
     * List of resources to automatically include
     *
     * @var  array
     */
    protected $defaultIncludes = [];

    /**
     *  Function transformCollection
     *
     *  This function transforms a collection of items,
     *  according to the abstract transform method.
     *
     *  @param array $items
     *  @return array A collection of items.
     */
    public function transformCollection(array $items)
    {
        return array_map([$this, 'transform'], $items);
    }

    /**
     * Transform object into a generic array
     *
     * @param $item
     */
    public abstract function transform($item);

}
