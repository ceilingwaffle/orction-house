<?php

namespace App\Providers;

use App;
use App\Auction;
use App\AuctionCategory;
use App\Repositories\AuctionRepository;
use App\Transformers\BidTransformer;
use Illuminate\Support\ServiceProvider;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /*
         * Custom validators
         */

        // Valid money string
        Validator::extend('money', function ($attribute, $value, $parameters) {
            // Allows formats like: $123.45, 123, $123, $123.4, 123.4
            return preg_match('/^(\$)?\d+(\.\d{1,2})?$/', $value);
        });

        // Valid auction category ID
        Validator::extend('auction_category', function ($attribute, $value, $parameters) {
            return AuctionCategory::isValidCategoryId($value);
        });

        // Valid sortable field for the auctions query
        Validator::extend('auction_order_by', function ($attribute, $value, $parameters) {
            $repo = App::make(AuctionRepository::class);

            return $repo->isValidSortableField($value);
        });

        // Valid "order by" direction
        Validator::extend('sort_direction', function ($attribute, $value, $parameters) {
            $allowedValues = ['asc', 'desc'];

            return in_array($value, $allowedValues);
        });

        // Valid if the auction is NOT owned by the provided user ID
        Validator::extend('not_auction_owner', function ($attribute, $value, $parameters) {
            $auctionId = $parameters[0];
            $userId = $parameters[1];

            $repo = App::make(AuctionRepository::class);

            return !$repo->isAuctionOwner($auctionId, $userId);
        });

        // Valid bid amount
        Validator::extend('allowable_bid_amount', function ($attribute, $value, $parameters) {

            $bidTransformer = new BidTransformer();
            $bidAmount = $bidTransformer->transform($value);

            $auctionId = $parameters[0];

            $repo = App::make(AuctionRepository::class);
            $auctionData = $repo->getAuctionViewData($auctionId);

            $minBidAllowed = Auction::determineMinimumBid($auctionData->auction_start_price, $auctionData->highest_bid_amount);

            return $bidAmount >= $minBidAllowed;
        });

        // Valid if the auction status is 'open'
        Validator::extend('auction_is_open', function ($attribute, $value, $parameters) {
            $repo = App::make(AuctionRepository::class);

            $auctionId = $parameters[0];

            return $repo->isOpenForBidding($auctionId);
        });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local')) {
            $this->app->register('Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider');
        }
    }
}
