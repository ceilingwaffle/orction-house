@extends('layouts.master')
@section('title', $auction['auction_title'])

@section('content')
    <div class="row">
        <div class="col-xs-12 col-md-10 col-md-offset-1 s-auction-top-row">
            <a href="/auctions" class="s-back-link">
                <button type="button" class="btn btn-xs btn-default" aria-label="Back to search results">
                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                </button>
                Back to search results
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-10 col-md-offset-1">
            @include('partials._validationErrors', ['classes' => 'text-center s-auction-alert-box'])
            @if (isset($auction['auction_status']) and $auction['auction_status'] != 'Open')
                <div class="alert alert-danger text-center s-auction-alert-box">
                    Bidding on this item has ended (auction {{ lcfirst($auction['auction_status']) }}).
                </div>
            @endif
            @if (Session::get('auction_id_error'))
                <div class="alert alert-danger text-center s-auction-alert-box">
                    {{ Session::get('auction_id_error') }}
                </div>
            @endif
            @if (isset($auction['highest_bidder_username']) and Auth::user()->username == $auction['highest_bidder_username'])
                @if (isset($auction['auction_status']) and $auction['auction_status'] != 'Expired')
                    <div class="alert alert-success text-center s-auction-alert-box">
                        @if ($auction['auction_status'] == 'Sold')
                            You won this item! Congratulations!
                        @elseif ($auction['auction_status'] == 'Open')
                            You are currently the highest bidder!
                        @endif
                    </div>
                @endif
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 col-md-offset-1 s-auction-left-panel">
            @if (isset($auction['auction_image']) and ! empty($auction['auction_image']))
                <img src="/assets/img/auctions/{{ $auction['auction_image'] }}" class="s-auction-photo"/>
            @else
                <img src="/assets/img/{{ getenv('AUCTION_IMAGE_PLACEHOLDER_FILE_NAME') }}" class="s-auction-photo"/>
            @endif
            <div class="s-auction-panel-block">
                <h3>Seller Information</h3>
                <a href="/users/{{ $auction['auction_seller_username'] }}/feedback">
                    <p class="s-username">{{ $auction['auction_seller_username'] }}</p>

                    <p class="s-feedback-count">({{ $auction['seller_positive_feedback_count'] }})</p>
                </a>
                @if (isset($auction['seller_positive_feedback_percentage']) and !empty($auction['seller_positive_feedback_percentage']))
                    <p class="s-feedback-text">{{ $auction['seller_positive_feedback_percentage'] }} Positive
                        feedback</p>
                @endif
            </div>
            @if ($userCanUpdate === true)
                <a href="/auctions/{{ $id }}/edit" class="btn btn-primary btn-lg btn-block">Update Auction Details</a>
            @endif
        </div>
        <div class="col-md-7">
            <h1 class="s-auction-title">{{ $auction['auction_title'] }}</h1>
            <hr/>
            <div class="s-auction-text-row">
                <p class="left">Item Condition:</p>

                <p class="right">{{ $auction['auction_condition'] }}</p>
            </div>
            <div class="s-auction-text-row">
                @if ($auction['auction_has_ended'] === true)
                    <p class="left">Auction Ended:</p>
                @else
                    <p class="left">Auction Ends:</p>
                @endif
                <p class="right s-time-remaining">{{ $auction['auction_time_remaining'] }}</p>

                <p class="right s-faded-text">({{ $auction['auction_ended_date'] }})</p>
            </div>
            <form id="place-bid-form" method="post" action="/auctions/{{ $auction['auction_id'] }}/bids">
                {!! csrf_field() !!}
                <div class="s-auction-bid-box">
                    <div class="s-auction-text-row">
                        @if ($auction['total_bids'] === 0)
                            <p class="left">Starting Bid:</p>
                        @elseif ($auction['auction_status'] == 'Open')
                            <p class="left">Current Bid:</p>
                        @elseif ($auction['auction_status'] == 'Sold')
                            <p class="left">Winning Bid:</p>
                        @else
                            <p class="left">Final Bid:</p>
                        @endif
                        <p class="right" style="font-size: 1.3em;">{{ $auction['highest_bid_amount'] }}</p>

                        <a href="/auctions/{{ $id }}/bids" class="right s-bids-link">[{{ $auction['total_bids'] }}
                            @if ($auction['total_bids'] == 1) bid @else bids @endif ]</a>
                    </div>
                    <div class="s-auction-text-row">
                        <p class="left"></p>

                        <p class="right">
                            <input type="text" id="bid" name="bid" value="{{ old('bid') }}"/>
                            <br/>
                            <span class="s-faded-text">Enter {{ $auction['minimum_bid'] }} or more</span>
                        </p>

                        <p class="right">
                            <button type="submit" class="btn btn-primary">Place Bid</button>
                        </p>
                    </div>
                    <div class="s-bid-errors">
                        {{--@if (count($errors) > 0 and !empty($errors->get('bid')))--}}
                            {{--<li>{{ $errors->get('bid')[0] }}</li>--}}
                        {{--@endif--}}
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
        <div class="col-md-1"></div>
    </div>
@endsection