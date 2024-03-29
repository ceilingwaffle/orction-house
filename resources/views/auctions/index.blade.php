@extends('layouts.master')
@section('title', 'Auctions')

@section('content')
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Filter</h3>
            </div>
            <div class="panel-body">
                @include('partials._validationErrors')
                <form id="auctions-filter-form" method="get" action="">
                    <input type="hidden" name="page" value="{{ old('page') ?: Input::get('page', 1) }}" />
                    <div class="form-group">
                        <label for="title">Item Name:</label>
                        <input type="text" id="title" name="title" class="form-control" placeholder="e.g. Chair"
                               value="{{ old('title') ?: Input::get('title') }}" maxlength="50" />
                    </div>
                    <div class="form-group">
                        <label for="category">Item Category:</label>
                        <select id="category" name="category" class="form-control" data-default-value="">
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
                               placeholder="e.g. $10.00" value="{{ old('min_price') ?: Input::get('min_price') }}" />
                    </div>
                    <div class="form-group">
                        <label for="max_price">Max Price:</label>
                        <input type="text" id="max_price" name="max_price" class="form-control"
                               placeholder="e.g. $50.00" value="{{ old('max_price') ?: Input::get('max_price') }}" />
                    </div>
                    <div class="form-group">
                        <label for="order_by">Sort By:</label>
                        <select id="order_by" name="order_by" class="form-control"
                                data-default-value="auction_end_date">
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
                    <div class="form-group">
                        <label for="order_direction">Sort Direction:</label>
                        <select id="order_direction" name="order_direction" class="form-control"
                                data-default-value="asc">
                            <option value="asc" @if(Input::get('order_direction') == 'asc') selected @endif>Ascending
                            </option>
                            <option value="desc"
                                    @if(Input::get('order_direction') == 'desc') selected @endif>Descending
                            </option>
                        </select>
                    </div>
                    <div class="col-xs-4" style="padding-left: 0; padding-right: 6px;">
                        <button type="button" class="btn btn-danger btn-block" onClick="reloadAtCurrentPage();">Reset
                        </button>
                    </div>
                    <div class="col-xs-8" style="padding-left: 6px; padding-right: 0;">
                        <button type="submit" class="btn btn-primary btn-block">Apply Filter</button>
                    </div>
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
                    <h3 class="text-center">No results</h3>
                @else
                    <p class="text-muted"
                       style="margin-top:0;margin-bottom:10px;">Click on an auction to view its full details and place a bid.</p>
                    @foreach ($auctions as $auction)
                        <a href="/auctions/{{ $auction['auction_id'] }}" class="s-auction-listing-box"
                           id="auction-{{ $auction['auction_id'] }}"
                           title="{{ $auction['auction_title'] }}">
                            @if (isset($auction['auction_image']) and ! empty($auction['auction_image']))
                                <img src="/assets/img/auctions/{{ $auction['auction_image'] }}" alt="Auction Photo" />
                            @else
                                <img src="/assets/img/{{ getenv('AUCTION_IMAGE_PLACEHOLDER_FILE_NAME') }}" alt="Auction Photo" />
                            @endif

                            <table>
                                <tr>
                                    <td colspan="4" class="s-auction-title">{{ $auction['auction_title'] }}</td>
                                </tr>
                                <tr>
                                    <td class="td-left">Category:</td>
                                    <td class="td-right">{{ $auction['auction_category'] }}</td>
                                    <td class="td-left">Listed By:</td>
                                    <td class="td-right">
                                        <a href="/users/{{ $auction['auction_seller_username'] }}/feedback">
                                            {{ $auction['auction_seller_username'] }} ({{ $auction['seller_positive_feedback_count'] }})
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-left">Current Price:</td>
                                    <td class="td-right">{{ $auction['current_auction_price'] }}</td>
                                    <td class="td-left">Total bids:</td>
                                    <td class="td-right">{{ $auction['total_bids'] }}</td>
                                </tr>
                                <tr>
                                    <td class="td-left">Status:</td>
                                    <td class="td-right
                                        @if ($auction['auction_status'] != 'Open')
                                            s-auction-status-expired
                                        @endif">
                                        {{ $auction['auction_status'] }}
                                    </td>
                                    @if ($auction['auction_has_ended'] === true)
                                        <td class="td-left">Auction Ended:</td>
                                    @else
                                        <td class="td-left">Auction Ends:</td>
                                    @endif
                                    <td class="td-right">{{ $auction['auction_time_remaining'] }}</td>
                                </tr>
                            </table>
                        </a>
                    @endforeach
                    {!! $paginator !!}
                @endif
            </div>
        </div>
    </div>
@endsection