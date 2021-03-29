@extends('layouts.auth')

@section('title', __('Reset Password'))

@section('content')
<div class="auth-page">
    <div class="card auth-card shadow-lg">
        <div class="card-body">
            <div class="px-3">
                <div class="auth-logo-box"><a href="/" class="logo logo-admin"><img src="{{ asset('assets/images/logo-sm.png') }}" height="55" alt="logo" class="auth-logo"></a></div>
                <div class="text-center auth-logo-text">
                    <h4 class="mt-0 mb-3 mt-5">Reset Your Password</h4>
                </div>
                <form class="form-horizontal auth-form my-4" action="{{ route('password.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="form-group">
                        <label>{{ __('E-Mail Address') }}</label>
                        <div class="input-group mb-3"><span class="auth-form-icon"><i class="dripicons-user"></i> </span><input type="text" class="form-control @error('email') is-invalid @enderror" placeholder="{{ __('E-Mail Address') }}" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus></div>
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
                    <div class="form-group">
                        <label for="userpassword">{{ __('Confirm Password') }}</label>
                        <div class="input-group mb-3"><span class="auth-form-icon"><i class="dripicons-lock"></i> </span><input type="password" class="form-control" placeholder="{{ __('Confirm Password') }}" name="password_confirmation" required></div>
                    </div>
                    <div class="form-group mb-0 row">
                        <div class="col-12 mt-2"><button class="btn btn-primary btn-round btn-block waves-effect waves-light" type="submit">{{ __('Reset Password') }}<i class="fas fa-sign-in-alt ml-1"></i></button></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection