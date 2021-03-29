@php
use App\Http\Controllers\Globals as Utils;
use Illuminate\Support\Collection;
@endphp

@extends('layouts.app')

@section('title', __('Dashboard || Swiss Credit Data Management system'))

@section('dashboard', __('active'))
@section('dashboardd', __('active'))

@section('head')
@endsection

@section('breadcrumb')
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="float-right">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
            <h4 class="page-title">Dashboard</h4>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-3" id="tasks">
                <div class="card">
                    <div class="card-body">
                        <a href="/tasks">
                        <div class="row">
                            <div class="col-4 align-self-center">
                                <div class="icon-info"><i class="dripicons-to-do align-self-center icon-lg icon-dual-warning"></i></div>
                            </div>
                            <div class="col-8 align-self-center text-right">
                                <div class="ml-2">
                                    <p class="mb-1 text-muted">Team</p>
                                    <h3 class="mt-0 mb-1 font-weight-semibold">{{ $teamCount }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="progress mt-2" style="height:3px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                        </div></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3" id="activities">
                <div class="card">
                    <div class="card-body">
                        <a href="/">
                        <div class="row">
                            <div class="col-4 align-self-center">
                                <div class="icon-info"><i class="lni-bar-chart align-self-center icon-lg icon-dual-success"></i></div>
                            </div>
                            <div class="col-8 align-self-center text-right">
                                <div class="ml-2">
                                    <p class="mb-1 text-muted">Activities</p>
                                    <h3 class="mt-0 mb-1 font-weight-semibold d-inline-block">{{ $activityCount }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="progress mt-2" style="height:3px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ round(Utils::getActivityPercent($user)) }}%;" aria-valuenow="{{ Utils::getActivityPercent($user) }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div></a>
                    </div>
                </div>
            </div><!--
            <div class="col-lg-2" id="comments">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4 align-self-center">
                                <div class="icon-info"><i class="lni-comment align-self-center icon-lg icon-dual-purple"></i></div>
                            </div>
                            <div class="col-8 align-self-center text-right">
                                <div class="ml-2">
                                    <p class="mb-1 text-muted">Comments</p>
                                    <h3 class="mt-0 mb-1 font-weight-semibold">{{ $commentCount }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="progress mt-2" style="height:3px;">
                            <div class="progress-bar bg-purple" role="progressbar" style="width: {{ round(Utils::getCommentPercent($user)) }}%;" aria-valuenow="{{ round(Utils::getCommentPercent($user)) }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>-->
            <div class="col-lg-2" id="customers">
                <div class="card">
                    <div class="card-body">
                        <a href="/customers">
                        <div class="row">
                            <div class="col-4 align-self-center">
                                <div class="icon-info"><i class="dripicons-user-group align-self-center icon-lg icon-dual-danger"></i></div>
                            </div>
                            <div class="col-8 align-self-center text-right">
                                <div class="ml-2">
                                    <p class="mb-1 text-muted">Customers</p>
                                    <h3 class="mt-0 mb-1 font-weight-semibold">{{ $customerCount }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="progress mt-2" style="height:3px;">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: {{ round(Utils::getCustomerPercent($user)) }}%;" aria-valuenow="{{ round(Utils::getCustomerPercent($user)) }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-2" id="folders">
                <div class="card">
                    <div class="card-body">
                        <a href="/file-manager">
                        <div class="row">
                            <div class="col-4 align-self-center">
                                <div class="icon-info"><i class="lni-folder align-self-center icon-lg icon-dual-violet"></i></div>
                            </div>
                            <div class="col-8 align-self-center text-right">
                                <div class="ml-2">
                                    <p class="mb-1 text-muted">Folders</p>
                                    <h3 class="mt-0 mb-1 font-weight-semibold">{{ $folderCount }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="progress mt-2" style="height:3px;">
                            <div class="progress-bar bg-violet" role="progressbar" style="width: {{ round(Utils::getFolderPercent($user)) }}%;" aria-valuenow="{{ round(Utils::getFolderPercent($user)) }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-2" id="teams">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4 align-self-center">
                                <div class="icon-info"><i class="lni-users align-self-center icon-lg icon-dual-pink"></i></div>
                            </div>
                            <div class="col-8 align-self-center text-right">
                                <div class="ml-2">
                                    <p class="mb-1 text-muted">Team</p>
                                    <h3 class="mt-0 mb-1 font-weight-semibold">{{ $teamCount }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="progress mt-2" style="height:3px;">
                            <div class="progress-bar bg-pink" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row" id="dactivities">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mt-0 mb-4">Activity</h4>
                <div class="slimscroll crm-dash-activity">
                    <div class="activity">
                        @foreach(Utils::getActivities($user) as $activity)
                        <div class="activity-info">
                            <div class="icon-info-activity">{!! $activity->icon !!}</div>
                            <div class="activity-info-text">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="m-0 w-75">{{ $activity->title }}</h6>
                                    <span class="text-muted d-block font-12">{{ Utils::convertTime($activity->created_at) }}</span>
                                </div>
                                <p class="text-muted mt-3">{!! $activity->message !!}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('foot')
<script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/tour/tour_home.js') }}"></script>
<script src="{{ asset('assets/pages/jquery.projects-index.init.js') }}"></script>
@endsection