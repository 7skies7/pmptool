<nav class="navbar navbar-expand-md navbar-light navbar-laravel top-nav">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name', 'Laravel') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            @auth
            <ul class="navbar-nav mr-auto">
                <li class="nav-item ">
                    <!-- <a class="nav-link">Program</a> -->
                    <router-link to='/mydashboard' class="nav-link"><font-awesome-icon icon="user-circle" ></font-awesome-icon>My Dashboard</router-link>
                </li>
                <li class="nav-item ">
                    <!-- <a class="nav-link">Program</a> -->
                    <router-link to='/mytasks' class="nav-link"><font-awesome-icon icon="user-circle" ></font-awesome-icon>My Tasks</router-link>
                </li>
                <li class="nav-item ">
                    <!-- <a class="nav-link">Program</a> -->
                    <router-link to='/facelogin' class="nav-link"><font-awesome-icon icon="user-circle" ></font-awesome-icon>Face Login</router-link>
                </li>
            </ul>
            @endauth
            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        <!-- <router-link to='/login' class="nav-link">{{ __('Login') }}</router-link> -->
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif

                @else

                    <li class="nav-item dropdown">
                        <div class="d-flex flex-row">
                            <span class="rounded-circle usercard">{{ Auth::user()->initials() }}</span>
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>{{ Auth::user()->name }} <span class="caret"></span>
                            </a>
                        </div>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
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
@auth
<nav class="container-fluid is-white navbar-nav mx-auto justify-content-center align-items-center primary-menu flex-row shadow-sm">
    <!-- <div class="col-md-22"> -->
    <!-- <div class="row h-100 justify-content-center align-items-center primary-menu"> -->
        <div class="text-center main-menu-links"> 
            <li class="nav-item ">
                <!-- <a class="nav-link">Program</a> -->
                <router-link to='/dashboard' class="nav-link"><font-awesome-icon icon="coffee" ></font-awesome-icon>Dashboard</router-link>
            </li>
        </div>
        <div class="text-center main-menu-links"> 
            <li class="nav-item ">
                <!-- <a class="nav-link">Program</a> -->
                <router-link to='/program' class="nav-link"><font-awesome-icon icon="clock" ></font-awesome-icon>Program</router-link>
            </li>
        </div>
        <div class="text-center main-menu-links"> 
            <li class="nav-item ">
                <!-- <a class="nav-link">Program</a> -->
                <router-link to='/project' class="nav-link"><font-awesome-icon icon="clock" ></font-awesome-icon>Project</router-link>
            </li>
        </div>
        <div class="text-center main-menu-links"> 
            <li class="nav-item ">
                <!-- <a class="nav-link">Program</a> -->
                <router-link to='/tasks' class="nav-link"><font-awesome-icon icon="clock" ></font-awesome-icon>Tasks</router-link>
            </li>
        </div>
        <div class="text-center main-menu-links"> 
            <li class="nav-item ">
                <router-link to='/sprint' class="nav-link"><font-awesome-icon icon="clock" ></font-awesome-icon>Sprint</router-link>
            </li>
        </div>
        <div class="text-center main-menu-links"> 
            <li class="nav-item ">
                <router-link to='/acl' class="nav-link"><font-awesome-icon icon="clock" ></font-awesome-icon>ACL</router-link>
            </li>
        </div>
        <div class="text-center main-menu-links"> 
            <li class="nav-item ">
                <router-link to='/users' class="nav-link"><font-awesome-icon icon="clock" ></font-awesome-icon>Users</router-link>
            </li>
        </div>
    <!-- </div> -->
    <!-- </div> -->
</nav>
@endauth