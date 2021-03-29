@php
use App\Http\Controllers\Globals as Utils;
use Illuminate\Support\Collection;
@endphp

@extends('layouts.app')

@section('title', __('File Manager > Add Links || Swiss Credit Data Management system'))

@section('apps', __('active'))
@section('fm', __('active'))

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
                    <li class="breadcrumb-item"><a href="/file-manager">File Manager</a></li>
                    <li class="breadcrumb-item active">Add Links</li>
                </ol>
            </div>
            <h4 class="page-title">Add Links</h4>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('addLinks') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group col-lg-12">
                        <label>Title</label> 
                        <input type="hidden" name="prev" value="{{ URL::previous() }}">
                        <input type="hidden" name="slug" value="{{ $slug }}">
                        <input type="text" name="title" class="form-control" required="">
                    </div>
                    <div class="form-group col-lg-12">
                        <label>Link URL</label> 
                        <input type="url" name="link" class="form-control" required="">
                    </div>
                    <button class="btn btn-purple btn-block" type="submit">Upload Link</button>
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