@extends('layouts.master')
@section('title', 'Sign Up')

@section('content')
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Sign Up</h3>
            </div>
            <div class="panel-body">
                <p class="text-muted text-center s-form-description">Fill in this form to create an account to use with the website.</p>
                @include('partials._validationErrors')
                <form method="POST" action="/auth/register" class="form-horizontal">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <label for="username" class="col-sm-4 control-label">User Name:</label>
                        <div class="col-sm-8">
                            <input type="text" id="username" name="username" class="form-control" placeholder=""
                                   value="{{ old('username') }}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="col-sm-4 control-label">Password:</label>
                        <div class="col-sm-8">
                            <input type="password" id="password" name="password" class="form-control"
                                   placeholder="" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation" class="col-sm-4 control-label">Confirm Password:</label>
                        <div class="col-sm-8">
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                   class="form-control" placeholder="" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-4 col-sm-8">
                            <button type="submit" class="btn btn-primary">Sign Up</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection