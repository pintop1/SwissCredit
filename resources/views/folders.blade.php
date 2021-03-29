@php
use App\Http\Controllers\Globals as Utils;
use Illuminate\Support\Collection;
@endphp

@extends('layouts.app')

@section('title', __('File Manager || Swiss Credit Data Management system'))

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
                    <li class="breadcrumb-item active">File Manager</li>
                </ol>
            </div>
            <h4 class="page-title" id="title">File Manager</h4>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12" id="capacity">
        <div class="card">
            <div class="card-body">
                @php
                $used = round(Utils::convertBytes(disk_total_space("/")-disk_free_space("/")),2);
                $total = round(Utils::convertBytes(disk_total_space("/")),2);
                @endphp
                <small class="float-right">{{ round(($used/$total)*100) }}%</small>
                <h6 class="mt-0">{{ $used }} {{ Utils::getStorageSymbol(disk_total_space("/")) }} / {{ $total }} {{ Utils::getStorageSymbol(disk_total_space("/")) }} Used</h6>
                <div class="progress" style="height: 5px;">
                    <div class="progress-bar {{ Utils::getColorReverse(round(($used/$total)*100)) }}" role="progressbar" style="width: {{ round(($used/$total)*100) }}%;" aria-valuenow="{{ round(($used/$total)*100) }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="">
            <div class="tab-content" id="files-tabContent">
                @if($user->role == 'credit risk')
                <div class="float-right" id="create">
                    <a href="/file-manager/add" class="btn btn-primary btn-sm add-file ml-3">
                        <i class="lni-folder mr-2"></i>Create Folder
                    </a> 
                </div>
                @endif
                <div class="tab-pane fade show active" id="files-projects">
                    <h4 class="header-title mt-0 mb-3">Folders</h4>
                    <div class="file-box-content">
                        @foreach($folders as $folder)
                        <div class="file-box">
                            @if($user->role == 'monitoring and compliance')
                            <a href="/file-manager/delete/folder/{{ $folder->id }}" class="download-icon-link" onclick="return confirm('Are you sure you want to delete this folder?');"><i class="dripicons-trash file-download-icon"></i></a>
                            @endif
                            <div class="text-center">
                                <a href="/file-manager/view/{{ $folder->slug }}"><i class="lni-folder text-gray"></i></a>
                                <h6 class="text-truncate"><a href="/file-manager/view/{{ $folder->slug }}">{{ $folder->name }}</a></h6>
                                <small class="text-muted">{{ date('d M, Y', strtotime($folder->created_at)) }} / {{ ucwords(Utils::getUserByEmail($folder->staff)->name ?? '') }}</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 mt-4"> 
    {{ $folders->links('vendor.pagination.default') }}
    </div>
</div>
@endsection

@section('foot')
<script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/tour/tour_file.js') }}"></script>
@endsection