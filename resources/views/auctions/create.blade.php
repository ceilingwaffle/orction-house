@extends('layouts.master')
@section('title', 'New Auction')

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Create a New Auction</h3>
                </div>
                <div class="panel-body">
                    @include('partials._validationErrors')
                    @include('partials.forms._auction', [
                        'formId' => 'create-auction-form',
                        'formAction' => "/auctions",
                        'submitButtonText' => 'List My Item',
                    ])
                </div>
            </div>
        </div>
    </div>
@endsection