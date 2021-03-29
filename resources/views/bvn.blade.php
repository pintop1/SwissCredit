@php
use App\Http\Controllers\Globals as Utils;
use Illuminate\Support\Collection;
@endphp

@extends('layouts.app')

@section('title', __('BVN Verification || Swiss Credit Data Management system'))

@section('apps', __('active'))
@section('bvn', __('active'))

@section('head')
@endsection

@section('breadcrumb')
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="float-right">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                    <li class="breadcrumb-item active">File Manager</li>
                </ol>
            </div>
            <h4 class="page-title">BVN Verification</h4>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('bvnCheck') }}" method="post">
                    @csrf
                    <div class="form-group col-lg-12">
                        <label>BVN</label> 
                        <input type="text" name="bvn" class="form-control" required="">
                    </div>
                    <button class="btn btn-purple btn-block" type="submit">Verify Now</button>
                </form>
            </div>
        </div>
    </div>
    @if(isset($bvnDetails))
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="lni-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> {{ $bvnDetails->message }}</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>
                @php
                $dob = explode('-', $bvnDetails->data->dob);
                @endphp
                <div class="border-bottom"><span class="text-bold">BVN</span> <span class="text-pink"> {{ $bvnDetails->data->bvn }}</span></div>
                <div class="border-bottom"><span class="text-bold">FIRST NAME</span> <span class="text-pink"> {{ $bvnDetails->data->first_name }}</span></div>
                <div class="border-bottom"><span class="text-bold">LAST NAME</span> <span class="text-pink"> {{ $bvnDetails->data->last_name }}</span></div>
                <div class="border-bottom"><span class="text-bold">DOB</span> <span class="text-pink"> {{ $dob[0] }} - {{ $dob[1] }}</span></div>
                <div class="border-bottom"><span class="text-bold">MOBILE</span> <span class="text-pink"> {{ $bvnDetails->data->mobile }}</span></div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@section('foot')
<script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
@endsection