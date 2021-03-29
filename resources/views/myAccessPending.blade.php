@php
use App\Http\Controllers\Globals as Utils;
@endphp

@extends('layouts.app')

@section('title', __('Permission Lists || Swiss Credit Data Management system'))

@section('app', __('active'))
@section('fmpep', __('active'))

@section('head')
<link href="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/plugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('breadcrumb')
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="float-right">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="/file-manager">File Manager</a></li>
                    <li class="breadcrumb-item active">Permissions </li>
                </ol>
            </div>
            <h4 class="page-title">Permissions</h4>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="mt-0 header-title">All active permissions</h4>
                <p class="text-muted mb-3"></p>
                <table id="row_callback" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Folder</th>
                            <th>Staff</th>
                            <th>Director</th>
                            <th>Internal Control</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $i = 1;
                        @endphp
                        @foreach($permissions as $perm)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ Utils::getFolder($perm->folder)->name }}</td>
                            <td>{{ ucwords(Utils::getUserByEmail($perm->staff)->name) }} -- {{ ucwords(Utils::getUserByEmail($perm->staff)->role) }}</td>
                            <td>
                                @if($perm->director == "approved")
                                <span class="badge badge-success">Approved</span>
                                @else
                                <span class="badge badge-warning">Pending</span>
                                @endif
                            </td>
                            <td>
                                @if($perm->internal_control == "approved")
                                <span class="badge badge-success">Approved</span>
                                @else
                                <span class="badge badge-warning">Pending</span>
                                @endif
                            </td>
                            <td>{{ date('F d, Y', strtotime($perm->created_at)) }}</td>
                            <td>
                                @if($user->role == 'director' && $perm->director == 'pending')
                                <a class='btn btn-warning btn-small' href='/file-manager-permission-grant/{{ $perm->id }}'><i class='dripicons dripicons-anchor'></i> Grant Permission</a><a class='btn btn-danger btn-small ml-2' href='/file-manager-permission-delete/{{ $perm->id }}'><i class='dripicons dripicons-anchor'></i> Delete Permission</a>
                                @elseif($user->role == 'internal control' && $perm->internal_control == 'pending')
                                <a class='btn btn-warning btn-small' href='/file-manager-permission-grant/{{ $perm->id }}'><i class='dripicons dripicons-anchor'></i> Grant Permission</a><a class='btn btn-danger btn-small ml-2' href='/file-manager-permission-delete/{{ $perm->id }}'><i class='dripicons dripicons-anchor'></i> Delete Permission</a>
                                @else
                                --
                                @endif
                            </td>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('foot')
<script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/jszip.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/buttons.colVis.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/pages/jquery.datatable.init.js') }}"></script>
@endsection