<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Cisco PMP') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <!-- <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css"> -->
    <!-- <link href='https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900|Material+Icons' rel="stylesheet"> -->
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app" style="background:white">
        <main>
            <div class="container" style="max-width:100%;padding:0px">
                <div class="row justify-content-center">
                    <div class="col-md-7 text-xs-center" style="height:90%;width:100%">
                        <img src="/images/login.jpg" style="height:90%;width:90%">
                    </div>
                    <div class="col-md-5" >
                    @guest
                        @yield('content')
                    @endguest
                    </div>

                </div>
            </div>
        </main>
    </div>

</body>
</html>
