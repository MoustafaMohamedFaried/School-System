@extends('layouts.app')

@section('title')
    Admin Control
@endsection

@section('content')
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Users</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item active">Users</li>
            </ol>

            <div class="card mb-4">
                <div class="card-body">

                    {{--Todos: showing activation (session) message --}}
                    @if (session()->has('activation'))
                        <div class="alert alert-dark" role="alert">
                            <div class="container">
                                <div class="row">
                                    <div class="col-11">
                                        <strong>{{ session()->get('activation') }}</strong>
                                    </div>
                                    <div class="col-1">
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-lg d-flex justify-content-center">
                            <h6>
                                Users
                            </h6>
                        </div>
                    </div>

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
                                    @if ($teacher-> status != 2) {{--? displaying not waiting teachers --}}
                                        @php $x++ @endphp
                                        <tr>
                                            <th scope="row">{{ $x }}</th>
                                            <td>{{ $teacher-> name }}</td>
                                            <td>{{ $teacher-> username }}</td>
                                            <td>{{ $teacher-> email }}</td>
                                            <td>
                                                @if ($teacher->status == 1)
                                                    <span class="badge text-bg-danger">Not-Active</span>
                                                @elseif ($teacher->status == 3)
                                                    <span class="badge text-bg-success">Active</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    @if ($teacher->status == 1)
                                                        {{--Todo: Ativate teacher register request button --}}
                                                        <form action="{{ route('admin.activation_control',$teacher-> id) }}" method="post">
                                                            @csrf
                                                            @method('put')

                                                            <button type="submit" class="btn btn-success" title="Activate">
                                                                <strong> <span>&#10003;</span> </strong>
                                                            </button>
                                                        </form>

                                                    @elseif ($teacher->status == 3)
                                                        {{--Todo: Deativate teacher register request button --}}
                                                        <form action="{{ route('admin.activation_control',$teacher-> id) }}" method="post">
                                                            @csrf
                                                            @method('put')
                                                            <button class="btn btn-danger" type="submit" title="Deactivate">
                                                                <strong> <span>&#x58;</span> </strong>
                                                            </button>
                                                        </form>

                                                    @endif
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

