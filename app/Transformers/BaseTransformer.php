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

        if (!is_numeric($moneyString)) {
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

    /**
     * Adds a $ to the beginning of a currency float value
     *
     * @param $moneyFloat
     * @return string
     */
    protected function toMoneyString($moneyFloat)
    {
        return '$' . $this->toDecimalPlacesFormat($moneyFloat);
    }

    /**
     * Returns a full date time string from a date string with the given format
     *
     * @param $date
     * @param string $format
     * @return string
     */
    protected function createDateTimeStringFromFormat($date, $format = 'd/m/Y')
    {
        return Carbon::createFromFormat($format, $date)->toDateTimeString();
    }

    /**
     * Returns a date string in the format of DD/MM/YYY from a full datetime string
     *
     * @param $dtString
     * @return string
     */
    protected function convertDateTimeStringToDate($dtString)
    {
        return Carbon::createFromTimestamp(strtotime($dtString))->format('d/m/Y');
    }

    /**
     * Returns true if the date string is today
     *
     * @param $dtString
     * @return bool
     */
    protected function dateIsToday($dtString)
    {
        return Carbon::createFromTimestamp(strtotime($dtString))->isToday();
    }
}