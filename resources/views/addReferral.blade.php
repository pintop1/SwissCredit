@php
use App\Http\Controllers\Globals as Utils;
use Illuminate\Support\Collection;
@endphp

@extends('layouts.app')

@section('title', __('Referrals > Add Referral || Swiss Credit Data Management system'))

@section('forms', __('active'))
@section('asf', __('active'))

@section('head')
<link href="{{ asset('assets/plugins/dropify/css/dropify.min.css') }}" rel="stylesheet">
@endsection

@section('breadcrumb')
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="float-right">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="/referrals">Referral</a></li>
                    <li class="breadcrumb-item active">Add Referral</li>
                </ol>
            </div>
            <h4 class="page-title">Add Referral</h4>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('addReferral') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="form-group col-lg-4">
                            <label>Name</label> 
                            <input type="text" name="name" class="form-control" required="">
                        </div>
                        <div class="form-group col-lg-4">
                            <label>Email</label> 
                            <input type="email" name="email" class="form-control" required="">
                            @error('email')
                            <div class="text-danger">
                                <strong>{{ $message }}</strong>
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-lg-4">
                            <label>Phone</label> 
                            <input type="text" name="phone" class="form-control" required="">
                            @error('phone')
                            <div class="text-danger">
                                <strong>{{ $message }}</strong>
                            </div>
                            @enderror
                        </div>
                    </div>
                    <button class="btn btn-purple btn-block" type="submit">Add Referral</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('foot')
<script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/plugins/dropify/js/dropify.min.js') }}"></script>
<script src="{{ asset('assets/pages/jquery.form-upload.init.js') }}"></script>
@endsection