@php
use App\Http\Controllers\Globals as Utils;
use App\Http\Controllers\Loader as CLoader;
@endphp

@extends('layouts.app')

@section('title', __('Offers || Swiss Credit Data Management system'))

@section('data', __('active'))
@section('offers', __('active'))

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
                    <li class="breadcrumb-item active">Offers</li>
                </ol>
            </div>
            <h4 class="page-title">Offers</h4>
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
                <h4 class="mt-0 header-title">All Offers</h4>
                <p class="text-muted mb-3"></p>
                <table id="row_callback" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Ref.No</th>
                            <th>Name</th>
                            <th>Amount</th>
                            <th>Created By</th>
                            <th>Last Edited By</th>
                            <th>Status</th>
                            <th>Date Created</th>
                            <th>Date Last Edited</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $i = 1;
                        @endphp
                        @foreach($posts as $post)
                        @php
                        $form = Utils::getForm($post->d_form);
                        @endphp
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ Utils::getSerial($form) }}</td>
                            <td>{{ ucwords($form->firstname.' '.$form->middle_name.' '.$form->surname) }}</td>
                            <td>{{ "N".number_format($post->amount_recommended,2) }}</td>
                            <td>{{ ucwords(Utils::getUserByEmail($post->staff)->name ?? '-') }}</td>
                            <td>{{ ucwords(Utils::getUserByEmail($post->last_edited)->name ?? '-') }}</td>
                            <td>{!! CLoader::getOfferStatus($post) !!}</td>
                            <td>{{ date('M d, Y H:i A', strtotime($post->created_at)) }}</td>
                            <td>{{ date('M d, Y H:i A', strtotime($post->updated_at)) }}</td>
                            <td>{!! CLoader::getOfferAction($post) !!}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>S/N</td>
                            <td>Ref.No</td>
                            <td>Name</td>
                            <td>Amount</td>
                            <td>Created By</td>
                            <td>Last Edited By</td>
                            <td>Status</td>
                            <td>Date Created</td>
                            <td>Date Last Edited</td>
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
        $('#row_callback').on('click', 'a.performing', function () {
          var url = ($(this).attr('data-target'));
          $(".loading").show();
          $.get(url, function(data){
            $(".loading").hide();
            $(".return").html(data);
          });
        });
    });
</script>
@endsection