<?php

namespace App\Common;

use App;
use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
//    /**
//     * Returns a random ID from a Model record for the given class
//     *
//     * @param $class
//     * @return mixed
//     */
//    public static function getRandomId($class)
//    {
//        return self::getRandomRecord($class)->id;
//    }
//
//    /**
//     * Returns a random Model record for the given class
//     *
//     * @param $class
//     * @return mixed
//     */
//    public static function getRandomRecord($class)
//    {
//        // Resolve the class in the IoC container
//        $c = App::make($class);
//
//        // Return the random record
//        return $c::orderByRaw("RAND()")->first();
//    }
}