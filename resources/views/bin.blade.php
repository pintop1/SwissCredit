@php
use App\Http\Controllers\Globals as Utils;
use Illuminate\Support\Collection;
@endphp

@extends('layouts.app')

@section('title', __('Trash || Swiss Credit Data Management system'))

@section('apps', __('active'))
@section('fmrb', __('active'))

@section('head')
@endsection

@section('breadcrumb')
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="float-right">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="/file-manager">File Manager</a></li>
                    <li class="breadcrumb-item active">Recycle Bin</li>
                </ol>
            </div>
            <h4 class="page-title">Recycle Bin</h4>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
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
                <div class="tab-pane fade show active" id="files-projects">
                    <h4 class="header-title mt-0 mb-3">Folder Content</h4>
                    <div class="file-box-content">
                        @foreach($contents as $content)
                            @if(isset($content->file) && $content->file != null)
                            <div class="file-box">
                                <div class="dropdown d-inline-block float-right mt-n2">
                                    <a class="nav-link dropdown-toggle arrow-none" id="drop1" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false"><i class="fas fa-ellipsis-v font-18 text-muted"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="drop1" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(38px, 38px, 0px);">
                                        <a class="dropdown-item" href="{{ asset($content->file) }}" target="_blank">Download</a> 
                                        <a class="dropdown-item" href="/file-manager/preview/{{ $content->id }}">Preview</a>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <a href="/file-manager/preview/{{ $content->id }}" target="_blank">{!! Utils::getFileIcons($content->file) !!}</a>
                                    <h6 class="text-truncate"><a href="/file-manager/preview/{{ $content->id }}" target="_blank">{{ $content->name }}</a></h6>
                                    <small class="text-muted">{{ date('d M, Y', strtotime($content->created_at)) }} / {{ ucwords(Utils::getUserByEmail($content->staff)->name) }}</small>
                                    <hr/>
                                    <small class="text-dark">Deleted By {{ ucwords(Utils::getDeletedBy($content->id)->name ?? '') }} on {{ date('F d, Y', strtotime(Utils::getDeletedDate($content->id)->created_at)) }} around {{ date('h:i A', strtotime(Utils::getDeletedDate($content->id)->created_at)) }}</small>
                                </div>
                            </div>
                            @else
                            <div class="file-box">
                                <div class="text-center">
                                    <a href="/file-manager/view/{{ $content->slug }}"><i class="lni-folder text-gray"></i></a>
                                    <h6 class="text-truncate"><a href="/file-manager/view/{{ $content->slug }}">{{ $content->name }}</a></h6>
                                    <small class="text-muted">{{ date('d M, Y', strtotime($content->created_at)) }} / {{ ucwords(Utils::getUserByEmail($content->staff)->name) }}</small>
                                    <hr/>
                                    <small class="text-dark">Deleted By {{ ucwords(Utils::getDeletedBy($content->id)->name ?? '') }} on {{ date('F d, Y', strtotime(Utils::getDeletedDate($content->id)->created_at)) }} around {{ date('h:i A', strtotime(Utils::getDeletedDate($content->id)->created_at)) }}</small>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12"> 
    {{ $contents->links('vendor.pagination.default') }}
    </div>
</div>
@if($links > 0)
<div class="row mt-5">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="mt-0 header-title">Links</h4>
                <p class="text-muted mb-3"></p>
                <table id="row_callback" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>TITLE</th>
                            <th>LINK</th>
                            <th>STAFF</th>
                            <th>DELETED BY</th>
                            <th>DELETED AT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $i = 1;
                        @endphp
                        @foreach($posts as $post)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $post->title }}</td>
                            <td>{!! '<a href="'.$post->link.'" target="_blank">Click Here</a>' !!}</td>
                            <td>{{ ucwords(Utils::getUserByEmail($post->staff)->name ?? '-') }}</td>
                            <td>{{ ucwords(Utils::getDeletedBy($post->id)->name ?? '-') }}</td>
                            <td>{{ 'on '.date('F d, Y', strtotime(Utils::getDeletedDate($post->id)->created_at)).' around '.date('h:i A', strtotime(Utils::getDeletedDate($post->id)->created_at)) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>S/N</td>
                            <td>TITLE</td>
                            <td>LINK</td>
                            <td>STAFF</td>
                            <td>DELETED BY</td>
                            <td>DELETED AT</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endif
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