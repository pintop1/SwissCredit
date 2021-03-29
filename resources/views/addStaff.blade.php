@php
use App\Http\Controllers\Globals as Utils;
use Illuminate\Support\Collection;
@endphp

@extends('layouts.app')

@section('title', __('Staffs > Add Staff || Swiss Credit Data Management system'))

@section('forms', __('active'))
@section('as', __('active'))

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
                    <li class="breadcrumb-item"><a href="/staffs">Staff</a></li>
                    <li class="breadcrumb-item active">Add Staff</li>
                </ol>
            </div>
            <h4 class="page-title">Add Staff</h4>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('addStaff') }}" method="post" enctype="multipart/form-data">
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
                            <label>Roles</label> 
                            <select class="form-control" name="role" required="">
                                <option value="">Please Select</option>
                                <option value="credit risk">Credit Risk</option>
                                <option value="underwriter">Underwriter</option>
                                <option value="risk">Risk</option>
                                <option value="recovery">Recovery</option>
                                <option value="operations">Operations</option>
                                <option value="finance">Finance</option>
                                <option value="internal control">Internal Control</option>
                                <option value="it">IT Officer</option>
                                <option value="director">Director</option>
                                <option value="monitoring and compliance">Monitoring and Compliance</option>
                            </select>
                        </div>
                        <div class="form-group col-lg-4">
                            <label>Is this staff a team leader?</label> 
                            <select class="form-control" name="lead" required="">
                                <option value="">Please Select</option>
                                <option value="true">Yes</option>
                                <option value="false">No</option>
                            </select>
                        </div>
                        <div class="form-group col-lg-4">
                            <label>Staff ID</label> 
                            <input type="text" name="staff_id" class="form-control" required="">
                            @error('staff_id')
                            <div class="text-danger">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                        </div>
                        <div class="form-group col-lg-4">
                            <label>Team</label> 
                            <select class="form-control" name="team" required="">
                                <option value="">Please Select</option>
                                <option>Sales Department</option>
                                <option>Risk Department</option>
                                <option>Recovery Department</option>
                                <option>Operations Department</option>
                                <option>IT Department</option>
                                <option>Management Department</option>
                            </select>
                        </div>
                    </div>
                    <button class="btn btn-purple btn-block" type="submit">Add Staff</button>
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