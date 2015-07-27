<?php

//use Monolog\Logger;
//use Monolog\Handler\StreamHandler;
//
//if (Config::get('database.log', false)) {
//    Event::listen('illuminate.query', function($query, $bindings, $time, $name) {
//        $data = compact('bindings', 'time', 'name');
//
//        // Format binding data for sql insertion
//        foreach ($bindings as $i => $binding) {
//            if ($binding instanceof \DateTime) {
//                $bindings[$i] = $binding->format('\'Y-m-d H:i:s\'');
//            } else if (is_string($binding)) {
//                $bindings[$i] = "'$binding'";
//            }
//        }
//
//        // Insert bindings into query
//        $query = str_replace(array('%', '?'), array('%%', '%s'), $query);
//        $query = vsprintf($query, $bindings);
//
//        $log = new Logger('sql');
//        $log->pushHandler(new StreamHandler(storage_path().'/logs/sql-' . date('Y-m-d') . '.log', Logger::INFO));
//
//        // add records to the log
//        $log->addInfo($query, $data);
//    });
//}

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return redirect('/auctions');
});

// Authentication routes
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Registration routes
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');

Route::group(['middleware' => 'auth'], function () {

    // Auctions
    Route::get('auctions', ['uses' => 'AuctionController@index']);
    Route::get('auctions/create', ['uses' => 'AuctionController@create']);
    Route::get('auctions/{id}', ['uses' => 'AuctionController@show'])->where(['id' => '[0-9]+']);
    Route::get('auctions/{id}/edit', ['uses' => 'AuctionController@edit'])->where(['id' => '[0-9]+']);
    Route::post('auctions', ['uses' => 'AuctionController@store']);
    Route::patch('auctions/{id}', ['uses' => 'AuctionController@update'])->where(['id' => '[0-9]+']);

    // Bids
    Route::get('auctions/{id}/bids', ['uses' => 'BidController@index'])->where(['id' => '[0-9]+']);;
    Route::post('auctions/{id}/bids', ['uses' => 'BidController@store'])->where(['id' => '[0-9]+']);;

    // User feedback
    Route::get('users/{username}/feedback', ['uses' => 'UserFeedbackController@index']);
    Route::get('auctions/{id}/feedback/create', ['uses' => 'UserFeedbackController@create'])->where(['id' => '[0-9]+']);
    Route::post('auctions/{id}/feedback', ['uses' => 'UserFeedbackController@store'])->where(['id' => '[0-9]+']);

});
