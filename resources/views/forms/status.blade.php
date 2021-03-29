<!DOCTYPE html>
<html>
	<head>
		<title>Congratulations!!!</title>
		<link href="{{ asset('assets/plugins/dropify/css/dropify.min.css') }}" rel="stylesheet">
		<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/fonts/dripicons/webfont/webfont.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/fonts/icons/LineIcons.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/fonts/fontawesome/css/all.css') }}" rel="stylesheet" type="text/css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="col-lg-12 my-5">
					<a href="https://swisscredit.ng" target="_blank"><img src="{{ asset('assets/images/logo-transparent.png') }}" class="float-right" width="160"></a>
				</div>
			    <div class="col-lg-12">
			    	@if(isset($approved))
			    		@if(isset($guarantor))
			    		<div class="row"><div class="col-12"><div class="card"><div class="card-body"><div class="jumbotron mb-0 bg-light"><h1 class="display-4">Congratulations!!!</h1><p class="lead">Your form has been submited to SWISS CREDIT LIMITED for review and processing.</p><hr class="my-4"></div></div></div></div></div>
			    		@else
			    		<div class="row"><div class="col-12"><div class="card"><div class="card-body"><div class="jumbotron mb-0 bg-light"><h1 class="display-4">Congratulations!!!</h1><p class="lead">You have approved your loan offer. We will carry on with your loan processing.</p><hr class="my-4"></div></div></div></div></div>
			    		@endif
			    	@else
			    	<div class="row"><div class="col-12"><div class="card"><div class="card-body"><div class="jumbotron mb-0 bg-light"><h1 class="display-4">Congratulations!!!</h1><p class="lead">Your application has been submitted successfully.<br>Your application will be processed shortly.</p><hr class="my-4"></div></div></div></div></div>
			    	@endif
			    </div>
			</div>
		</div>

		<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/js/jquery-ui.min.js') }}"></script>
        <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('assets/js/metismenu.min.js') }}"></script>
        <script src="{{ asset('assets/js/waves.js') }}"></script>
        <script src="{{ asset('assets/js/feather.min.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.slimscroll.min.js') }}"></script>
		<script src="{{ asset('assets/plugins/dropify/js/dropify.min.js') }}"></script>
		<script src="{{ asset('assets/pages/jquery.form-upload.init.js') }}"></script>
	</body>
</html>