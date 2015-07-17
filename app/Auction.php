<?php

namespace App;

use App\Common\BaseModel;

class Auction extends BaseModel
{
    protected $table = 'auctions';

    public function category()
    {
        return $this->hasOne(AuctionCategory::class, 'id', 'auction_category_id');
    }

    public function condition()
    {
        return $this->hasOne(AuctionCondition::class, 'id', 'auction_condition_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function bids()
    {
        return $this->hasMany(Bid::class, 'auction_id', 'id');
    }

    /**
     * Returns the minimum bid allowed for the given user, calculated like so:
     *
     * - If no bids, minimum = start_price
     * - If the user is the highest bidder, minimum = current bid + 0.5
     * - If the user is NOT the highest bidder, and there is only 1 bid, minimum = start_price
     * - If the user is NOT the highest bidder, and there are 2 or more bids, minimum = (2nd highest bid) + 0.5
     *
     * @param User $user
     * @return mixed
     */
    public function calculateMinimumBid(User $user)
    {
        // if nBids = 0
        //      minBid = start_price;

        // if nBids = 1
        //      if (this.user_id) = (highestBidder.user_id)
        //          minBid = highestBid + 0.5
        //      else
        //          minBid = start_price

        // if nBids > 1
        //      if (this.user_id) = (highestBidder.user_id)
        //          minBid = highestBid + 0.5
        //      else
        //          minBid = (2nd highest bid) + 0.5


        $auction = $this->with(['bids' => function($query) {
            $query->orderBy('amount', 'desc')->take(2);
        }])->first();

        // Determine the minimum bid like so:

        if ($auction->bids->count() === 0) {
            $minBid = $this->start_price;
        } elseif ($auction->bids->count() === 1) {
            // if this user id = highest bidder
            if ($user->id === $auction->bids->first()->user_id) {
                $minBid = $auction->bids[0]->amount + 0.5;
            } else {
                $minBid = $this->start_price;
            }
        } else {
            // if this user id = highest bidder
            if ($user->id === $auction->bids->first()->user_id) {
                $minBid = $auction->bids[0]->amount + 0.5;
            } else {
                $minBid = $auction->bids[1]->amount + 0.5;
            }
        }

        return $minBid;

    }
}
