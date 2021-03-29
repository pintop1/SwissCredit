@php
use App\Http\Controllers\Globals as Utils;
use Illuminate\Support\Collection;
@endphp

@extends('layouts.app')

@section('title', __('Tasks > Add Task || Swiss Credit Data Management system'))

@section('forms', __('active'))
@section('at', __('active'))

@section('head')
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
@endsection

@section('breadcrumb')
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="float-right">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="/tasks">Tasks</a></li>
                    <li class="breadcrumb-item active">Add Task</li>
                </ol>
            </div>
            <h4 class="page-title">Add Task</h4>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('addTask') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="form-group col-lg-12">
                            <label>Title</label> 
                            <input type="text" name="title" class="form-control" required="">
                        </div>
                        <div class="form-group col-lg-12">
                            <label>Description</label> 
                            <textarea class="form-control" name="message"></textarea>
                        </div>
                        <div class="form-group col-lg-12">
                            <label>Staffs (<span class="text-danger">You can assign 1 task to more than 1 staff at the same time</span>)</label> 
                            <select class="form-control" name="users[]" required="" multiple="multiple">
                                <option value="">Please Select</option>
                                @foreach($users as $use)
                                <option value="{{ $use->email }}">{{ ucwords($use->name) }} - {{ $use->team }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-12">
                            <label>Due in (hours)</label> 
                            <input type="number" name="due_time" class="form-control" required="">
                        </div>
                    </div>
                    <button class="btn btn-purple btn-block" type="submit">Add Tasks</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('foot')
@endsection