@extends('layouts.master')
@section('title', 'Auctions')

@section('content')
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Filter</h3>
            </div>
            <div class="panel-body">
                <form method="get" action="">
                    <div class="form-group">
                        <label for="title">Item Name:</label>
                        <input type="text" id="title" name="title" class="form-control" placeholder="e.g. Chair"
                               value="{{ Input::get('title') }}" />
                    </div>
                    <div class="form-group">
                        <label for="category">Item Category:</label>
                        <select id="category" name="category" class="form-control">
                            <option value="">All</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                        @if(Input::get('category') == $category->id) selected @endif>
                                    {{ $category->category }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="min_price">Min Price:</label>
                        <input type="text" id="min_price" name="min_price" class="form-control"
                               placeholder="e.g. $10.00" value="{{ Input::get('min_price') }}" />
                    </div>
                    <div class="form-group">
                        <label for="max_price">Max Price:</label>
                        <input type="text" id="max_price" name="max_price" class="form-control"
                               placeholder="e.g. $50.00" value="{{ Input::get('max_price') }}" />
                    </div>
                    <div class="form-group">
                        <label for="order_by">Sort By:</label>
                        <select id="order_by" name="order_by" class="form-control">
                            @foreach($sortableFields as $sortable)
                                <option value="{{ $sortable['field'] }}"
                                        @if(Input::get('order_by') == $sortable['field']) selected
                                        @elseif($sortable['default'] and !Input::get('order_by')) selected
                                        @endif>
                                    {{ $sortable['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-default btn-block">Apply</button>
                </form>
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
                    <p class="text-muted"
                       style="margin-top:0;margin-bottom:10px;">Click on an auction to view its full details and place a bid.</p>
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
                                    <td class="td-right">$ {{ $auction['highest_bid_amount'] }}</td>
                                    <td class="td-left">Highest Bidder:</td>
                                    <td class="td-right">{{ $auction['highest_bidder_username'] }}</td>
                                </tr>
                                <tr>
                                    <td class="td-left">Status:</td>
                                    <td class="td-right">{{ $auction['auction_status'] }}</td>
                                    @if ($auction['auction_has_ended'])
                                        <td class="td-left">Auction Ended:</td>
                                    @else
                                        <td class="td-left">Auction Ends:</td>
                                    @endif
                                    <td class="td-right">{{ $auction['auction_time_remaining'] }}</td>
                                </tr>
                                <tr>
                                    <td class="td-left">Listed By:</td>
                                    <td class="td-right">
                                        {{ $auction['auction_seller_username'] }}
                                        @if(!is_null($auction['seller_positive_feedback_percentage']) )
                                            ({{ $auction['seller_positive_feedback_percentage'] }})
                                        @endif
                                    </td>
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