<nav class="navbar navbar-expand-md navbar-light navbar-laravel top-nav">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ url('/home#/dashboard') }}">
            <!-- {{ config('app.name', 'Laravel') }} -->
            <img src="/svg/cisco.jpg">
            <!-- CISCO PMS -->
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            @auth
            <ul class="navbar-nav mr-auto topnavlist" style="display:none!important">
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
                    <!-- @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif -->

                @else
                <v-flex xs2 style="margin-right:10px;margin-top:12px">
                    <v-badge overlap>
                        
                        <!-- <v-avatar color="#29B4E6 red--after"> -->
                            <v-icon dark>notifications</v-icon>
                        <!-- </v-avatar> -->
                    </v-badge>
                </v-flex>
                <usercard>  
                    <form id="logout-form" method="POST" action="/logout">
                        @csrf
                        <v-btn type="submit" small color="info">Logout</v-btn>
                        <!-- <button type="submit" class="btn btn-add ">Logout</button> -->
                    </form>
                </usercard>
                @endguest
            </ul>
        </div>

    </div>
</nav>
@auth
<nav class="container-fluid is-white navbar-nav mx-auto justify-content-center align-items-center primary-menu flex-row shadow-sm" >
    <!-- <div class="col-md-22"> -->
    <!-- <div class="row h-100 justify-content-center align-items-center primary-menu"> -->
        <div class="text-center main-menu-links"> 
            <li class="nav-item ">
                <!-- <a class="nav-link">Program</a> -->
                <router-link to='/dashboard' class="nav-link"><i class="material-icons">dashboard</i>Dashboard</router-link>
            </li>
        </div>
    @if(Gate::allows('View_Program'))
        <div class="text-center main-menu-links"> 
            <li class="nav-item ">
                <!-- <a class="nav-link">Program</a> -->
                <router-link to='/program' class="nav-link"><i class="material-icons">layers</i>Program</router-link>
            </li>
        </div>
    @endif
    @if(Gate::allows('View_Project'))
        <div class="text-center main-menu-links"> 
            <li class="nav-item ">
                <!-- <a class="nav-link">Program</a> -->
                <router-link to='/project' class="nav-link"><i class="material-icons">developer_board</i>Project</router-link>
            </li>
        </div>
    @endif
<!--         <div class="text-center main-menu-links"> 
            <li class="nav-item ">
                <router-link to='/tasks' class="nav-link"><font-awesome-icon icon="clock" ></font-awesome-icon>Tasks</router-link>
            </li>
        </div>
        <div class="text-center main-menu-links"> 
            <li class="nav-item ">
                <router-link to='/sprint' class="nav-link"><font-awesome-icon icon="clock" ></font-awesome-icon>Sprint</router-link>
            </li>
        </div> -->
        @if(Gate::allows('View_ACL'))
        <div class="text-center main-menu-links"> 
            <li class="nav-item ">
                <router-link to='/acl' class="nav-link"><i class="material-icons">security</i>ACL</router-link>
            </li>
        </div>
        @endif
        @if(Gate::allows('View_Users'))
        <div class="text-center main-menu-links"> 
            <li class="nav-item ">
                <router-link to='/users' class="nav-link"><i class="material-icons">account_circle</i>Users</router-link>
            </li>
        </div>
        @endif
        @if(Gate::allows('View_Organization'))
        <div class="text-center main-menu-links"> 
            <li class="nav-item ">
                <router-link to='/company' class="nav-link"><i class="material-icons">account_balance</i>Organization</router-link>
            </li>
        </div>
        @endif
        <div class="text-center main-menu-links"> 
            <li class="nav-item ">
                <!-- <a class="nav-link">Program</a> -->
                <router-link to='/mytasks' class="nav-link"><i class="material-icons">event</i>My Tasks</router-link>
            </li>
        </div>
        @if(Gate::allows('View_Project') || Gate::allows('View_Program') || Gate::allows('View_Organisation'))
        <div class="text-center main-menu-links"> 
            <li class="nav-item ">
                <!-- <a class="nav-link">Program</a> -->
                <router-link to='/myactivity' class="nav-link"><i class="material-icons">event</i>My Activity</router-link>
            </li>
        </div>
        @endif
    <!-- </div> -->
    <!-- </div> -->
</nav>
@endauth

