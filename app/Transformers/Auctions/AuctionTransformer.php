<?php

namespace App\Transformers\Auctions;

use App\Exceptions\UnexpectedAuctionFeedbackTypeStringFormatException;
use App\FeedbackType;
use App\Transformers\BaseTransformer;
use Carbon\Carbon;

abstract class AuctionTransformer extends BaseTransformer
{
    private function feedbackStringToValues($feedbackString)
    {
        // Split the string into array values
        $feedbackTypesAndCounts = explode(':', $feedbackString);
        $feedbackTypes = explode(',', $feedbackTypesAndCounts[0]);
        $feedbackCounts = explode(',', $feedbackTypesAndCounts[1]);

        // We expect an equal number of feedback types to feedback counts, otherwise
        // something went wrong with the database query result.
        if (count($feedbackTypes) != count($feedbackCounts)) {
            throw new UnexpectedAuctionFeedbackTypeStringFormatException();
        }

        // Group the feedback values into their feedback types
        $amounts = [];
        for ($i = 0; $i < count($feedbackTypes); $i++) {
            $amounts[$feedbackTypes[$i]] = $feedbackCounts[$i];
        }

        // Set the feedback count values (0 if not the feedback type is not set)
        $positive = (array_key_exists(FeedbackType::POSITIVE, $amounts) ? $amounts[FeedbackType::POSITIVE] : 0);
        $negative = (array_key_exists(FeedbackType::NEGATIVE, $amounts) ? $amounts[FeedbackType::NEGATIVE] : 0);

        return [
            'positive' => $positive,
            'negative' => $negative,
        ];
    }

    /**
     * Converts a feedback string from the database query (like 1,2,3:1,1,2)
     * to its positive-feedback percentage value (e.g. pos,neutral,neg:1,1,2 --> 1 out of 4 positive --> 25%)
     *
     * @param $feedbackString
     * @return mixed
     * @throws UnexpectedAuctionFeedbackTypeStringFormatException
     */
    protected function feedbackStringToPercentage($feedbackString)
    {
        $feedback = $this->feedbackStringToValues($feedbackString);
        $positive = $feedback['positive'];
        $negative = $feedback['negative'];

        // Calculate the percentage of "positive to negative" feedback counts (we ignore neutral feedback in this calculation)
        $total = $positive + $negative;

        if ($total === 0) {
            return null;
        }

        $percentage = round($positive / $total * 100);

        $pcString = $percentage . '%';

        return $pcString;
    }

    /**
     * Gets the number of positive feedback from the string
     *
     * @param $feedbackString
     * @return mixed
     * @throws UnexpectedAuctionFeedbackTypeStringFormatException
     */
    protected function feedbackStringToPositiveCount($feedbackString)
    {
        return $this->feedbackStringToValues($feedbackString)['positive'];
    }

    /**
     * Returns true if an auction has ended
     *
     * @param $auctionEndDateString
     * @return bool
     */
    protected function auctionHasEnded($auctionEndDateString)
    {
        $dt = Carbon::createFromTimestamp(strtotime($auctionEndDateString));

        return $dt->isPast();
    }

    /**
     * Transforms some search parameter values into different values
     *
     * @param array $params
     * @return array
     */
    public function transformSearchParams(array $params)
    {
        if (isset($params['min_price']) && !empty($params['min_price'])) {
            $params['min_price'] = $this->transformMoney($params['min_price']);
        }

        if (isset($params['max_price']) && !empty($params['max_price'])) {
            $params['max_price'] = $this->transformMoney($params['max_price']);
        }

        return $params;
    }
}