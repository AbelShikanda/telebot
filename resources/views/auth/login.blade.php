@extends('layouts.app')
@section('sidebar')
    {{-- Leave this section empty to exclude the sidebar --}}
@endsection
@section('navbar')
    {{-- Leave this section empty to exclude the sidebar --}}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row align-items-center h-100">
            <form class="col-lg-3 col-md-4 col-10 mx-auto text-center" method="POST" action="{{ route('login') }}">
                @csrf
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
                <h1 class="h6 mb-3">{{ __('Login') }}</h1>
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
                <div class="form-group">
                    <label for="inputPassword" class="sr-only">Password</label>
                    <input type="password" id="inputPassword"
                        class="form-control form-control-lg @error('password') is-invalid @enderror" name="password"
                        placeholder="Password" required autocomplete="current-password">

                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="checkbox mb-3">
                    <label>
                        <input type="checkbox" value="remember-me" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        Stay logged in
                    </label>
                </div>
                <button class="btn btn-lg btn-primary btn-block" type="submit">Let me in</button>
                <p class="mt-5 mb-3 text-muted">© 2024</p>
                
                @if (Route::has('password.request'))
                <a class="btn btn-link" href="{{ route('password.request') }}">
                    {{ __('Forgot Your Password?') }}
                </a>
            @endif
            </form>
        </div>
    </div>
@endsection
