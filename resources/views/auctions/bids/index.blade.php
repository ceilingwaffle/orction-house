@extends('layouts.master')
@section('title', 'Bids for Item ' . $auctionTitle)

@section('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Showing Bids for Item <a href="/auctions/{{ $id }}"
                                                                     style="text-decoration: underline;">{{ $auctionTitle }}</a>
                    </h3>
                </div>
                <div class="panel-body">
                    @include('partials._validationErrors')
                    @if (count($bids) < 1)
                        <h3 class="text-center">No bids have been placed yet</h3>
                    @else
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Bidder</th>
                                        <th>Bid Amount</th>
                                        <th>Bid Time</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($bids as $bid)
                                        <tr>
                                            <td>
                                                <a href="/users/{{ $bid['bidder_username'] }}/feedback">
                                                    {{ $bid['bidder_username'] }}
                                                    ({{ $bid['bidder_positive_feedback_count'] }})
                                                </a>
                                            </td>
                                            <td><span class="text-right">{{ $bid['bid_amount'] }}</span></td>
                                            <td>
                                                <p>{{ $bid['bid_date_human'] }} <span class="s-faded-text">({{ $bid['bid_date_full'] }})</span></p>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            {!! $paginator !!}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection