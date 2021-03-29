@php
use App\Http\Controllers\Globals as Utils;
@endphp

@extends('layouts.app')

@section('title', __('Generate Offer Letter || Swiss Credit Data Management system'))

@section('form', __('active'))
@section('goffer', __('active'))

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
                    <li class="breadcrumb-item active">Generate Offer</li>
                </ol>
            </div>
            <h4 class="page-title">Generate Offer</h4>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="mt-0 header-title">Generate Offer</h4>
                <p class="text-muted mb-3"></p>
                <table id="row_callback" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Ref.No</th>
                            <th>Name</th>
                            <th>Employer</th>
                            <th>DOB</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $i = 1;
                        @endphp
                        @foreach($posts as $post)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ Utils::getSerial($post) }}</td>
                            <td>{!! "<img src='".asset($post->passport)."' alt='' class='rounded-circle thumb-sm mr-1'>".ucwords($post->firstname." ".$post->middle_name." ".$post->surname) !!}</td>
                            <td>{{ ucwords($post->current_employer ?? '-') }}</td>
                            <td>{{ $post->date_of_birth }}</td>
                            <td>{!! '<div class="d-inline-block border-right border-gray p-2"><a class="btn btn-danger btn-small" href="/customers/'.$post->id.'/preview" target="_blank"><i class="fas fa-file-pdf"></i> Preview</a></div><div class="d-inline-block p-2"><a class="btn btn-primary btn-small" href="/offers/add/'.$post->id.'"><i class="dripicons dripicons-document-new"></i> Generate Offer</a></div>' !!}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>S/N</td>
                            <td>Ref.No</td>
                            <td>Name</td>
                            <td>Employer</td>
                            <td>DOB</td>
                            <td>Action</td>
                        </tr>
                    </tfoot>
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