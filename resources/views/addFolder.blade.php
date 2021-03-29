@php
use App\Http\Controllers\Globals as Utils;
use Illuminate\Support\Collection;
@endphp

@extends('layouts.app')

@section('title', __('File Manager > Add Folder || Swiss Credit Data Management system'))

@section('apps', __('active'))
@section('fm', __('active'))

@section('head')
@endsection

@section('breadcrumb')
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="float-right">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="/file-manager">File Manager</a></li>
                    <li class="breadcrumb-item active">Add Folder</li>
                </ol>
            </div>
            <h4 class="page-title">Add Folder</h4>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('createFolder') }}" method="post">
                    @csrf
                    <div class="form-group col-lg-12">
                        <label>Folder Name</label> 
                        <input type="hidden" name="prev" value="{{ URL::previous() }}">
                        <input type="hidden" name="slug" value="{{ $slug }}">
                        <input type="text" name="name" class="form-control" required="">
                    </div>
                    <button class="btn btn-purple btn-block" type="submit">Create Folder</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('foot')
<script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
@endsection