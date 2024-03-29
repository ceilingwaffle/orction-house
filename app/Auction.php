<?php

namespace App;

use App\Common\BaseModel;
use Exception;

class Auction extends BaseModel
{
    protected $table = 'auctions';

    protected $fillable = [
        'title',
        'description',
        'start_price',
        'end_date',
        'image_file_name',
        'user_id',
        'auction_category_id',
        'auction_condition_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function category()
    {
        return $this->hasOne(AuctionCategory::class, 'id', 'auction_category_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function condition()
    {
        return $this->hasOne(AuctionCondition::class, 'id', 'auction_condition_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bids()
    {
        return $this->hasMany(Bid::class, 'auction_id', 'id');
    }

//    /**
//     * Returns the minimum bid allowed for the given user, calculated like so:
//     *
//     * - If the user is the creator of the auction, show the real highest bid amount
//     * - Otherwise:
//     * - If no bids, minimum = start_price
//     * - If the user is the highest bidder, minimum = current bid + $0.50
//     * - If the user is NOT the highest bidder, and there is only 1 bid, minimum = start_price + $0.50
//     * - If the user is NOT the highest bidder, and there are 2 or more bids, minimum = (2nd highest bid) + $1.00
//     *
//     * @param User $user
//     * @return mixed
//     */
//    public function calculateMinimumBidForUser(User $user)
//    {
//        $auction = $this->where('id', '=', $this->id)->with(['bids' => function($query) {
//            $query->orderBy('amount', 'desc')->take(2);
//        }])->first();
//
//        // If this user is the owner, get the real current bid amount
//        if ($auction->user_id === $user->id) {
//            if ($auction->bids->count() === 0) {
//                $minBid = $this->start_price;
//            } else {
//                $minBid = $auction->bids[0]->amount + 0.5;
//            }
//
//        } else {
//            if ($auction->bids->count() === 0) {
//                $minBid = $this->start_price;
//            } elseif ($auction->bids->count() === 1) {
//                if ($user->id === $auction->bids->first()->user_id) {
//                    $minBid = $auction->bids[0]->amount + 0.5;
//                } else {
//                    $minBid = $this->start_price + 0.5;
//                }
//            } else {
//                if ($user->id === $auction->bids->first()->user_id) {
//                    $minBid = $auction->bids[0]->amount + 0.5;
//                } else {
//                    $minBid = $auction->bids[1]->amount + 1.0;
//                }
//            }
//        }
//
//        return $minBid;
//
//    }
//
//    /**
//     * @param $userId
//     * @return mixed
//     */
//    public function calculateMinimumBidForUserId($userId)
//    {
//        $user = User::findOrFail($userId);
//
//        return $this->calculateMinimumBidForUser($user);
//    }

    /**
     * Returns the minimum bid allowed
     *
     * @param $startPrice
     * @param $currentBid
     * @return mixed
     */
    public static function determineMinimumBid($startPrice, $currentBid)
    {
        return self::determineCurrentAuctionPrice($startPrice, $currentBid) + 0.5;
    }

    /**
     * Returns the current auction price
     *
     * @param $startPrice
     * @param $currentBid
     * @return mixed
     */
    public static function determineCurrentAuctionPrice($startPrice, $currentBid)
    {
        if (empty($currentBid) or $currentBid === 0) {
            return $startPrice;
        } else {
            return $currentBid;
        }
    }

    /**
     * Returns true if an auction has ended
     *
     * @param $auctionStatus
     * @return bool
     * @throws Exception
     */
    public static function auctionHasEnded($auctionStatus)
    {
        if (!is_string($auctionStatus)) {
            throw new Exception("Expected string but got " . gettype($auctionStatus));
        }

        return $auctionStatus != 'open';
    }

    /**
     * Returns the highest bid amount for this auction, or 0.0 if no bids
     *
     * @return float
     */
    public function getHighestBid()
    {
        $auction = $this->with(['bids' => function($query) {
            $query->orderBy('amount', 'desc')->take(1);
        }])->first()->toArray();

        if (isset($auction['bids'][0])) {
            return $auction['bids'][0]['amount'];
        } else {
            return 0.0;
        }
    }

}
