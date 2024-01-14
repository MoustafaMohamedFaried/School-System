@extends('layouts.app')

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
@endsection

@section('title')
    Dashboard
@endsection

@section('content')
    <main>
        @if (Auth::user()->status == 3) {{-- ? if user is active --}}

            <div class="container-fluid px-4">
                <h1 class="mt-4">Dashboard</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>

                @if (Auth::user()->role_id == 1) {{--? if user is admin --}}

                    {{--? cards --}}
                    <div class="row">

                        <div class="col-xl-4 col-md-6">
                            <div class="card bg-danger text-white mb-4">
                                <div class="card-body">Teachers</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <p class="small text-white stretched-link">
                                        @if(empty($teachers))
                                            No Teachers
                                        @else
                                            {{ $teachers }}
                                        @endif
                                    </p>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-md-6">
                            <div class="card bg-primary text-white mb-4">
                                <div class="card-body">Students</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <p class="small text-white stretched-link">
                                            @if(empty($students))
                                                No Students
                                            @else
                                                {{ $students }}
                                            @endif
                                        </p>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-md-6">
                            <div class="card bg-success text-white mb-4">
                                <div class="card-body">Register Requests</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <p class="small text-white stretched-link">
                                        @if(empty($register_request))
                                            No register requests
                                        @else
                                            {{ $register_request }}
                                        @endif
                                    </p>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>

                    </div>

                @endif

                @if (Auth::user()->role_id == 2) {{--? is user is teacher he can sees the number of his students --}}
                    <div class="col-xl-4 col-md-6">
                        <div class="card bg-danger text-white mb-4">
                            <div class="card-body">Your students</div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <p class="small text-white stretched-link">
                                    @if(empty($teacher_students))
                                        You don't have students yet
                                    @else
                                        {{ $teacher_students }}
                                    @endif
                                </p>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                @endif

                {{--? charts --}}
                <div class="row">
                    <div class="col-xl-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-chart-area me-1"></i>
                                Area Chart Example
                            </div>
                            <div class="card-body"><canvas id="myAreaChart" width="100%" height="40"></canvas></div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-chart-bar me-1"></i>
                                Bar Chart Example
                            </div>
                            <div class="card-body"><canvas id="myBarChart" width="100%" height="40"></canvas></div>
                        </div>
                    </div>
                </div>

            </div>

        @elseif (Auth::user()->status == 2) {{-- ? if user is in waiting list --}}
            <h1 class="text-center text-primary mt-5 pt-5">
                You're in waiting list to accept your request
            </h1>

        @endif

    </main>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="{{ URL::asset('assets/demo/chart-area-demo.js') }}"></script>
    <script src="{{ URL::asset('assets/demo/chart-bar-demo.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="{{ URL::asset('assets/js/datatables-simple-demo.js') }}"></script>
@endsection
