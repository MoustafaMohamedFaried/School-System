<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" type="image/x-icon" href="{{ URL::asset('assets/img/school.png') }}">
        <title>@yield('title')</title>

        <link href="{{ URL::asset('assets/css/styles.css') }}" rel="stylesheet"/>
        @yield('css')

        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    </head>

    <body>

        <!-- navbar -->
        @include('layouts.nav')
        <!-- end navbar -->

        <div id="layoutSidenav">

            <!-- sidebar -->
            @auth
                @if (Auth::user()->status == 3) {{--? if user is active --}}
                    @include('layouts.sidebar')
                @endif
            @endauth
            <!-- end sidebar -->

            <div id="layoutSidenav_content">

                <!-- content -->
                @yield('content')
                <!-- end content -->

                <!-- footer -->
                @include('layouts.footer')
                <!-- end footer -->

            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="{{ URL::asset('assets/js/scripts.js') }}"></script>

        @yield('js')
    </body>

</html>
