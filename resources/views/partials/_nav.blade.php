<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">
                <img src="/assets/img/orc_header_logo_50x50.png" style="position:absolute; top:0;">
                <h2 class="s-nav-title"><span style="font-weight:bold;">ORC</span>TION HOUSE</h2>
            </a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                @if ( ! Auth::check())
                    <li><a href="/auth/register">Sign Up</a></li>
                    <li><a href="/auth/login">Log In</a></li>
                @else
                    {{--<li class="active"><a href="#">Home</a></li>--}}
                    {{--<li><a href="/auctions">Auctions</a></li>--}}
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Auctions <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="/auctions">View all Auctions</a></li>
                            <li><a href="/auctions/create">Create a new Auction</a></li>
                            {{--<li><a href="#">Something else here</a></li>--}}
                            {{--<li role="separator" class="divider"></li>--}}
                            {{--<li class="dropdown-header">Nav header</li>--}}
                            {{--<li><a href="#">Separated link</a></li>--}}
                            {{--<li><a href="#">One more separated link</a></li>--}}
                        </ul>
                    </li>
                    <li><a href="/auth/logout">Log Out ({{ Auth::user()->username }})</a></li>
                @endif
            </ul>
        </div>
    </div>
</nav>