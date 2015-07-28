<?php

namespace App\Transformers\Feedback;

use App\Transformers\BaseTransformer;

class FeedbackStoreTransformer extends BaseTransformer
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
            'feedback_type_id' => $feedback['feedback_type_id'],
            'message' => $feedback['message'],
            'auction_id' => $feedback['auction_id'],
            'left_by_user_id' => $feedback['left_by_user_id'],
        ];
    }
}