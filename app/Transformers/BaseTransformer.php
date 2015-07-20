<?php

namespace App\Transformers;

use Illuminate\Support\Collection;

abstract class BaseTransformer
{
    /**
     * Transforms a collection of data
     *
     * @param Collection $items
     * @return array
     */
    public function transformCollection(Collection $items)
    {
        return array_map([$this, 'transform'], $items->toArray());
    }

    /**
     * Transforms a single piece of data
     *
     * @param $item
     * @return mixed
     */
    public abstract function transform($item);
}