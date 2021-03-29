@php
use App\Http\Controllers\Globals as Utils;
use Illuminate\Support\Collection;
@endphp

@extends('layouts.app')

@section('title', __('My Tasks || Swiss Credit Data Management system'))

@section('dashboard', __('active'))
@section('tasks', __('active'))

@section('head')
@endsection

@section('breadcrumb')
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="float-right">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                    <li class="breadcrumb-item active">Tasks</li>
                </ol>
            </div>
            <h4 class="page-title">Tasks</h4>
        </div>
    </div>
</div>
@endsection

@section('content')
@if($user->role == 'hos' || $user->role == 'internal control' || $user->role == 'director')
<div class="row">
    <div class="col-lg-6">
    </div>
    <div class="col-lg-6 text-right">
        <div class="text-right">
            <ul class="list-inline">
                <li class="list-inline-item"><button type="button" class="btn btn-gradient-primary"><i class="dripicons-gear"></i></button></li>
                <li class="list-inline-item"><a href="/tasks/add" class="btn btn-gradient-primary">Add New Task</a></li>
            </ul>
        </div>
    </div>
</div>
@endif
@if(count($tasks) > 0 && count($ctasks) > 0)
<div class="row">
    <div class="col-lg-6">
        <h4 class="title">Assigned Tasks</h4> 
        @foreach($tasks as $key => $dt)
            @php
            $task = Utils::getTask($dt);
            @endphp
        <div class="card">
            <div class="card-body">
                <div class="task-box">
                    <div class="task-priority-icon"><i class="fas fa-circle text{{ Utils::getColor2($task->status) }}"></i></div>
                    <p class="text-muted float-right"><span class="text-muted">{{ date('H:m', strtotime($task->created_at)) }}</span> / <span class="text-muted">{{ date('H:m', strtotime($task->due_time)) }}</span> <span class="mx-1">·</span> <span><i class="far fa-fw fa-clock"></i> {{ date('M d', strtotime($task->created_at)) }}</span></p>
                    <h5 class="mt-0">{{ $task->title }}</h5>
                    <p class="text-muted mb-1">{{ $task->message }}</p>
                    <p class="text-muted text-right mb-1">{{ round($task->status)}}% Complete</p>
                    <div class="progress mb-4" style="height: 4px;">
                        <div class="progress-bar {{ Utils::getColor($task->status) }}" role="progressbar" style="width: {{ round($task->status)}}%;" aria-valuenow="{{ round($task->status)}}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="img-group">
                            @foreach(json_decode($task->users) as $taskee)
                                @php
                                $ing = Utils::getUserByEmail($taskee);
                                @endphp
                            <a class="user-avatar user-avatar-group" href="/staff/view/{{ $ing->id }}">
                                <img src="{{ ($ing->passport != null)? asset($ing->passport):Gravatar::get($ing->email) }}" alt="user" class="rounded-circle thumb-xs"> 
                            </a>
                            @endforeach
                        </div>
                        <ul class="list-inline mb-0 align-self-center">
                            <li class="list-item d-inline-block mr-2">
                                <a class="" href="#">
                                    <i class="mdi mdi-format-list-bulleted text{{ Utils::getColor2($task->status) }} font-15"></i> 
                                    <span class="text-muted font-weight-bold">{{ round($task->status) }}/100</span>
                                </a>
                            </li>
                            <li class="list-item d-inline-block">
                                @if(!Utils::getTaskDone($user, $task->id))
                                <a class="ml-2" href="/tasks/{{ $task->id }}/done"><i class="lni-pencil text-muted font-18"></i></a>
                                @else
                                <a class="ml-2" href="#"><i class="lni-pencil text-success font-18"></i></a>
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="col-lg-6">
        <h4 class="title">Created Tasks</h4> 
        @foreach($ctasks as $key => $task)
        <div class="card">
            <div class="card-body">
                <div class="task-box">
                    <div class="task-priority-icon"><i class="fas fa-circle text{{ Utils::getColor2($task->status) }}"></i></div>
                    <p class="text-muted float-right"><span class="text-muted">{{ date('H:m', strtotime($task->created_at)) }}</span> / <span class="text-muted">{{ date('H:m', strtotime($task->due_time)) }}</span> <span class="mx-1">·</span> <span><i class="far fa-fw fa-clock"></i> {{ date('M d', strtotime($task->created_at)) }}</span></p>
                    <h5 class="mt-0">{{ $task->title }}</h5>
                    <p class="text-muted mb-1">{{ $task->message }}</p>
                    <p class="text-muted text-right mb-1">{{ round($task->status)}}% Complete</p>
                    <div class="progress mb-4" style="height: 4px;">
                        <div class="progress-bar {{ Utils::getColor($task->status) }}" role="progressbar" style="width: {{ round($task->status)}}%;" aria-valuenow="{{ round($task->status)}}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="img-group">
                            @foreach(json_decode($task->users) as $taskee)
                                @php
                                $ing = Utils::getUserByEmail($taskee);
                                @endphp
                            <a class="user-avatar user-avatar-group" href="/staff/view/{{ $ing->id }}">
                                <img src="{{ ($ing->passport != null)? asset($ing->passport):Gravatar::get($ing->email) }}" alt="user" class="rounded-circle thumb-xs"> 
                            </a>
                            @endforeach
                        </div>
                        <ul class="list-inline mb-0 align-self-center">
                            <li class="list-item d-inline-block mr-2">
                                <a class="" href="#">
                                    <i class="mdi mdi-format-list-bulleted text{{ Utils::getColor2($task->status) }} font-15"></i> 
                                    <span class="text-muted font-weight-bold">{{ round($task->status) }}/100</span>
                                </a>
                            </li>
                            @if($user->email == $task->staff)
                            <li class="list-item d-inline-block"><a class="" href="/tasks/{{ $task->id }}/delete"><i class="lni-trash text-muted font-18"></i></a></li>
                            @endif
                            <li class="list-item d-inline-block"><a class="" href="/tasks/{{ $task->id }}/view"><i class="lni-eye text-primary font-18"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@elseif(count($ctasks) > 0)
