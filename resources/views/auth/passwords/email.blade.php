@extends('layouts.app')
@section('sidebar')
    {{-- Leave this section empty to exclude the sidebar --}}
@endsection
@section('navbar')
    {{-- Leave this section empty to exclude the sidebar --}}
@endsection
@section('content')
    <div class="container">
        <div class="row align-items-center h-100">
            <form class="col-lg-4 col-md-4 col-10 mx-auto text-center"method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="mx-auto text-center my-4">
                    <a class="navbar-brand mx-auto mt-2 flex-fill text-center" href="./index.html">
                        <svg version="1.1" id="logo" class="navbar-brand-img brand-md" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 120 120"
                            xml:space="preserve">
                            <g>
                                <polygon class="st0" points="78,105 15,105 24,87 87,87 	" />
                                <polygon class="st0" points="96,69 33,69 42,51 105,51 	" />
                                <polygon class="st0" points="78,33 15,33 24,15 87,15 	" />
                            </g>
                        </svg>
                    </a>
                    <h2 class="my-3">{{ __('Reset Password') }}</h2>
                </div>

                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <p class="text-muted">Enter your email address and we'll send you an email with instructions to reset your
                    password</p>
                <div class="form-group">
                    <label for="inputEmail" class="sr-only">Email address</label>
                    <input type="email" id="inputEmail"
                        class="form-control form-control-lg @error('email') is-invalid @enderror" name="email"
                        placeholder="Email address" value="{{ old('email') }}" required autocomplete="email" autofocus>

                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <button class="btn btn-lg btn-primary btn-block" type="submit">Reset Password</button>
                <p class="mt-5 mb-3 text-muted">© 2024</p>
            </form>
        </div>
    </div>
@endsection
