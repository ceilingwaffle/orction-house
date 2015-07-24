@extends('layouts.master')
@section('title', 'New Listing')

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Create a New Auction</h3>
                </div>
                <div class="panel-body">
                    @include('partials._validationErrors')
                    <form method="post" id="create-auction-form" action="" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                        <div class="form-group">
                            <label for="item_name">* Item Name:</label>
                            <input type="text" id="item_name" name="item_name" class="form-control" placeholder="e.g. Chair" value="{{ old('title') }}" />
                        </div>
                        <div class="form-group">
                            <label for="description">* Describe the item:</label>
                            <textarea id="description" name="description" class="form-control" placeholder="" value={{ old('description') }}></textarea>
                        </div>
                        <div class="form-group">
                            <label for="category">* Category:</label>
                            <select id="category" name="category" class="form-control">
                                <option value="0" disabled>--- Select ---</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->category }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="starting_price">* Starting price:</label>
                                    <input type="text" id="starting_price" name="starting_price" class="form-control" placeholder="e.g. $1.00" value="{{ old('starting_price', '$0.00') }}" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="days">* Days to run (between 1-14):</label>
                                    <input type="text" id="days" name="days" class="form-control" placeholder="e.g. 10" value="{{ old('days', 7) }}" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="photo">Upload a photo (max 1 MB):</label>
                            <input type="file" id="photo" name="photo" accept="image/*" class="form-control" value="{{ old('photo') }}" />
                        </div>
                        <button type="submit" class="btn btn-lg btn-block btn-primary">List My Auction</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection