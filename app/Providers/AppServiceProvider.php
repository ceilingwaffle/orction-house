<?php

namespace App\Providers;

use App;
use App\AuctionCategory;
use App\Repositories\AuctionRepository;
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
        // Custom validators
        Validator::extend('money', function($attribute, $value, $parameters) {
            // Allows formats like: $123.45, 123, $123, $123.4, 123.4
            return preg_match('/^(\$)?\d+(\.\d{1,2})?$/', $value);
        });

        Validator::extend('auction_category', function($attribute, $value, $parameters) {
            $categories = AuctionCategory::all(['id']);

            return $categories->contains('id', $value);
        });

        Validator::extend('auction_order_by', function($attribute, $value, $parameters) {
            $repo = App::make(AuctionRepository::class);

            return $repo->isValidSortableField($value);
        });

        Validator::extend('sort_direction', function($attribute, $value, $parameters) {
            $allowedValues = ['asc', 'desc'];

            return in_array($value, $allowedValues);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if($this->app->environment('local'))
        {
            $this->app->register('Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider');
        }
    }
}
