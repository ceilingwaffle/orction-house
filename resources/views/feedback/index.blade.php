@extends('layouts.master')
@section('title', 'User Feedback')

@section('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">All feedback provided to user "{{ $username }}"</h3>
                </div>
                <div class="panel-body">
                    @include('partials._validationErrors')
                    @if (count($feedbackData) < 1)
                        <h3 class="text-center">No feedback to show</h3>
                    @else
                        <div class="s-badges-container">
                            <div class="panel panel-default">
                                <div class="panel-body text-center">
                                    <h4>Feedback Received:</h4>
                                    {{--<form action="" class="form-inline" role="form"></form>--}}
                                    <a class="btn btn-success" type="button">
                                        Positive <span class="badge">{{ $userData['feedback']['positive_count'] }}</span>
                                    </a>
                                    <a class="btn btn-warning" type="button">
                                        Neutral <span class="badge">{{ $userData['feedback']['neutral_count'] }}</span>
                                    </a>
                                    <a class="btn btn-danger" type="button">
                                        Negative <span class="badge">{{ $userData['feedback']['negative_count'] }}</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <table class="table table-striped table-bordered s-table">
                            <thead>
                            <tr>
                                <th>Experience</th>
                                <th>Sold Item</th>
                                <th>Purchased By</th>
                                <th>Message</th>
                                <th>Written</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($feedbackData as $feedback)
                                <tr class="@if(Session::get('highlightAuction') == $feedback['auction_id']) s-table-row-highlight @endif">
                                    <td>{{ $feedback['feedback_type'] }}</td>
                                    <td>
                                        <a href="/auctions/{{ $feedback['auction_id'] }}">{{ $feedback['auction_title'] }}</a>
                                        ({{ $feedback['auction_winning_bid_amount'] }})
                                    </td>
                                    <td>
                                        <a href="/users/{{ $feedback['left_by_username'] }}/feedback">
                                            {{ $feedback['left_by_username'] }}
                                            ({{ $feedback['user_positive_feedback_count'] }})
                                        </a>
                                    </td>
                                    <td>{{ $feedback['feedback_message'] }}</td>
                                    <td>{{ $feedback['feedback_date'] }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {!! $paginator !!}
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection