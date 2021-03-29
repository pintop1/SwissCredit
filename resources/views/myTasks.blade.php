@php
use App\Http\Controllers\Globals as Utils;
use Illuminate\Support\Collection;
$tasks = Utils::dpaginatee(Utils::getSpecificTask(), 10);
@endphp

@extends('layouts.app')

@section('title', __('My Pending Tasks || Swiss Credit Data Management system'))

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
                    <li class="breadcrumb-item active">Pending Tasks</li>
                </ol>
            </div>
            <h4 class="page-title">Pending Tasks</h4>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class = "header-title mt-0 mb-3"> My Tasks </h4>
                <div class = "main-timeline mt-3">
                    @foreach($tasks as $task)
                    <div class = "timeline">
                        <span class = "timeline-icon"> </span> <span class = "year"> {{ strtoupper($task['time']) }} </span>
                        <div class = "timeline-content">
                            <h5 class = "title"> {{ $task['title'] }} </h5>
                            <span class = "post"> {{ $task['time'] }} </span>
                            <p class = "description"> {!! $task['msg'] !!} {!! $task['action'] !!} </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 mt-4"> 
    {{ $tasks->links('vendor.pagination.default') }}
    </div>
</div>
@endsection

@section('foot')
<script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/plugins/dropify/js/dropify.min.js') }}"></script>
<script src="{{ asset('assets/pages/jquery.form-upload.init.js') }}"></script>
<script>
    $(function () {
        $('a.performing').on('click', function () {
          var url = ($(this).attr('data-target'));
          $(".loading").show();
          $.get(url, function(data){
            $(".loading").hide();
            $(".return").html(data);
          });
        });
    });
</script>
@endsection