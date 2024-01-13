@extends('layouts.app')

@section('title')
    Register Requests
@endsection

@section('content')
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Requests</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item active">Register Requests</li>
            </ol>

            <div class="card mb-4">
                <div class="card-body">

                    <div class="row">
                        <div class="col-lg d-flex justify-content-center">
                            <h6>
                                Register Requests
                            </h6>
                        </div>
                    </div>

                    {{--Todos: showing Registeration_request (session) message --}}
                    @if (session()->has('Registeration_request'))
                        <div class="alert alert-dark" role="alert">
                            <div class="container">
                                <div class="row">
                                    <div class="col-11">
                                        <strong>{{ session()->get('Registeration_request') }}</strong>
                                    </div>
                                    <div class="col-1">
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (!($teachers->isEmpty()))
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Username</th>
                                    <th scope="col">E-mail</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $x = 0 @endphp
                                @foreach ($teachers as $teacher)
                                    @if ($teacher-> status == 2) {{--? displaying just waiting teachers --}}
                                        @php $x++ @endphp
                                        <tr>
                                            <th scope="row">{{ $x }}</th>
                                            <td>{{ $teacher-> name }}</td>
                                            <td>{{ $teacher-> username }}</td>
                                            <td>{{ $teacher-> email }}</td>
                                            <td>
                                                <span class="badge text-bg-primary">Waiting</span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    {{--Todo: Accept teacher register request button --}}
                                                    <form class="m-1" action="{{ route('admin.accept_register_request',$teacher-> id) }}" method="post">
                                                        @csrf
                                                        @method('put')

                                                        <button type="submit" class="btn btn-success" title="Accept Request">
                                                            <strong> <span>&#10003;</span> </strong>
                                                        </button>
                                                    </form>

                                                    {{--Todo: Refuse teacher register request button --}}
                                                    <form class="m-1" action="{{ route('admin.refuse_register_request',$teacher-> id) }}" method="post">
                                                        @csrf
                                                        @method('put')
                                                        <button class="btn btn-danger" type="submit" title="Refuse Request">
                                                            <strong> <span>&#x58;</span> </strong>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                        {{--? pagination bar --}}
                        {!! $teachers->links('pagination::bootstrap-5') !!}
                    @else
                        <h3 class="text-danger text-center">No available data </h3>
                    @endif

                </div>
            </div>
        </div>
    </main>
@endsection

