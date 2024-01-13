@extends('layouts.app')

@section('title')
    Login
@endsection

@section('content')
    <main>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    <div class="card shadow-lg border-0 rounded-lg mt-5">
                        <div class="card-header"><h3 class="text-center font-weight-light my-4">Login</h3></div>
                        <div class="card-body">

                            {{--Todos: alert to telling user his account is not-active & can't login --}}
                            @if (session()->has('disactivate'))
                                <div class="alert alert-danger" role="alert">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-11">
                                                <strong>{{ session()->get('disactivate') }}</strong>
                                            </div>
                                            <div class="col-1">
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <form action="{{ route('login') }}" method="POST">
                                @csrf

                                <div class="form-floating mb-3">
                                    <input class="form-control @error('email') is-invalid @enderror" id="email" name="email" type="email" placeholder="name@example.com" value="{{ old('email') }}"/>
                                    <label for="email">Email address</label>
                                    @error('email')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-floating mb-3">
                                    <input class="form-control @error('password') is-invalid @enderror" id="password" name="password" type="password" placeholder="Password" />
                                    <label for="password">Password</label>
                                    @error('password')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                    <a class="small" href="{{ route('password.request') }}" style="text-decoration: none">Forgot Password?</a>
                                    <button type="submit" class="btn btn-primary">Login</button>
                                </div>

                            </form>
                        </div>
                        <div class="card-footer text-center py-3">
                            <div class="small"><a href="{{ route('register') }}" style="text-decoration: none">Need an account? Sign up!</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

