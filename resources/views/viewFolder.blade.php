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
            <h4 class="page-title">{{ $folder->name }}</h4>
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
                    @if($user->role == 'credit risk')
                    <a href="/file-manager/add/{{ $folder->slug }}" class="btn btn-primary btn-sm add-file mb-3">
                        <i class="lni-folder mr-2"></i>Create Folder
                    </a> 
                    @endif
                    @if($user->role == 'credit risk' || $user->role == 'underwriter' || $user->role == 'risk')
                    <a href="/file-manager/add-files/{{ $folder->slug }}" class="btn btn-primary btn-sm add-file mb-3">
                        <i class="lni-upload mr-2"></i>Add Files
                    </a> 
                    <a href="/file-manager/add-links/{{ $folder->slug }}" class="btn btn-primary btn-sm add-file mb-3">
                        <i class="lni-link mr-2"></i>Add Links
                    </a> 
                    @endif
                    @if($user->role == 'credit risk' || $user->role == 'underwriter' || $user->role == 'risk' || $user->role == 'monitoring and compliance')
                        @if($pending != null)
                        <a class="btn btn-warning btn-sm add-file ml-3 mb-3 text-white">
                            <i class="lni-user mr-2"></i>You have an unapproved permission 
                        </a> 
                        @elseif($permission != null)
                        <a class="btn btn-success btn-sm add-file ml-3 mb-3 text-white">
                            <i class="lni-user mr-2"></i>You currently have permission to this folder
                        </a> 
                        @else
                        <a class="btn btn-primary btn-sm add-file ml-3 mb-3 text-white" id="reqPerm">
                            <i class="lni-user mr-2"></i>Request Permission
                        </a> 
                        @endif
                    @endif
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
                                        @if($user->role == 'monitoring and compliance')
                                        <a class="dropdown-item" href="/file-manager/delete/file/{{ $content->id }}" onclick="return confirm('Are you sure you want to delete this file?');">Delete</a>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-center">
                                    <a href="/file-manager/preview/{{ $content->id }}" target="_blank">{!! Utils::getFileIcons($content->file) !!}</a>
                                    <h6 class="text-truncate"><a href="/file-manager/preview/{{ $content->id }}" target="_blank">{{ $content->name }}</a></h6>
                                    <small class="text-muted">{{ date('d M, Y', strtotime($content->created_at)) }} / {{ ucwords(Utils::getUserByEmail($content->staff)->name) }}</small>
                                </div>
                            </div>
                            @else
                            <div class="file-box">
                                @if($user->role == 'monitoring and compliance')
                                <a href="/file-manager/delete/folder/{{ $content->id }}" onclick="return confirm('Are you sure you want to delete this file?');" class="download-icon-link"><i class="dripicons-trash file-download-icon"></i></a>
                                @endif
                                <div class="text-center">
                                    <a href="/file-manager/view/{{ $content->slug }}"><i class="lni-folder text-gray"></i></a>
                                    <h6 class="text-truncate"><a href="/file-manager/view/{{ $content->slug }}">{{ $content->name }}</a></h6>
                                    <small class="text-muted">{{ date('d M, Y', strtotime($content->created_at)) }} / {{ ucwords(Utils::getUserByEmail($content->staff)->name) }}</small>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 mt-4"> 
    {{ $contents->links('vendor.pagination.default') }}
    </div>
</div>
@if(count($links) > 0)
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
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $i = 1;
                        @endphp
                        @foreach($links as $link)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $link->title }}</td>
                            <td><a href="{{ $link->link }}" target="_blank">Click Here</a></td>
                            <td>{{ ucwords(Utils::getUserByEmail($link->staff)->name) }}</td>
                            <td><a class='btn btn-danger btn-small ml-2' href='/file-manager/delete/link/{{ $link->id }}'  onclick="return confirm('Are you sure you want to delete this link?');">Delete Link</a></td>
                        </tr>
                        @endforeach
                    </tbody>
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
<script src="{{ asset('assets/pages/jquery.datatable.init.js') }}"></script>
<script>
    $(function () {
        $("#reqPerm").click(function(){
            swal.fire({
                title: 'Are you sure?',
                text: 'This request will be forwarded to the Director and Internal Control for approval',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, proceed!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    var page = '{{ url("/file-manager/request-permission") }}';
                    var page2 = '{{ $folder->slug }}';
                    window.location = page+'/'+page2;
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swal.fire('Cancelled', 'Your action has been cancelled', 'error');
                }
            });
        });
    });
</script>
@endsection