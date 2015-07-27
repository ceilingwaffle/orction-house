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
     * Converts a currency string to a float value
     *
     * @param $moneyString
     * @return string
     */
    protected function transformCurrencyStringToFloat($moneyString)
    {
        if (empty($moneyString)) {
            $moneyString = "0.0";
        }

        return preg_replace("/([^0-9\\.])/i", "", $moneyString);
    }

    /**
     * Returns a formatted date string, like: "22 Jul, 2015   12:34:56 PM"
     *
     * @param $dateString
     * @return bool|string
     */
    protected function formatAsPrettyDateAndTime($dateString)
    {
        return date('d M, Y h:i:s a', strtotime($dateString));
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

    /**
     * Transforms a value to a currency format like $1,234.56
     *
     * @param $input
     * @return string
     */
    protected function transformToCurrencyString($input)
    {
        $float = floatval($input);

        return '$' . number_format($float, 2, '.', ',');
    }
}