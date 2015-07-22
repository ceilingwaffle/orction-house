@extends('layouts.master')
@section('title', 'Auction ' . $id)

@section('content')
    <div class="row">
        <div class="col-xs-12 col-sm-11 col-sm-offset-1 s-auction-top-row">
            <a href="/auctions" class="s-back-link">
                <button type="button" class="btn btn-xs btn-default" aria-label="Back to search results">
                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                </button> Back to search results
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3 col-sm-offset-1 s-auction-left-panel">
            <img src="http://fakeimg.pl/350x350" class="s-auction-photo" />
            <div class="s-auction-panel-block">
                <h3>Seller Information</h3>
                <a href="/users/myusername1/feedback">
                    <p class="s-username">myusername1</p>
                    <p class="s-feedback-count">(1000)</p>
                </a>
                <p class="s-feedback-text">98.7% Positive feedback</p>
            </div>
        </div>
        <div class="col-sm-7">
            <h1 class="s-auction-title">Ornamental Sasquatch (ID: {{ $id }})</h1>
            <hr />
            <div class="s-auction-text-row">
                <p class="left">Item Condition:</p>
                <p class="right">Brand New</p>
            </div>
            <div class="s-auction-text-row">
                <p class="left">Time Remaining:</p>
                <p class="right s-time-remaining">30 seconds</p>
                <p class="right s-faded-text">(22 Jul, 2015&nbsp;&nbsp;&nbsp;12:34:56 PM)</p>
            </div>
            <form id="bid-form" method="post" action="/bid-url-todo">
                <div class="s-auction-bid-box">
                    <div class="s-auction-text-row">
                        <p class="left">Current Bid:</p>
                        <p class="right" style="font-size: 1.3em;">$123.45</p>
                        <p class="right s-bids-link">[ 5 bids ]</p>
                    </div>
                    <div class="s-auction-text-row">
                        <p class="left"></p>
                        <p class="right">
                            <input type="text" id="bid" name="bid" />
                            <br/>
                            <span class="s-faded-text">Enter $20.00 or more</span>
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
                <p class="right">Relics</p>
            </div>
            <div class="s-auction-text-row">
                <p class="left">Listed:</p>
                <p class="right">3 days ago</p>
                <p class="right s-faded-text">(22 Jul, 2015&nbsp;&nbsp;&nbsp;12:34:56 PM)</p>
            </div>
            <div class="s-auction-text-row">
                <p class="left">Last Updated:</p>
                <p class="right">4 hours ago</p>
                <p class="right s-faded-text">(22 Jul, 2015&nbsp;&nbsp;&nbsp;12:34:56 PM)</p>
            </div>
            <h3>Item Description:</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Asperiores incidunt nihil voluptatum. Aliquam delectus dolore eligendi facere illo itaque labore laboriosam magni, nobis odit placeat porro quas qui quia sit.</p>
        </div>
        <div class="col-sm-1"></div>
    </div>
@endsection