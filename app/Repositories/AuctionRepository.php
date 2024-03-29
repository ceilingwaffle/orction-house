<?php

namespace App\Repositories;

use App\Auction;
use Carbon\Carbon;
use DB;

class AuctionRepository extends Repository
{
    /**
     * Returns an array of auction listings
     *
     * @param array $params
     * @return array
     */
    public function getAuctions(array $params = [])
    {
        // Set up the where statements and PDO bindings depending on the URL filter-parameters provided
        $whereParams = [];

        if (isset($params['title']) && !empty($params['title'])) {
            $whereParams['title'] = [
                'urlParam' => 'title',
                'columnName' => 'auction_title',
                'whereStatement' => " AND a.title LIKE CONCAT('%', :title, '%') "
            ];
        }

        if (isset($params['category']) && !empty($params['category'])) {
            $whereParams['category'] = [
                'urlParam' => 'category',
                'columnName' => 'auction_category_id',
                'whereStatement' => " AND acat.id = :category "
            ];
        }

        if (isset($params['min_price']) && !empty($params['min_price'])) {
            $whereParams['min_price'] = [
                'urlParam' => 'min_price',
                'columnName' => 'highest_bid_amount',
                'whereStatement' => " AND b1.amount >= :min_price "
            ];
        }

        if (isset($params['max_price']) && !empty($params['max_price'])) {
            $whereParams['max_price'] = [
                'urlParam' => 'max_price',
                'columnName' => 'highest_bid_amount',
                'whereStatement' => " AND b1.amount <= :max_price "
            ];
        }

        if (isset($params['auction_id']) && !empty($params['auction_id'])) {
            $whereParams['auction_id'] = [
                'urlParam' => 'auction_id',
                'columnName' => 'auction_id',
                'whereStatement' => " AND a.id = :auction_id "
            ];
        }

        // Apply the bindings and where filters
        $this->prepareQueryFilters($params, $whereParams);

        $orderBy = empty($this->orderBy) ? "" : "ORDER BY {$this->orderBy} {$this->orderByDirection}";

        // Prepare the database query
        $query = "SELECT
             a.id AS 'auction_id'
             , a.title AS 'auction_title'
             , a.end_date AS 'auction_end_date'
             , CASE
                  WHEN (b1.amount IS NOT NULL AND a.end_date <= now()) THEN 'sold'
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
             , a.description as 'auction_description'
             , a.created_at as 'auction_listed_at'
             , a.updated_at as 'auction_updated_at'
             , a.description as 'auction_description'
             , a.start_price as 'auction_start_price'
             , CASE
                  WHEN f2.left_by_user_id IS NOT NULL THEN f2.left_by_username
                  ELSE null
               END AS 'feedback_left_by_username'
            FROM auctions a
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
            LEFT OUTER JOIN (
              -- User who left feedback
              SELECT f.auction_id, f.left_by_user_id, u.username as 'left_by_username'
              FROM feedback f
              INNER JOIN users u ON u.id = f.left_by_user_id
            ) f2 ON f2.auction_id = a.id
            WHERE 1 {$this->whereStatements}
            {$orderBy};";

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
     * Returns an array of auction conditions and their IDs
     *
     * @return array
     */
    public function getAuctionConditions()
    {
        $query = "SELECT ac.id, ac.condition
                  FROM auction_conditions ac
                  ORDER BY ac.id;";

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

    /**
     * Returns a set of data for a single auction
     *
     * @param $auctionId
     * @return array
     */
    public function getAuctionViewData($auctionId)
    {
        $params['auction_id'] = $auctionId;

        return $this->getAuctions($params)[0];
    }

    /**
     * Returns true if the given auction ID exists
     *
     * @param $id
     * @return mixed
     */
    public function isValidAuctionId($id)
    {
        return !is_null(Auction::find($id));
    }

//    /**
//     * Returns the minimum bid allowed for an auction for the given user
//     *
//     * @param $auctionId
//     * @param User $user
//     * @return mixed
//     */
//    public function getMinimumBidForUser($auctionId, User $user)
//    {
//        $auction = Auction::findOrFail($auctionId);
//
//        return $auction->calculateMinimumBidForUser($user);
//    }

    /**
     * Returns true if the auction is owned by the user
     *
     * @param $auctionId
     * @param $userId
     * @return bool
     */
    public function isAuctionOwner($auctionId, $userId)
    {
        $auction = Auction::where('id', '=', $auctionId)->where('user_id', '=', $userId)->first();

        return !is_null($auction);
    }

    /**
     * Returns true if bids are allowed to be placed on this auction
     *
     * @param $auctionId
     * @return bool
     */
    public function isOpenForBidding($auctionId)
    {
        $auction = $this->getAuctionViewData($auctionId);

        return !Auction::auctionHasEnded($auction->auction_status);
    }

    /**
     * Inserts a new auction record into the database
     *
     * @param array $auctionData
     * @return Auction
     */
    public function createAuction(array $auctionData)
    {
        $auction = new Auction([
            'title' => $auctionData['title'],
            'description' => $auctionData['description'],
            'start_price' => $auctionData['start_price'],
            'end_date' => $auctionData['end_date'],
            'image_file_name' => $auctionData['image_file_name'],
            'auction_category_id' => $auctionData['auction_category_id'],
            'auction_condition_id' => $auctionData['auction_condition_id'],
            'user_id' => $auctionData['user_id'],
        ]);

        $auction->save();

        return $auction;
    }

    /**
     * Inserts a new auction record into the database
     *
     * @param $id
     * @param array $auctionData
     * @return Auction
     */
    public function updateAuction($id, array $auctionData)
    {
        $auction = Auction::findOrFail($id);

        // Check if we should delete the existing photo
        if ($auctionData['delete_existing_photo'] == '1') {
            $auction->image_file_name = null;
        }

        // Include the new photo if one has been uploaded
        if (isset($auctionData['photo_file'])) {
            $auction->image_file_name = $auctionData['photo_file'];
        }

        if (isset($auctionData['end_date'])) {
            $auction->end_date = $auctionData['end_date'];
        }

        $auction->title = $auctionData['title'];
        $auction->description = $auctionData['description'];
        $auction->auction_category_id = $auctionData['auction_category_id'];
        $auction->auction_condition_id = $auctionData['auction_condition_id'];

        $auction->save();

        return $auction;
    }

    /**
     * Returns an array of auction data to be displayed on the auction edit form
     *
     * @param $auctionId
     * @return array
     */
    public function getAuctionEditFormData($auctionId)
    {
        $this->pdoBindings['auction_id'] = $auctionId;

        $query = "SELECT a.title AS 'auction_title',
                          a.description AS 'auction_description',
                          a.auction_category_id AS 'auction_category_id',
                          a.auction_condition_id AS 'auction_condition_id',
                          a.start_price AS 'auction_start_price',
                          a.end_date AS 'auction_end_date',
                          a.image_file_name AS 'auction_photo_file_name'
                  FROM auctions a
                  WHERE a.id = :auction_id;";

        $results = DB::select(DB::raw($query), $this->pdoBindings);

        return $results[0];
    }

    /**
     * Returns the end date of the auction with the given ID
     *
     * @param $auctionId
     * @return mixed
     */
    public function getAuctionEndDate($auctionId)
    {
        return Auction::findOrFail($auctionId)->end_date;
    }

    /**
     * Returns true if an auction has ended
     *
     * @param $auctionId
     * @return bool
     */
    public function auctionHasEnded($auctionId)
    {
        $endDate = $this->getAuctionEndDate($auctionId);
        $endDate = Carbon::createFromTimestamp(strtotime($endDate));

        return $endDate->isPast();
    }

    /**
     * Gets the title of an auction
     *
     * @param $auctionId
     * @return string
     */
    public function getAuctionTitle($auctionId)
    {
        $title = Auction::findOrFail($auctionId)->title;

        return ucfirst($title);
    }

    /**
     * Returns the user ID of the winner of an auction
     *
     * @param $auctionId
     * @return mixed
     */
    public function getAuctionWinnerUserId($auctionId)
    {
        $this->pdoBindings['auction_id'] = $auctionId;

        $query = "SELECT b.id, b.auction_id, b.amount, b.created_at, u.id as 'user_id'
                    FROM (
                        SELECT b1.*
                        FROM bids AS b1
                        LEFT JOIN bids AS b2
                        ON (b1.auction_id = b2.auction_id AND b1.amount < b2.amount)
                        WHERE b2.amount IS NULL
                    ) b
                    INNER JOIN users u ON u.id = b.user_id
                    WHERE b.auction_id = :auction_id;";

        $results = DB::select(DB::raw($query), $this->pdoBindings);

        return $results[0]->user_id;
    }

    /**
     * Returns true if the user is the auction winner
     *
     * @param $userId
     * @param $auctionId
     * @return bool
     */
    public function userIsAuctionWinner($userId, $auctionId)
    {
        return $userId === $this->getAuctionWinnerUserId($auctionId);
    }

    /**
     * Returns the username of the auction seller if the auction exists
     *
     * @param $auctionId
     * @return null
     */
    public function getAuctionSellerUsername($auctionId)
    {
        $auction = Auction::where('id', '=', $auctionId)->with('user')->first();

        if (!$auction) {
            return null;
        }

        return $auction->user->username;
    }

}