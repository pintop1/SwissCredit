@php
use App\Http\Controllers\Globals as Utils;
use Illuminate\Support\Collection;
@endphp

@extends('layouts.app')

@section('title', __('My Profile || Swiss Credit Data Management system'))


@section('head')
<script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
<link href="{{ asset('assets/plugins/dropify/css/dropify.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/plugins/filter/magnific-popup.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/plugins/lightpick/lightpick.css') }}" rel="stylesheet">
@endsection

@section('breadcrumb')
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="float-right">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                    <li class="breadcrumb-item active">Profile</li>
                </ol>
            </div>
            <h4 class="page-title">Profile</h4>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body met-pro-bg">
                <div class="met-profile">
                    <div class="row">
                        <div class="col-lg-4 align-self-center mb-3 mb-lg-0">
                            <div class="met-profile-main">
                                <div class="met-profile-main-pic">
                                    @if($user->passport != null)
                                    <img src="{{ asset($user->passport) }}" alt="" width="140" class="rounded-circle"> 
                                    @else
                                    <img src="{{ Gravatar::get($user->email) }}" alt="" class="rounded-circle"> 
                                    @endif
                                    <span class="fro-profile_main-pic-change"><i class="fas fa-camera"></i></span>
                                </div>
                                <div class="met-profile_user-detail">
                                    <h5 class="met-user-name">{{ ucwords($user->name) }}</h5>
                                    <p class="mb-0 met-user-name-post">{{ strtoupper($user->role) }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 ml-auto">
                            <ul class="list-unstyled personal-detail">
                                <li class="mt-2"><i class="dripicons-mail text-info font-18 mt-2 mr-2"></i> <b>Email </b>: {{ strtolower($user->email) }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <ul class="nav nav-pills mb-0" id="pills-tab" role="tablist">
                    <li class="nav-item"><a class="nav-link active" id="settings_detail_tab" data-toggle="pill" href="#settings_detail">Settings</a></li>
                </ul>
            </div>
            <!--end card-body-->
        </div>
        <!--end card-->
    </div>
    <!--end col-->
</div>
<!--end row-->
<div class="row">
    <div class="col-12">
        <div class="tab-content detail-list" id="pills-tabContent">
            <div class="tab-pane active " id="settings_detail">
                <div class="row">
                    <div class="col-lg-12 col-xl-9 mx-auto">
                        <div class="card">
                            <div class="card-body">
                                <form method="post" class="card-box" action="{{ route('profile') }}" enctype="multipart/form-data">
                                    @csrf
                                    <input type="file" id="input-file-now" class="dropify" name="passport" data-default-file="{{ asset($user->passport) }}">
                                    <form class="form-horizontal form-material mb-0">
                                        <div class="form-group"><input type="text" placeholder="Full Name" class="form-control" name="name" value="{{ $user->name }}"></div>
                                        <div class="form-group row">
                                            <div class="col-md-6"><input type="email" placeholder="Email" class="form-control" name="email"  value="{{ $user->email }}" readonly=""></div>
                                            <div class="col-md-6"><input type="text" placeholder="Staff ID" class="form-control" name="staff_id" value="{{ $user->staff_id }}"></div>
                                        </div>
                                        <div class="form-group"><button type="submit" class="btn btn-gradient-primary btn-sm px-4 mt-3 float-right mb-0">Update Profile</button></div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end col-->
                </div>
                <!--end row-->
            </div>
            <!--end settings detail-->
        </div>
        <!--end tab-content-->
    </div>
    <!--end col-->
</div>
@endsection

@section('foot')
<script src="{{ asset('assets/plugins/dropify/js/dropify.min.js') }}"></script>
<script src="{{ asset('assets/plugins/moment/moment.js') }}"></script>
<script src="{{ asset('assets/plugins/filter/isotope.pkgd.min.js') }}"></script>
<script src="{{ asset('assets/plugins/filter/masonry.pkgd.min.js') }}"></script>
<script src="{{ asset('assets/plugins/filter/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('assets/plugins/lightpick/lightpick.js') }}"></script>
<script src="{{ asset('assets/pages/jquery.profile.init.js') }}"></script>
@endsection