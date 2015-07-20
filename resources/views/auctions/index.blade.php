@extends('layouts.master')
@section('title', 'Auctions')

@section('content')
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Filter</h3>
            </div>
            <div class="panel-body">
                filter stuff here....
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Auctions</h3>
            </div>
            <div class="panel-body">
                @if (!isset($auctions) or empty($auctions))
                    No results found
                @else
                    <p class="text-muted" style="margin-top:0;margin-bottom:10px;">Click on an auction to view its full details and place a bid.</p>
                    @foreach ($auctions as $auction)
                        <div class="s-auction-listing-box" id="auction-{{ $auction['auction_id'] }}"
                             data-auction-url="#{{ $auction['auction_id'] }}"
                             title="{{ $auction['auction_title'] }}">
                            <img src="/assets/img/auctions/{{ $auction['auction_image'] }}" alt="Auction Photo" />
                            <table>
                                <tr>
                                    <td colspan="4" class="s-auction-title">{{ $auction['auction_title'] }}</td>
                                </tr>
                                <tr>
                                    <td class="td-left">Category:</td>
                                    <td class="td-right">{{ $auction['auction_category'] }}</td>
                                </tr>
                                <tr>
                                    <td class="td-left">Current Bid:</td>
                                    <td class="td-right">{{ $auction['highest_bid_amount'] }}</td>
                                    <td class="td-left">Highest Bidder:</td>
                                    <td class="td-right">{{ $auction['highest_bidder_username'] }}</td>
                                </tr>
                                <tr>
                                    <td class="td-left">Status:</td>
                                    <td class="td-right">{{ $auction['auction_status'] }}</td>
                                    @if ($auction['auction_has_ended'])
                                        <td class="td-left">Auction Ended:</td>
                                    @else
                                        <td class="td-left">Time Remaining:</td>
                                    @endif
                                    <td class="td-right">{{ $auction['auction_time_remaining'] }}</td>
                                </tr>
                                <tr>
                                    <td class="td-left">Listed By:</td>
                                    <td class="td-right">{{ $auction['auction_seller_username'] }} ({{ $auction['seller_positive_feedback_percentage'] }})</td>
                                    <td class="td-left">Total bids:</td>
                                    <td class="td-right">{{ $auction['total_bids'] }}</td>
                                </tr>
                            </table>
                        </div>
                    @endforeach
                    {!! $paginator !!}
                @endif
            </div>
        </div>
    </div>
@endsection