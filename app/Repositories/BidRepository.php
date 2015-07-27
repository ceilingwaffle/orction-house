<?php

namespace App\Repositories;

use App\Bid;
use DB;

class BidRepository extends Repository
{
    /**
     * Stores a new bid in the database
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

    /**
     * Fetches an array of bids placed on an auction
     *
     * @param $auctionId
     * @return array
     */
    public function getBidsForAuction($auctionId)
    {
        $this->pdoBindings['auction_id'] = $auctionId;

        $query = "select u.username as 'bidder_username'
                        , b.amount as 'bid_amount'
                        , b.created_at 'bid_created_at'
                        , f.feedback_type_counts
                from bids b
                left outer join users u on b.user_id = u.id
                left outer join (
                    -- User feedback-type counts
                    select t1.user_id, concat_ws(':',group_concat(t1.feedback_type_id order by t1.feedback_type_id),
                                                     group_concat(t1.feedback_type_count order by t1.feedback_type_id))
                                                 as 'feedback_type_counts'
                    from
                    (
                        select  u.id as 'user_id', ft.id as 'feedback_type_id', count(ft.id) as 'feedback_type_count'
                        from feedback_types ft
                        inner join feedback f on f.feedback_type_id = ft.id
                        inner join auctions a on a.id = f.auction_id
                        inner join users u on u.id = a.user_id
                        group by u.id, f.feedback_type_id
                    ) t1
                    group by user_id
                    order by user_id
                ) f on f.user_id = b.user_id
                where b.auction_id = :auction_id
                order by b.created_at desc;";

        $results = DB::select(DB::raw($query), $this->pdoBindings);

        return $results;
    }

}