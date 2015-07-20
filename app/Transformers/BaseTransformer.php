<?php

namespace App\Transformers;

abstract class BaseTransformer
{
    /**
     * Transforms a collection of data
     *
     * @param $items
     * @return array
     */
    public function transformMany(array $items)
    {
        return array_map([$this, 'transform'], $items);
    }

    /**
     * Transforms a single piece of data
     *
     * @param $item
     * @return mixed
     */
    public abstract function transform($item);
}