<?php

namespace App\Transformers\Feedback;

use App\Transformers\Auctions\AuctionBaseTransformer;

class FeedbackIndexTransformer extends AuctionBaseTransformer
{

    /**
     * Transforms a single piece of data
     *
     * @param $feedback
     * @return mixed
     */
    public function transform($feedback)
    {
        return [
            'left_by_username' => $feedback->left_by_username,
            'auction_id' => $feedback->auction_id,
            'auction_title' => $feedback->auction_title,
            'auction_winning_bid_amount' => $this->transformToCurrencyString($feedback->auction_winning_bid_amount),
            'feedback_type' => $feedback->feedback_type,
            'feedback_message' => $feedback->feedback_message,
            'feedback_date' => $this->toHumanTimeDifference($feedback->feedback_date),
            'user_positive_feedback_count' => $this->feedbackStringToPositiveCount($feedback->user_feedback_type_counts),
        ];
    }
}