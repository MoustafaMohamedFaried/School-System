<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Core</div>
                <a class="nav-link" href="{{ route('home') }}">
                    <div class="sb-nav-link-icon"><i style="font-size: 22px" class="fa-solid fa-house"></i></div>
                    Dashboard
                </a>

                @auth
                    @if (Auth::user()->role_id == 1)
                        <div class="sb-sidenav-menu-heading">Admin</div>

                        <a class="nav-link" href="{{ route('admin.register_requests') }}">
                            <div class="sb-nav-link-icon"><i style="font-size: 22px" class="fa-solid fa-door-open"></i></div>
                            Register Requests
                        </a>

                        <a class="nav-link" href="{{ route('admin.admin_control') }}">
                            <div class="sb-nav-link-icon"><i style="font-size: 22px" class="fa-solid fa-gauge"></i></div>
                            Admin Control
                        </a>
                    @endif
                @endauth

                <div class="sb-sidenav-menu-heading">Teachers</div>
                <a class="nav-link" href="{{ route('teacher.index') }}">
                    <div class="sb-nav-link-icon"><i style="font-size: 22px" class="fa-solid fa-person-chalkboard"></i></div>
                    Teachers
                </a>

                <div class="sb-sidenav-menu-heading">Students</div>
                <a class="nav-link" href="{{ route('student.index') }}">
                    <div class="sb-nav-link-icon"><i style="font-size: 22px" class="fa-solid fa-user-graduate"></i></div>
                    Students
                </a>
            </div>
        </div>
        <div class="sb-sidenav-footer text-light">
            <div class="small">Logged in as:</div>
            @if (Auth::user()->role_id == 1)
                Admin
            @elseif (Auth::user()->role_id == 2)
                Teacher
            @endif
        </div>
    </nav>
</div>
