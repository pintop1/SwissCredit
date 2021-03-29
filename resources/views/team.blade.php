@php
use App\Http\Controllers\Globals as Utils;
use Illuminate\Support\Collection;
@endphp

@extends('layouts.app')

@section('title', __('My Teams || Swiss Credit Data Management system'))

@section('dashboard', __('active'))
@section('team', __('active'))

@section('head')
@endsection

@section('breadcrumb')
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="float-right">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                    <li class="breadcrumb-item active">Team</li>
                </ol>
            </div>
            <h4 class="page-title">Team</h4>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row" id="teams">
    @foreach($teams as $team)
    <div class="col-lg-3">
        <div class="card team-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h4 class="header-title mt-0 d-inline-block">{{ $team->team }}</h4>
                    @if($user->role == 'hos' || $user->role == 'internal control' || $user->role == 'director')
                        @if($team->email != $user->email)
                        <div class="dropdown d-inline-block">
                            <a class="nav-link dropdown-toggle arrow-none" id="dLabel1" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                <i class="fas fa-ellipsis-v font-20 text-muted"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dLabel1">
                                <a class="dropdown-item" href="/tasks/add/{{ $team->id }}">Add Task</a> 
                            </div>
                        </div>
                        @endif
                    @endif
                </div>
                <div class="text-center">
                    <img src="{{ ($team->passport != null)? asset($team->passport):Gravatar::get($team->email) }}" alt="" class="rounded-circle user-img img-thumbnail">
                    <div class="online-circle">
                        @if($team->isOnline())
                        <i class="fa fa-circle text-success"></i>
                        @else
                        <i class="fa fa-circle text-gray"></i>
                        @endif
                    </div>
                    <h4 class="team-leader">{{ ucwords($team->name) }}</h4>
                    <p class="text-muted font-12">
                        @if($team->lead == 'true' || $team->role == 'hos')
                        Team Leader
                        @else 
                        Team Member
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection

@section('foot')
<script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/tour/tour_team.js') }}"></script>
@endsection