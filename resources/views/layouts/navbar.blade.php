<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
                @if(Auth::check())
                    <li><a href="#">À propos</a></li>
                    <li><a href="#">Manuel</a></li>
                    <li><a href="{{ route('profile') }}">Profil</a></li>
                    <li><a href="#">Contraintes</a></li>
                @endif
            </ul>

            @if (Auth::check())
            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                {{--Branch name: pharmacien vs atp--}}
                <li>
                    <a class="branchname">{{ Auth::user()->branch->name }}</a>
                </li>


                {{-- Adminstration--}}
                @include('layouts.navbar-admin')

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        {{ Auth::user()->firstname . ' ' . Auth::user()->lastname }} <span class="caret"></span>
                    </a>

                    <ul class="dropdown-menu" role="menu">
                        <li>
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                {{ __('auth.logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
            @endif
        </div>
    </div>
</nav>