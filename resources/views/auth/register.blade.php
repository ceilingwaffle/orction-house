<form method="POST" action="/auth/register">
    {!! csrf_field() !!}

    <div>
        User Name
        <input type="text" name="username" value="{{ old('username') }}">
    </div>

    <div>
        Password
        <input type="password" name="password">
    </div>

    <div class="col-md-6">
        Confirm Password
        <input type="password" name="password_confirmation">
    </div>

    <div>
        <button type="submit">Register</button>
    </div>
</form>