@extends('layouts.master')
@section('title', 'Auctions')

@section('content')
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">Filter</div>
            <div class="panel-body">
                filter stuff here....
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">Auctions</div>
            <div class="panel-body">
                <div class="s-auction-listing-box" data-auction-url="#auction1" title="Auction title">
                    <img src="image.jpg" alt="Auction Photo" />
                    <table>
                        <tr>
                            <td colspan="4" class="s-auction-title">Auction title</td>
                        </tr>
                        <tr>
                            <td class="td-left">Category:</td>
                            <td class="td-right">Relics</td>
                        </tr>
                        <tr>
                            <td class="td-left">Listed By:</td>
                            <td class="td-right">waffle (50)</td>
                            <td class="td-left">Current Bid:</td>
                            <td class="td-right">$19.50</td>
                        </tr>
                        <tr>
                            <td class="td-left">Starting Bid:</td>
                            <td class="td-right">$1.00</td>
                            <td class="td-left">Time Remaining:</td>
                            <td class="td-right">30 seconds</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection