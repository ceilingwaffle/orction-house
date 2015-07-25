<?php

namespace App\Transformers;

use Carbon\Carbon;

abstract class BaseTransformer
{
    /**
     * Transforms a collection of data
     *
     * @param $items
     * @return array
     */
    public function transformMany(array $items)
    {
        return array_map([$this, 'transform'], $items);
    }

    /**
     * Transforms a single piece of data
     *
     * @param $item
     * @return mixed
     */
    public abstract function transform($item);

    /**
     * Returns a human readable string like "2 minutes ago"
     *
     * @param $dateString
     * @return string
     */
    protected function toHumanTimeDifference($dateString)
    {
        $dt = Carbon::createFromTimestamp(strtotime($dateString));

        return $dt->diffForHumans();
    }

    /**
     * Removes the dollar sign from the beginning of the provided string
     *
     * @param $moneyString
     * @return string
     */
    protected function transformMoney($moneyString)
    {
        if (empty($moneyString)) {
            $moneyString = "0.0";
        }

        // Remove the $ from the beginning of the string
        if (substr($moneyString, 0, 1) === '$') {
            $moneyString = ltrim($moneyString, '$');
        }

        if ( ! is_numeric($moneyString)) {
            $moneyString = "0.0";
        }

        return $this->toDecimalPlacesFormat($moneyString, 2);
    }

    /**
     * Returns a string with two decimal places on the end
     *
     * @param $string
     * @param int $decPlaces
     * @return string
     */
    protected function toDecimalPlacesFormat($string, $decPlaces = 2)
    {
        return number_format($string, $decPlaces);
    }

    /**
     * Returns a formatted date string, like: "22 Jul, 2015   12:34:56 PM"
     *
     * @param $dateString
     * @return bool|string
     */
    protected function formatDate($dateString)
    {
        return date('d M, Y    h:i:s a', strtotime($dateString));
    }
}