@php
use App\Http\Controllers\Globals as Utils;
use Illuminate\Support\Collection;
@endphp

@extends('layouts.app')

@section('title', __('My Tasks > View Task > '.$task->title.' || Swiss Credit Data Management system'))

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
                    <li class="breadcrumb-item"><a href="/tasks">Tasks</a></li>
                    <li class="breadcrumb-item active">View Task</li>
                </ol>
            </div>
            <h4 class="page-title">{{ $task->title }}</h4>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
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
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end row-->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="mt-0 mb-3 header-title">Our Reguler Users</h4>
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Users</th>
                                <th>Roles</th>
                                <th>Email</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(json_decode($task->users) as $taskee)
                                @php
                                $ing = Utils::getUserByEmail($taskee);
                                @endphp
                            <tr>
                                <td>
                                    @if($ing->passport != null)
                                    <img src="{{ asset($ing->passport) }}" alt="" class="rounded-circle thumb-sm mr-1">
                                    @else
                                    <img src="{{ Gravatar::get($ing->email) }}" alt="" class="rounded-circle thumb-sm mr-1">
                                    @endif
                                     {{ ucwords($ing->name) }}
                                 </td>
                                <td>{{ ucwords($ing->role) }}</td>
                                <td>{{ strtolower($ing->email) }}</td>
                                <td>
                                    @if(Utils::getTaskDone($ing, $task->id))
                                    <span class="badge badge-soft-success">Completed</span>
                                    @else
                                    <span class="badge badge-soft-warning">Pending</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('foot')
<script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/pages/jquery.projects_task.init.js') }}"></script>
@endsection