@extends('layouts.master')
@section('title', 'User Feedback')

@section('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">User feedback for {{ $username }}</h3>
                </div>
                <div class="panel-body">
                    @include('partials._validationErrors')
                    - todo:  
                    - Total positive
                    - Total neutral
                    - Total negative
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Type</th>
                            <th>User</th>
                            <th>Bought Item</th>
                            <th>Message</th>
                            <th>Written</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($feedbackData as $feedback)
                            <tr>
                                <td>{{ $feedback['feedback_type'] }}</td>
                                <td>
                                    <a href="/users/{{ $feedback['left_by_username'] }}/feedback">{{ $feedback['left_by_username'] }}</a>
                                </td>
                                <td>
                                    <a href="/auctions/{{ $feedback['auction_id'] }}">{{ $feedback['auction_title'] }}</a>
                                    (${{ $feedback['auction_winning_bid_amount'] }})
                                </td>
                                <td>{{ $feedback['feedback_message'] }}</td>
                                <td>{{ $feedback['feedback_date'] }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection