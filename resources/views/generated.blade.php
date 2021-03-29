@php
use App\Http\Controllers\Globals as Utils;
use Illuminate\Support\Collection;
@endphp

@extends('layouts.app')

@section('title', __('Generated Forms || Swiss Credit Data Management system'))

@section('data', __('active'))
@section('generated', __('active'))

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
                    <li class="breadcrumb-item active">Generated Forms</li>
                </ol>
            </div>
            <h4 class="page-title">Generated Forms</h4>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="mt-0 header-title">All Forms (Loan Forms)</h4>
                <p class="text-muted mb-3"></p>
                <table id="row_callback" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Link</th>
                            <th>To</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $i = 1;
                        @endphp
                        @foreach($generates as $generate)
                        @php
                        $form = Utils::getForm($generate->form_filled);
                        @endphp
                        @if($generate->status == 'used' && $form->status != 'approved')
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ env('APP_URL') }}/myForm/{{ $generate->code }}</td>
                            <td>{{ strtolower($generate->to_email) }}</td>
                            <td>
                                @if($generate->status == 'pending')
                                    <span class="badge badge-md badge-boxed badge-soft-warning">Not Filled</span>
                                @elseif($generate->status == 'used')
                                    <span class="badge badge-md badge-boxed badge-soft-success">Filled</span>
                                @elseif($generate->status == 'expired')
                                    <span class="badge badge-md badge-boxed badge-soft-danger">Expired</span>
                                @endif
                            </td>
                            <td>
                                @if($generate->status == 'used')
                                    <a class="text-secondary p-2" href="/customers/{{ $generate->form_filled }}/edit"><i class="lni-pencil"></i></a>
                                    <a class="text-danger p-2" target="_blank" href="/customers/{{ $generate->form_filled }}/preview"><i class="fas fa-file-pdf"></i></a>
                                @endif
                            </td>
                        </tr>
                        @endif
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