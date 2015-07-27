<?php

namespace App\Transformers\Bids;

use App\Transformers\BaseTransformer;

class BidStoreTransformer extends BaseTransformer
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