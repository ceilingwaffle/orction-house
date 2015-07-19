<?php

namespace App\Repositories;

use DB;

class AuctionRepository
{
    public function getAuctions(array $params = [])
    {
        $query = "SELECT
             a.id AS 'auction_id'
             , a.title AS 'auction_title'
             , a.end_date AS 'auction_end_date'
             , CASE
                  WHEN w.id IS NOT NULL THEN 'sold'
                  WHEN a.end_date > now() THEN 'open'
                  ELSE 'expired'
               END AS 'auction_status'
             , acat.category AS 'auction_category'
             , acon.condition AS 'auction_condition'
             , a.image_file_name AS 'auction_image'
             , u.username AS 'auction_creator_username'
             , CASE
                  WHEN b2.bid_count IS NULL THEN 0
                  ELSE b2.bid_count
               END AS 'total_bids'
             , b1.amount AS 'highest_bid_amount'
             , b1.username AS 'highest_bidder_username'
             , f.feedback_type_counts AS 'user_feedback_type_counts'
            FROM auctions a
            LEFT OUTER JOIN winners w ON w.auction_id = a.id
            LEFT OUTER JOIN auction_categories acat ON acat.id = a.auction_category_id
            LEFT OUTER JOIN auction_conditions acon ON acon.id = a.auction_condition_id
            LEFT OUTER JOIN (
                -- Highest bidder info
                SELECT b.id, b.auction_id, b.amount, b.created_at, u.username
                FROM (
                    SELECT b1.*
                    FROM bids AS b1
                    LEFT JOIN bids AS b2
                    ON (b1.auction_id = b2.auction_id AND b1.amount < b2.amount)
                    WHERE b2.amount IS NULL
                ) b
                INNER JOIN users u ON u.id = b.user_id
            ) b1 ON b1.auction_id = a.id
            LEFT OUTER JOIN (
                -- Total number of bids
                SELECT b.auction_id, count(b.auction_id) AS bid_count
                FROM bids b
                GROUP BY b.auction_id
            ) b2 ON b2.auction_id = a.id
            LEFT OUTER JOIN users u ON u.id = a.user_id
            LEFT OUTER JOIN (
                -- User feedback-type counts
                SELECT t1.user_id, concat_ws(':',group_concat(t1.feedback_type_id ORDER BY t1.feedback_type_id),
                                                 group_concat(t1.feedback_type_count ORDER BY t1.feedback_type_id))
                                             AS 'feedback_type_counts'
                FROM
                (
                    SELECT  u.id AS 'user_id', ft.id AS 'feedback_type_id', count(ft.id) AS 'feedback_type_count'
                    FROM feedback_types ft
                    INNER JOIN feedback f ON f.feedback_type_id = ft.id
                    INNER JOIN auctions a ON a.id = f.auction_id
                    INNER JOIN users u ON u.id = a.user_id
                    GROUP BY u.id, f.feedback_type_id
                ) t1
                GROUP BY user_id
                ORDER BY user_id
            ) f ON f.user_id = a.user_id;";

        $results = DB::select(DB::raw($query));

        return $results;

    }
}