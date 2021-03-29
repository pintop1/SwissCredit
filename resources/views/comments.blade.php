@php
use App\Http\Controllers\Globals as Utils;
use Illuminate\Support\Collection;
@endphp

@extends('layouts.app')

@section('title', __('Comments || Swiss Credit Data Management system'))

@section('data', __('active'))
@section('customers', __('active'))

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
                    <li class="breadcrumb-item active">Comments</li>
                </ol>
            </div>
            <h4 class="page-title">File Comments</h4>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row">
	<div class="col-12">
		<div class="chat-box-left">
			
		</div>
		<!--end chat-box-left -->
		<div class="chat-box-right">
			<div class="chat-header">
				<a href="" class="media">
					<div class="media-left">
					    <img src="{{ asset($form->passport) }}" alt="user" class="rounded-circle thumb-md">
				    </div>
					<div class="media-body">
						<div>
							<h6 class="mb-1 mt-0">{{ ucwords($form->firstname.' '.$form->middle_name.' '.$form->surname) }}</h6>
						</div>
					</div>
				</a>
			</div>
			<!-- end chat-header -->
			<div class="chat-body">
				<div class="chat-detail slimscroll">
				    @foreach($comments as $key => $comment)
				        @php
				        $staff = Utils::getUserByEmail($comment->staff);
				        @endphp
				        @if($staff->email != $user->email)
					<div class="media">
						<div class="media-img">
						    @if($staff->passport != null)
						    <img src="{{ asset($staff->passport) }}" alt="user" class="rounded-circle thumb-md">
						    @else
						    <img src="{{ Gravatar::get($staff->email) }}" alt="user" class="rounded-circle thumb-md">
						    @endif
						    <span><small class="badge badge-danger">{{ ucwords($staff->name) }}</small></span>
					    </div>
						<div class="media-body">
							<div class="chat-msg">
								<p>{{ $comment->comments }}
								<br><br>
								<span class="text-muted float-right">{{ date('M d, Y h:m a', strtotime($comment->created_at)) }}</span></p>
							</div>
						</div>
					</div>
					    @else
					<div class="media">
						<div class="media-body reverse">
							<div class="chat-msg">
								<p>{{ $comment->comments }}<br><br>
								<span class="text-muted float-right">{{ date('M d, Y h:m a', strtotime($comment->created_at)) }}</span></p>
							</div>
						</div>
						<div class="media-img">
						    <span><small class="badge badge-danger">{{ ucwords($staff->name) }}</small></span>
						    @if($staff->passport != null)
						    <img src="{{ asset($staff->passport) }}" alt="user" class="rounded-circle thumb-md">
						    @else
						    <img src="{{ Gravatar::get($staff->email) }}" alt="user" class="rounded-circle thumb-md">
						    @endif
					    </div>
					</div>
				        @endif
			        @endforeach
				</div>
			</div>
			<div class="chat-footer">
			    <form action="{{ route('comment') }}" method="post" id="myForm">
		            @csrf
    				<div class="row">
    					<div class="col-12 col-md-9"><span class="chat-admin">
    					    @if($user->passport != null)
    					    <img src="{{ asset($user->passport) }}" alt="user" class="rounded-circle thumb-sm">
    					    @else
    					    <img src="{{ Gravatar::get($user->email) }}" alt="user" class="rounded-circle thumb-sm">
    					    @endif
    					    </span>
    				        <input type="hidden" value="{{ $form->id }}" name="id">
    				        <input type="text" class="form-control" placeholder="Type something here..." name="msg" required>
    				    </div>
    					<div class="col-3 text-right">
    						<div class="d-none d-sm-inline-block chat-features"><a href="#" onclick='document.getElementById("myForm").submit();'><i class="fas fa-paper-plane"></i></a></div>
    					</div>
    				</div>
				</form>
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