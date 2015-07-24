<?php

namespace App\Repositories;

use App\Bid;

class BidRepository extends Repository
{
    /**
     * Stores a bid record in the database
     *
     * @param $bidAmount
     * @param $auctionId
     * @param $userId
     * @return bool
     */
    public function storeBid($bidAmount, $auctionId, $userId)
    {
        $bid = new Bid;

        $bid->amount = $bidAmount;
        $bid->auction_id = $auctionId;
        $bid->user_id = $userId;

        return $bid->save();
    }
}