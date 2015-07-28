@extends('layouts.master')
@section('title', 'Log In')

@section('content')
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Log In</h3>
            </div>
            <div class="panel-body">
                @include('partials._validationErrors')
                <form method="POST" id="user-login-form" action="/auth/login" class="form-horizontal">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <label for="username" class="col-sm-3 control-label">User Name:</label>
                        <div class="col-sm-9">
                            <input type="text" id="username" name="username" class="form-control" maxlength="50"
                                   value="{{ old('username') }}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="col-sm-3 control-label">Password:</label>
                        <div class="col-sm-9">
                            <input type="password" id="password" name="password" class="form-control"
                                   value="{{ old('password') }}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-9">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" id="remember" name="remember" @if(old('remember')) checked @endif>
                                    Remember Me
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-9">
                            <button type="submit" class="btn btn-primary">Log In</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection