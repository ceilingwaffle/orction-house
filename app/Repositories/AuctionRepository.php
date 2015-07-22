<?php

namespace App\Repositories;

use DB;

class AuctionRepository extends Repository
{
    /**
     * Returns an array of auction listings
     *
     * @param array $params
     * @return array
     */
    public function getAuctions(array $params)
    {
        // Set up the where statements and PDO bindings depending on the URL filter-parameters provided
        $whereParams = [];

        if (isset($params['title'])) {
            $whereParams['title'] = [
                'urlParam' => 'title',
                'columnName' => 'auction_title',
                'whereStatement' => " AND a.title LIKE CONCAT('%', :title, '%') "
            ];
        }

        if (isset($params['category'])) {
            $whereParams['category'] = [
                'urlParam' => 'category',
                'columnName' => 'auction_category_id',
                'whereStatement' => " AND acat.id = :category "
            ];
        }

        if (isset($params['min_price'])) {
            $whereParams['min_price'] = [
                'urlParam' => 'min_price',
                'columnName' => 'highest_bid_amount',
                'whereStatement' => " AND b1.amount >= :min_price "
            ];
        }

        if (isset($params['max_price'])) {
            $whereParams['max_price'] = [
                'urlParam' => 'max_price',
                'columnName' => 'highest_bid_amount',
                'whereStatement' => " AND b1.amount <= :max_price "
            ];
        }

        // Apply the bindings and where filters
        $this->prepareQueryFilters($params, $whereParams);

        // Prepare the database query
        $query = "SELECT
             a.id AS 'auction_id'
             , a.title AS 'auction_title'
             , a.end_date AS 'auction_end_date'
             , CASE
                  WHEN w.id IS NOT NULL THEN 'sold'
                  WHEN a.end_date > now() THEN 'open'
                  ELSE 'expired'
               END AS 'auction_status'
             , acat.id AS 'auction_category_id'
             , acat.category AS 'auction_category'
             , acon.id AS 'auction_condition_id'
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
                    SELECT u.id AS 'user_id', ft.id AS 'feedback_type_id', count(ft.id) AS 'feedback_type_count'
                    FROM feedback_types ft
                    INNER JOIN feedback f ON f.feedback_type_id = ft.id
                    INNER JOIN auctions a ON a.id = f.auction_id
                    INNER JOIN users u ON u.id = a.user_id
                    GROUP BY u.id, f.feedback_type_id
                ) t1
                GROUP BY user_id
                ORDER BY user_id
            ) f ON f.user_id = a.user_id
            WHERE 1 {$this->whereStatements}
            ORDER BY {$this->orderBy} {$this->orderByDirection};";

        // Fetch the results
        $results = DB::select(DB::raw($query), $this->pdoBindings);

        return $results;
    }

    /**
     * Returns an array of auction categories and their IDs
     *
     * @return array
     */
    public function getAuctionCategories()
    {
        $query = "SELECT ac.id, ac.category
                  FROM auction_categories ac
                  ORDER BY ac.category;";

        $results = DB::select(DB::raw($query));

        return $results;
    }

    /**
     * Returns an array of sortable auction fields
     *
     * @return array
     */
    public function getAuctionSortableFields()
    {
        $sortables = [
            [
                'field' => 'auction_title',
                'name' => 'Item Name',
                'default' => false
            ],
            [
                'field' => 'auction_end_date',
                'name' => 'Time Ending',
                'default' => true
            ],
            [
                'field' => 'auction_status',
                'name' => 'Status',
                'default' => false
            ],
            [
                'field' => 'auction_category_id',
                'name' => 'Item Category',
                'default' => false
            ],
            [
                'field' => 'total_bids',
                'name' => 'Number of Bids',
                'default' => false
            ],
            [
                'field' => 'highest_bid_amount',
                'name' => 'Price',
                'default' => false
            ],
        ];

        // Sort alphabetically by name
        usort($sortables, function ($elem1, $elem2) {
            return strcmp($elem1['name'], $elem2['name']);
        });

        return $sortables;
    }

    /**
     * Returns true if the field name is allowed to be sorted by in the DB query
     *
     * @param $field
     * @return bool
     */
    public function isValidSortableField($field)
    {
        foreach ($this->getAuctionSortableFields() as $validField) {
            if ($field === $validField['field']) {
                return true;
            }
        }

        return false;
    }
}