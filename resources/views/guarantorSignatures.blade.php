@php
use App\Http\Controllers\Globals as Utils;
use Illuminate\Support\Collection;
@endphp

@extends('layouts.app')

@section('title', __('Guarantors > Signatures || Swiss Credit Data Management system'))

@section('data', __('active'))
@section('offer', __('active'))

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
                    <li class="breadcrumb-item"><a href="/offers">Offers</a></li>
                    <li class="breadcrumb-item active">Signatures</li>
                </ol>
            </div>
            <h4 class="page-title">Signatures</h4>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    @if(isset($signatures) && $signatures->id != null)
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6 border-right border-gray">
                        {!! $signatures->sign_1 !!}
                        @if($signatures->default == '1')
                        <p><a href="javascript:void(0);" class="btn btn-danger btn-sm">This is the guarantor's recommended signature</a></p>
                        <!--<p><a href="/offers/guarantors/signature/{{ $signatures->id }}/use-1" class="btn btn-primary btn-sm">Use this signature</a></p>-->
                        @endif
                    </div>
                    <div class="col-lg-6 border-right border-gray">
                        {!! $signatures->sign_2 !!}
                        @if($signatures->default == '2')
                        <p><a href="javascript:void(0);" class="btn btn-danger btn-sm">This is the guarantor's recommended signature</a></p>
                        <!--<p><a href="/offers/guarantors/signature/{{ $signatures->id }}/use-2" class="btn btn-primary btn-sm">Use this signature</a></p>-->
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@section('foot')
<script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/plugins/dropify/js/dropify.min.js') }}"></script>
<script src="{{ asset('assets/pages/jquery.form-upload.init.js') }}"></script>
@endsection