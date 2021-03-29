@extends('layouts.auth')

@section('title', __('Server Error'))

@section('content')
<div class="auth-page">
    <div class="card auth-card shadow-lg">
        <div class="card-body">
            <div class="px-3">
                <div class="auth-logo-box">
                    <a href="/" class="logo logo-admin">
                        <img src="{{ asset('assets/images/logo-sm.png') }}" height="55" alt="logo" class="auth-logo">
                    </a>
                </div>
                <img src="{{ asset('assets/images/500.png') }}" alt="" class="d-block mx-auto mt-4" height="250">
                <div class="text-center auth-logo-text mb-4">
                    <h4 class="mt-0 mb-3 mt-5">Something went wrong!</h4>
                    <a href="/" class="btn btn-sm btn-gradient-primary">Back to Home</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection