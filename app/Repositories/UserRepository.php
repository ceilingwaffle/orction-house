<?php

namespace App\Repositories;

use DB;

class UserRepository extends Repository
{
    /**
     * Fetches an array of user feedback data (feedback counts)
     *
     * @param $username
     * @return array
     */
    public function getUserFeedbackData($username)
    {
        $this->pdoBindings['username'] = $username;

        $query = "SELECT t1.user_id, u.username, concat_ws(':',group_concat(t1.feedback_type_id ORDER BY t1.feedback_type_id),
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
                    INNER JOIN users u ON u.id = t1.user_id
                    WHERE u.username = :username
                    GROUP BY user_id;";

        // Fetch the results
        $results = DB::select(DB::raw($query), $this->pdoBindings);

        if (!isset($results[0])) {
            return [];
        }

        return $results[0];
    }
}