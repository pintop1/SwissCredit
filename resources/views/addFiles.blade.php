@php
use App\Http\Controllers\Globals as Utils;
use Illuminate\Support\Collection;
@endphp

@extends('layouts.app')

@section('title', __('File Manager > Add Files || Swiss Credit Data Management system'))

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
                    <li class="breadcrumb-item active">Add Files</li>
                </ol>
            </div>
            <h4 class="page-title">Add Files</h4>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('addFiles') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group col-lg-12">
                        <label>Files</label> 
                        <input type="hidden" name="prev" value="{{ URL::previous() }}">
                        <input type="hidden" name="slug" value="{{ $slug }}">
                        <input type="file" name="uploads[]" id="input-file-now" class="dropify" multiple="">
                    </div>
                    <button class="btn btn-purple btn-block" type="submit">Upload Files</button>
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