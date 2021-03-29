@php
use App\Http\Controllers\Globals as Utils;
use App\Http\Controllers\Loader as CLoader;
@endphp

@extends('layouts.app')

@section('title', __('Agent Referrals || Swiss Credit Data Management system'))

@section('swissclub', __('active'))
@section('scagents', __('active'))

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
                    <li class="breadcrumb-item active">Agent Referrals</li>
                </ol>
            </div>
            <h4 class="page-title">Agent Referrals</h4>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <form>
            <div class="row">
                <div class="col-md-4 col-sm-12 col-lg-6 col-xl-6">
                    <div class="form-group">
                        <label>Search Query</label>
                        <input type="text" name="search" class="form-control" placeholder="Data you are looking for is not in the table? Search here!" value="{{ $_GET['search'] ?? '' }}">
                    </div>
                </div>
                <div class="col-md-4 col-sm-12 col-lg-3 col-xl-3">
                    <div class="form-group">
                        <label>Number of entries returned</label>
                        <input type="number" name="num" class="form-control" required="" value="{{ $_GET['num'] ?? 50 }}">
                    </div>
                </div>
                <div class="col-md-4 col-sm-12 col-lg-3 col-xl-3">
                    <br>
                    <button class="btn btn-primary btn-block mt-2" type="submit">Filter</button>
                </div>
            </div>
        </form>
        <div class="card">
            <div class="card-body">
                <h4 class="mt-0 header-title">All Referrals</h4>
                <p class="text-muted mb-3"></p>
                <table id="row_callback" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Name</th>
                            <th>Employer</th>
                            <th>DOB</th>
                            <th>Status</th>
                            <th>Date & Time Applied</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $i = 1;
                        @endphp
                        @foreach($posts as $post)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{!! "<img src='".asset($post->passport)."' alt='' class='rounded-circle thumb-sm mr-1'>".ucwords($post->firstname." ".$post->middle_name." ".$post->surname) !!}</td>
                            <td>{{ ucwords($post->current_employer) }}</td>
                            <td>{{ $post->date_of_birth }}</td>
                            <td>{!! CLoader::getStatus($post) !!} </td>
                            <td>{{ date('M d, Y', strtotime($post->created_at))." at ".date('h:i A', strtotime($post->created_at)) }}</td>
                        </tr>
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
<script>
    $(function () {
        $("#row_callback").DataTable();
    });
</script>
@endsection