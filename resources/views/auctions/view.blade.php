@extends('layouts.master')
@section('title', 'Auction ' . $id)

@section('content')
    <div class="row">
        <div class="col-xs-12 col-sm-10 col-sm-offset-1 s-auction-top-row">
            <a href="/auctions" class="s-back-link">
                <button type="button" class="btn btn-xs btn-default" aria-label="Back to search results">
                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                </button>
                Back to search results
            </a>
        </div>
    </div>
    @if (isset($auction['auction_has_ended']) and $auction['auction_has_ended'])
        <div class="col-xs-12 col-sm-10 col-sm-offset-1 alert alert-danger text-center s-auction-alert-box">
            Bidding on this item has ended.
        </div>
    @endif
    <div class="row">
        <div class="col-sm-3 col-sm-offset-1 s-auction-left-panel">
            <img src="/assets/img/auctions/{{ $auction['auction_image'] }}" class="s-auction-photo" />
            <div class="s-auction-panel-block">
                <h3>Seller Information</h3>
                <a href="/users/{{ $auction['auction_seller_username'] }}/feedback">
                    <p class="s-username">{{ $auction['auction_seller_username'] }}</p>
                    <p class="s-feedback-count">({{ $auction['seller_positive_feedback_count'] }})</p>
                </a>
                @if (isset($auction['seller_positive_feedback_percentage']) and !empty($auction['seller_positive_feedback_percentage']))
                    <p class="s-feedback-text">{{ $auction['seller_positive_feedback_percentage'] }} Positive feedback</p>
                @endif
            </div>
        </div>
        <div class="col-sm-7">
            <h1 class="s-auction-title">{{ $auction['auction_title'] }}</h1>
            <hr />
            <div class="s-auction-text-row">
                <p class="left">Item Condition:</p>
                <p class="right">{{ $auction['auction_condition'] }}</p>
            </div>
            <div class="s-auction-text-row">
                @if ($auction['auction_has_ended'])
                    <p class="left">Auction Ended:</p>
                @else
                    <p class="left">Auction Ends:</p>
                @endif
                <p class="right s-time-remaining">{{ $auction['auction_time_remaining'] }}</p>
                <p class="right s-faded-text">({{ $auction['auction_ended_date'] }})</p>
            </div>
            <form id="bid-form" method="post" action="/auctions/{{ $auction['auction_id'] }}/bid">
                <div class="s-auction-bid-box">
                    <div class="s-auction-text-row">
                        <p class="left">Current Bid:</p>
                        <p class="right" style="font-size: 1.3em;">${{ $auction['highest_bid_amount'] }}</p>
                        <p class="right s-bids-link">[{{ $auction['total_bids'] }}
                            @if ($auction['total_bids'] == 1) bid @else bids @endif ]</p>
                    </div>
                    <div class="s-auction-text-row">
                        <p class="left"></p>
                        <p class="right">
                            <input type="text" id="bid" name="bid" />
                            <br />
                            <span class="s-faded-text">Enter ${{ $auction['user_minimum_bid'] }} or more</span>
                        </p>
                        <p class="right">
                            <button type="submit" class="btn btn-primary">Place Bid</button>
                        </p>
                    </div>
                    <div class="s-auction-text-row">
                        <p class="left"></p>
                    </div>
                </div>
            </form>
            <div class="s-auction-text-row">
                <p class="left">Item Category:</p>
                <p class="right">{{ $auction['auction_category'] }}</p>
            </div>
            <div class="s-auction-text-row">
                <p class="left">Listed:</p>
                <p class="right">{{ $auction['auction_created_date_readable'] }}</p>
                <p class="right s-faded-text">({{ $auction['auction_created_date'] }})</p>
            </div>
            <div class="s-auction-text-row">
                <p class="left">Last Updated:</p>
                <p class="right">{{ $auction['auction_updated_date_readable'] }}</p>
                <p class="right s-faded-text">({{ $auction['auction_updated_date'] }})</p>
            </div>
            <h3>Item Description:</h3>
            <p>{{ $auction['auction_description'] }}</p>
        </div>
        <div class="col-sm-1"></div>
    </div>
@endsection