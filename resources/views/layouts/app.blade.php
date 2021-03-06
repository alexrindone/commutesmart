<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ mix('/js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <!-- <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a> -->
                <a class="nav-bar-brand" href="http://commutesmartnh.org/">
                    <img src="/images/commute-smart-logo-rgb.png" style="max-width:200px; height: auto;">
                </a>
                <div style="padding-right: 15px;">  </div>
                <a class="nav-bar-brand" href="https://commutesmartseacoast.org">
                    <img src="/images/logo.png" style="max-width:200px; height: auto;">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @else
                            <li class="nav-item">
                                @php
                                $current_time = Carbon\Carbon::now()->toDateString();
                                $leaderboard = App\Challenge::orderBy('id', 'desc')->get();
                                @endphp

                                @foreach ($leaderboard as $leaderboard_challenge) 
                                    @if($leaderboard_challenge->start_date <= $current_time && $leaderboard_challenge->end_date >= $current_time) 
                                        <a class="nav-link" href="/leaderboard/{{ strtolower($leaderboard_challenge->type) }}/{{ $leaderboard_challenge->slug }}">{{ $leaderboard_challenge->name}}</a>
                                                              
                                @endif
                                @endforeach
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Past Challenges <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    @foreach (App\Challenge::orderBy('id', 'desc')->get() as $challenge)
                                    @if($challenge->end_date <= $current_time) 
                                    <a class="dropdown-item" href="/leaderboard/{{ strtolower($challenge->type) }}/{{ $challenge->slug }}"> {{ $challenge->name}}</a>
                                    @endif
                                    @endforeach                                    
                                </div>
                            </li>
                            @if (Auth::user()->type == 'admin')
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Admin <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="/admin/companies"
                                       >
                                        {{ __('Manage Companies') }}
                                    </a>
                                    <a class="dropdown-item" href="/admin/challenges"
                                       >
                                        {{ __('Manage Challenges') }}
                                    </a>
                                    <a class="dropdown-item" href="/admin/users"
                                       >
                                        {{ __('Update Captains') }}
                                    </a>
                                    @foreach(App\Challenge::orderBy('name')->get() as $challenge)

                                        <a class="dropdown-item" href="/admin/users-trips-export/{{$challenge->id}}">
                                            Export User Trips Data for {{$challenge->name}}
                                        </a>
                                    @endforeach
                                    
                                    <a class="dropdown-item" href="/admin/users-registered-export"
                                       >
                                        {{ __('Export Registered Users Data') }}
                                    </a>
                                </div>
                            </li>
                            @endif
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Profile <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="/trips">
                                        {{ __('My Trips') }}
                                    </a>
                                    @if (Auth::user()->is_captain)
                                        <a class="dropdown-item" href="/profile/team">
                                            {{ __('My Team') }}
                                        </a>
                                    @endif
                                    <a class="dropdown-item" href="/profile">
                                        {{ __('My Profile') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
        <div style="text-align: center">This site is now being used to log trips for both CommuteSMART Seacoast, and its statewide counterpart, CommuteSmart NH.</div>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
