@extends('layouts.master')
@section('title', 'Update Auction')

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Update Auction</h3>
                </div>
                <div class="panel-body">
                    @include('partials._validationErrors')
                    @include('partials.forms._auction', [
                        'formId' => 'update-auction-form',
                        'formMethod' => 'PATCH',
                        'formAction' => "/auctions/{$id}",
                        'submitButtonText' => 'Update My Listing',
                        'disabledInputs' => [
                            'starting_price' => true,
                        ],
                    ])
                </div>
            </div>
        </div>
    </div>
@endsection