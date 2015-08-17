@extends('layouts.master')
@section('title', 'Home')

@section('content')
    <div class="home-container">
        <div class="home-container-text">
            <h1>Welcome to Orction House!</h1>
            <h4>Here are some things you can do to get started:</h4>
            <ul>
                @if (!Auth::check())
                    <li>
                        <a href="/auth/register">Sign up</a> for a new account (or <a
                                href="/auth/login">log in</a> to your existing account).
                    </li>
                @endif
                <li>Check out the <a href="/auctions">items available for sale</a> (account required).</li>
                <li>Sell an item by <a href="/auctions/create">creating a new auction</a> (account required).</li>
            </ul>
        </div>
    </div>

@endsection