<div class="row">
    <div class="col-lg-6">
        <h4>Created Tasks</h4>
        @foreach($ctasks as $key => $task)
        <div class="card">
            <div class="card-body">
                <div class="task-box">
                    <div class="task-priority-icon"><i class="fas fa-circle text{{ Utils::getColor2($task->status) }}"></i></div>
                    <p class="text-muted float-right"><span class="text-muted">{{ date('H:m', strtotime($task->created_at)) }}</span> / <span class="text-muted">{{ date('H:m', strtotime($task->due_time)) }}</span> <span class="mx-1">·</span> <span><i class="far fa-fw fa-clock"></i> {{ date('M d', strtotime($task->created_at)) }}</span></p>
                    <h5 class="mt-0">{{ $task->title }}</h5>
                    <p class="text-muted mb-1">{{ $task->message }}</p>
                    <p class="text-muted text-right mb-1">{{ round($task->status)}}% Complete</p>
                    <div class="progress mb-4" style="height: 4px;">
                        <div class="progress-bar {{ Utils::getColor($task->status) }}" role="progressbar" style="width: {{ round($task->status)}}%;" aria-valuenow="{{ round($task->status)}}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="img-group">
                            @foreach(json_decode($task->users) as $taskee)
                                @php
                                $ing = Utils::getUserByEmail($taskee);
                                @endphp
                            <a class="user-avatar user-avatar-group" href="/staff/view/{{ $ing->id }}">
                                <img src="{{ ($ing->passport != null)? asset($ing->passport):Gravatar::get($ing->email) }}" alt="user" class="rounded-circle thumb-xs"> 
                            </a>
                            @endforeach
                        </div>
                        <ul class="list-inline mb-0 align-self-center">
                            <li class="list-item d-inline-block mr-2">
                                <a class="" href="#">
                                    <i class="mdi mdi-format-list-bulleted text{{ Utils::getColor2($task->status) }} font-15"></i> 
                                    <span class="text-muted font-weight-bold">{{ round($task->status) }}/100</span>
                                </a>
                            </li>
                            @if($user->email == $task->staff)
                            <li class="list-item d-inline-block"><a class="" href="/tasks/{{ $task->id }}/delete"><i class="lni-trash text-muted font-18"></i></a></li>
                            @endif
                            <li class="list-item d-inline-block"><a class="" href="/tasks/{{ $task->id }}/view"><i class="lni-eye text-primary font-18"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@elseif(count($tasks) > 0)
