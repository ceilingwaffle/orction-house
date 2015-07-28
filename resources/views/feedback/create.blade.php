@extends('layouts.master')
@section('title', 'Leave Feedback')

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Leave Feedback</h3>
                </div>
                <div class="panel-body">
                    @include('partials._validationErrors')
                    <p>Leaving feedback for...</p>
                    <form method="POST" id="create-feedback-form" action="/auctions/{{ $auctionId }}/feedback"
                          enctype="multipart/form-data">
                        {!! csrf_field() !!}
                        <div class="form-group">
                            <label for="rating">Rating:</label>
                            <select id="rating" name="rating" class="form-control">
                                <option value="" disabled>-- Select --</option>
                                @foreach($feedbackTypes as $feedbackType)
                                    <option value="{{ $feedbackType->id }}" @if(old('rating')) selected @endif>{{ $feedbackType->type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="message">Message:</label>
                            <textarea id="message" name="message" class="form-control" maxlength="200"
                                      placeholder="Describe your experience...">{{ old('message') }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Leave Feedback</button>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection