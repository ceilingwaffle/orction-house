<?php

namespace App\Transformers;

class BidTransformer extends BaseTransformer
{
    /**
     * Transforms a bid amount into a different value
     *
     * @param $bidAmount
     * @return mixed
     */
    public function transform($bidAmount)
    {
        return $this->transformCurrencyStringToFloat($bidAmount);
    }
}