<div class="row">
    <div class="col-lg-6">
        @foreach($tasks as $key => $dt)
        @php
        $task = Utils::getTask($dt);
        @endphp
        <h4>Assigned Tasks</h4>
        <div class="card">
            <div class="card-body">
                <div class="task-box">
                    <div class="task-priority-icon"><i class="fas fa-circle text{{ Utils::getColor2($task->status) }}"></i></div>
                    <p class="text-muted float-right"><span class="text-muted">{{ date('H:m', strtotime($task->created_at)) }}</span> / <span class="text-muted">{{ date('H:m', strtotime($task->due_time)) }}</span> <span class="mx-1">·</span> <span><i class="far fa-fw fa-clock"></i> {{ date('M d', strtotime($task->created_at)) }}</span></p>
                    <h5 class="mt-0">{{ $task->title }}</h5>
                    <p class="text-muted mb-1">{{ $task->message }}</p>
                    <p class="text-muted text-right mb-1">{{ round($task->status)}}% Complete</p>
                    <div class="progress mb-4" style="height: 4px;">
                        <div class="progress-bar {{ Utils::getColor($task->status) }}" role="progressbar" style="width: {{ round($task->status)}}%;" aria-valuenow="{{ round($task->status)}}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="img-group">
                            @foreach(json_decode($task->users) as $taskee)
                                @php
                                $ing = Utils::getUserByEmail($taskee);
                                @endphp
                            <a class="user-avatar user-avatar-group" href="/staff/view/{{ $ing->id }}">
                                <img src="{{ ($ing->passport != null)? asset($ing->passport):Gravatar::get($ing->email) }}" alt="user" class="rounded-circle thumb-xs"> 
                            </a>
                            @endforeach
                        </div>
                        <ul class="list-inline mb-0 align-self-center">
                            <li class="list-item d-inline-block mr-2">
                                <a class="" href="#">
                                    <i class="mdi mdi-format-list-bulleted text{{ Utils::getColor2($task->status) }} font-15"></i> 
                                    <span class="text-muted font-weight-bold">{{ round($task->status) }}/100</span>
                                </a>
                            </li>
                            <li class="list-item d-inline-block">
                                @if(!Utils::getTaskDone($user, $task->id))
                                <a class="ml-2" href="/tasks/{{ $task->id }}/done"><i class="lni-pencil text-muted font-18"></i></a>
                                @else
                                <a class="ml-2" href="#"><i class="lni-pencil text-success font-18"></i></a>
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@else
<div class="alert icon-custom-alert alert-outline-pink b-round fade show" role="alert">                                            
    <i class="lni-warning alert-icon"></i>
    <div class="alert-text">
        <strong>Oh snap!</strong> No tasks found on your account.
    </div>
    <div class="alert-close">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><i class="lni-close text-danger"></i></span>
        </button>
    </div>
</div>  
@endif
@endsection

@section('foot')
<script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/tour/tour_task.js') }}"></script>
<script src="{{ asset('assets/pages/jquery.projects_task.init.js') }}"></script>
@endsection