@if (count($errors) > 0)
    <div class="alert alert-danger @if(isset($classes)) {{ $classes }} @endif">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif