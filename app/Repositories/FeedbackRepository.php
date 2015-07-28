<?php

namespace App\Repositories;

use App\Feedback;
use App\FeedbackType;
use DB;

class FeedbackRepository extends Repository
{
    /**
     * Returns an array of feedback sent to the given user by other users
     *
     * @param $username
     * @return array
     */
    public function getFeedbackSentToUser($username)
    {
        $query = "SELECT
                      u2.username as 'left_by_username'
                    , a.id as 'auction_id'
                    , a.title as 'auction_title'
                    , b.amount as 'auction_winning_bid_amount'
                    , ft.type as 'feedback_type'
                    , f.message as 'feedback_message'
                    , f.created_at as 'feedback_date'
                    FROM users u
                    inner join auctions a on a.user_id = u.id
                    inner join (
                        -- Highest bidder info
                        SELECT u.id as 'highest_bidder_user_id', amount, b.auction_id
                        FROM (
                            SELECT b1.*
                            FROM bids AS b1
                            LEFT JOIN bids AS b2
                            ON (b1.auction_id = b2.auction_id AND b1.amount < b2.amount)
                            WHERE b2.amount IS NULL
                        ) b
                        inner join users u on u.id = b.user_id
                    ) b on b.auction_id = a.id
                    inner join feedback f on a.id = f.auction_id
                    inner join feedback_types ft on ft.id = f.feedback_type_id
                    inner join users u2 on f.left_by_user_id = u2.id
                    where u.username = :username
                    order by f.created_at desc;";

        $this->pdoBindings['username'] = $username;

        // Fetch the results
        $results = DB::select(DB::raw($query), $this->pdoBindings);

        return $results;
    }

    /**
     * Fetches the feedback types and ID's
     *
     * @return array
     */
    public function getFeedbackTypes()
    {
        $query = "SELECT ft.id, ft.type
                  FROM feedback_types ft
                  ORDER BY ft.id;";

        $results = DB::select(DB::raw($query));

        return $results;
    }

    /**
     * Returns true if feedback has been assigned to an auction
     *
     * @param $auctionId
     * @return mixed
     */
    public function auctionHasFeedback($auctionId)
    {
        return ! is_null(Feedback::where('auction_id', '=', $auctionId)->first());
    }

    /**
     * Returns true if the provided feedback type ID exists
     *
     * @param $feedbackTypeId
     * @return bool
     */
    public function isValidFeedbackTypeId($feedbackTypeId)
    {
        return FeedbackType::all(['id'])->contains('id', $feedbackTypeId);
    }

    /**
     * Inserts a new feedback record into the database
     *
     * @param array $feedbackData
     * @return Feedback
     */
    public function createFeedback(array $feedbackData)
    {
        $feedback = new Feedback([
            'feedback_type_id' => $feedbackData['feedback_type_id'],
            'message' => $feedbackData['message'],
            'auction_id' => $feedbackData['auction_id'],
            'left_by_user_id' => $feedbackData['left_by_user_id'],
        ]);

        $feedback->save();

        return $feedback;
    }
}