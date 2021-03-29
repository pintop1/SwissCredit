@extends('layouts.auth')

@section('title', __('Log In'))

@section('content')
<div class="auth-page">
    <div class="card auth-card shadow-lg">
        <div class="card-body">
            <div class="px-3">
                <div class="auth-logo-box"><a href="/" class="logo logo-admin"><img src="{{ asset('assets/images/logo-sm.png') }}" height="55" alt="logo" class="auth-logo"></a></div>
                <div class="text-center auth-logo-text">
                    <h4 class="mt-0 mb-3 mt-5">Let's Get Started</h4>
                    <p class="text-muted mb-0">Sign in to continue to Dashboard</p>
                </div>
                <form class="form-horizontal auth-form my-4" action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>{{ __('E-Mail Address') }}</label>
                        <div class="input-group mb-3"><span class="auth-form-icon"><i class="dripicons-user"></i> </span><input type="text" class="form-control @error('email') is-invalid @enderror" placeholder="{{ __('E-Mail Address') }}" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus></div>
                        @error('email')
                            <div class="text-danger">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="userpassword">{{ __('Password') }}</label>
                        <div class="input-group mb-3"><span class="auth-form-icon"><i class="dripicons-lock"></i> </span><input type="password" class="form-control @error('password') is-invalid @enderror" id="userpassword" placeholder="{{ __('Password') }}" name="password" required autocomplete="current-password"></div>
                        @error('password')
                            <div class="text-danger">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>
                    <div class="form-group row mt-4">
                        <div class="col-sm-6">
                            <div class="custom-control custom-switch switch-success"><input type="checkbox" class="custom-control-input" id="customSwitchSuccess" name="remember" {{ old('remember') ? 'checked' : '' }}> <label class="custom-control-label text-muted" for="customSwitchSuccess">Remember me</label></div>
                        </div>
                        <div class="col-sm-6 text-right"><a href="{{ route('password.request') }}" class="text-muted font-13"><i class="dripicons-lock"></i> {{ __('Forgot Your Password?') }}</a></div>
                    </div>
                    <div class="form-group mb-0 row">
                        <div class="col-12 mt-2"><button class="btn btn-primary btn-round btn-block waves-effect waves-light" type="submit">{{ __('Log In') }}<i class="fas fa-sign-in-alt ml-1"></i></button></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection