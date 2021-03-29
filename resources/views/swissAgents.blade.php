@php
use App\Http\Controllers\Globals as Utils;
use App\Http\Controllers\Loader as CLoader;
@endphp

@extends('layouts.app')

@section('title', __('Swiss Club Agents || Swiss Credit Data Management system'))

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
                    <li class="breadcrumb-item active">Agents</li>
                </ol>
            </div>
            <h4 class="page-title">Swiss Club Agents</h4>
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
                        <label>Search</label>
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
                <h4 class="mt-0 header-title">All New Loans</h4>
                <p class="text-muted mb-3"></p>
                <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Name</th>
                            <th>Referral Code</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Total Commission</th>
                            <th>Commision Paid</th>
                            <th>Date Joined</th>
                            <th>Applications</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $i = 1;
                        @endphp
                        @foreach($posts as $post)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>
                                @if($post->passport)
                                {!! "<img src='https://swissclub.swisscredit.ng/".$post->passport."' alt='' class='rounded-circle thumb-sm mr-1'>".ucwords($post->name) !!}
                                @else
                                {!! "<img src='".Gravatar::get(Utils::valid_email($post->email) ? $post->email : 'example@example.com')."' alt='' class='rounded-circle thumb-sm mr-1'>".ucwords($post->name) !!}
                                @endif
                                </td>
                            <td>{{ $post->referral_code }}</td>
                            <td>{{ $post->phone }}</td>
                            <td>{{ $post->email }}</td>
                            <td>₦{{ number_format(Utils::getCommission($post->email),2) }}</td>
                            <td>₦{{ number_format(Utils::getCommissionPaid($post->email),2) }}</td>
                            <td>
                                @if($post->created_at)
                                {{ date('F d, Y h:i a', strtotime($post->created_at)) }}
                                @elseif($post->updated_at)
                                {{ date('F d, Y h:i a', strtotime($post->updated_at)) }}
                                @endif
                            </td>
                            <td><a href="/swissclub/agents/view/{{ $post->referral_code }}" class="btn btn-primary" target="_blank">View Applications</a></td>
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
        $("#datatable").DataTable(), $(document).ready(function() {
            $("#datatable2").DataTable()
        }), $("#datatable-buttons").DataTable({
            lengthChange: !1,
            buttons: ["copy", "excel", "pdf", "colvis"]
        }).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"), $("#row_callback").DataTable({
            createdRow: function(t, a, e) {
                15e4 < 1 * a[5].replace(/[\$,]/g, "") && $("td", t).eq(5).addClass("highlight")
            }
        });
    });
    
</script>
@endsection