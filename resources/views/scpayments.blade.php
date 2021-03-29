@php
use App\Http\Controllers\Globals as Utils;
use App\Http\Controllers\Loader as CLoader;
$me = Utils::getUser();
@endphp

@extends('layouts.app')

@section('title', __('Swiss Club Payments || Swiss Credit Data Management system'))

@section('swissclub', __('active'))
@section('scpayments', __('active'))

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
                    <li class="breadcrumb-item active">Payments</li>
                </ol>
            </div>
            <h4 class="page-title">Swiss Club Payments</h4>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="mt-0 header-title">All New Loans</h4>
                <p class="text-muted mb-3"></p>
                <table id="row_callback" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Agent</th>
                            <th>Amount</th>
                            <th>View Loan</th>
                            <th>Commission</th>
                            @if($me->role == 'director' || $me->role == 'super admin' || $me->role == 'it')
                            <th>Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $i = 1;
                        @endphp
                        @foreach($posts as $post)
                            @php
                            $agent = Utils::getAgents($post->swiss_club_agent);
                            @endphp
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{!! "<img src='".Gravatar::get($agent->email)."' alt='' class='rounded-circle thumb-sm mr-1'>".ucwords($agent->name ?? '') !!}</td>
                            <td>₦{{ number_format($post->amount_recommended,2) }}</td>
                            <td><a href="/offers/{{ $post->id }}/offer" class="btn btn-danger" target="_blank">View Loan Offer</a></td>
                            <td>₦{{ number_format($post->amount_recommended / 100,2) }}</td>
                            @if($me->role == 'director' || $me->role == 'super admin' || $me->role == 'it')
                            <td><i class="fa fa-checkmark"></i>  <a href="/swissclub/agents/paid/{{ $post->d_form }}" class="btn btn-primary">Mark as paid</a></td>
                            @endif
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