@extends('layouts.auth')

@section('title', __('Forgot Password'))

@section('content')
<div class="auth-page">
    <div class="card auth-card shadow-lg">
        <div class="card-body">
            <div class="px-3">
                <div class="auth-logo-box"><a href="/" class="logo logo-admin"><img src="{{ asset('assets/images/logo-sm.png') }}" height="55" alt="logo" class="auth-logo"></a></div>
                <div class="text-center auth-logo-text">
                    <h4 class="mt-0 mb-3 mt-5">Forgotten your password</h4>
                    <p class="text-muted mb-0">Enter your email address to reset it</p>
                </div>
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <form class="form-horizontal auth-form my-4" action="{{ route('password.email') }}" method="POST">
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
                    <div class="form-group mb-0 row">
                        <div class="col-12 mt-2"><button class="btn btn-primary btn-round btn-block waves-effect waves-light" type="submit">{{ __('Send Password Reset Link') }}<i class="fas fa-sign-in-alt ml-1"></i></button></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection