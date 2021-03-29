@php
use App\Http\Controllers\Globals as Utils;
@endphp
<!DOCTYPE html>
<html>
	<head>
		<title>Offer Letter</title>
		<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/css/jquery-ui.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/metisMenu.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/fonts/dripicons/webfont/webfont.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/fonts/icons/LineIcons.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/fonts/fontawesome/css/all.css') }}" rel="stylesheet" type="text/css">
        <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
        <!--[if IE]> 
		<script type="text/javascript" src="{{ asset('assets/plugins/signature/js/excanvas.js') }}"></script> 
		<![endif]-->
		<script type="text/javascript" src="{{ asset('assets/plugins/signature/js/jquery.ui.touch-punch.min.js') }}"></script> 
		<link type="text/css" href="{{ asset('assets/plugins/signature/css/jquery.signature.css') }}" rel="stylesheet"> 
		<script type="text/javascript" src="{{ asset('assets/plugins/signature/js/jquery.signature.js') }}"></script>
		<style>
		.kbw-signature { width: 400px; height: 200px; }
		</style>
		<script type="text/javascript">
			$(function() {
			    //alert('done');
				var sig = $('#sig').signature();
				var sig2 = $('#sig2').signature();
				
				$('#clear').click(function() {
					sig.signature('clear');
				});
				$('#clear2').click(function() {
					sig2.signature('clear');
				});
				$('#svg').click(function() {
					alert(sig.signature('toSVG'));
				});
				$('#dSub').click(function() {
					$('#dsvgs').html(sig.signature('toSVG')+sig2.signature('toSVG'));
					var svg = document.getElementsByTagName("svg")[0];
					var bbox = svg.getBBox();
					var viewBox = [bbox.x, bbox.y, bbox.width, bbox.height].join(" ");
					svg.setAttribute("viewBox", viewBox);
					// second svg
					var svg2 = document.getElementsByTagName("svg")[1];
					var bbox2 = svg2.getBBox();
					var viewBox2 = [bbox2.x, bbox2.y, bbox2.width, bbox2.height].join(" ");
					svg2.setAttribute("viewBox", viewBox2);

					$('input[name=signature]').val(svg.outerHTML);
					$('input[name=signature2]').val(svg2.outerHTML);
					$('#d_form').submit();
				});
			});
		</script>
	</head>
	<body>
		<div class="container">

			<div class="row">
				<div class="col-lg-12 my-5">
					<a href="https://swisscredit.ng" target="_blank"><img src="{{ asset('assets/images/logo-transparent.png') }}" class="float-right" width="160"></a>
				</div>
			    <div class="col-lg-12">
			    	@if(session()->has('message'))
		                {!! session()->get('message') !!}
		            @endif
			        <div class="card">
			        	<form action="{{ route('processSignature') }}" method="post" id="d_form">
			        		@csrf
			        		<input type="hidden" name="id" value="{{ $offer->id }}">
				            <div class="card-body">
				            	<div class="row">
				            		<div class="col-12">
				            			<div class="card">
				            				<div class="card-body">
				            					<div class="jumbotron mb-0 bg-light">
				            						<h3 class="display-6">Hello {{ ucwords($customer->firstname.' '.$customer->middle_name.' '.$customer->surname) }}</h3>
				            						<p class="lead">Please click the button below to preview, download and confirm your offer letter.</p>
				            						<hr class="my-4">
				            						<p>
				            							After previewing your offer, please append your signature below and click the submit button so as to proceed with your loan processing.
				            						</p>
				            						<p>
				            							Please ignore if you do not accept the offer given to you and contact your account officer for guidance.
				            						</p>
				            						<a class="btn btn-gradient-primary btn-lg" target="_blank" href="/customer/offer/view/{{ Utils::encrypt_decrypt('encrypt', $offer->id) }}" role="button">Preview Offer</a>
				            					</div>
				            				</div>
				            			</div>
				            		</div>
				            		<div class="col-lg-6 text-center">
					            		<h4 class="card-title">Signature</h4>
								    	<div id="sig"></div>
								    	<input type="hidden" name="signature" required="">
										<p style="clear: both;">
											<button type="button" class="btn btn-gradient-warning btn-sm" id="clear">Clear</button> 
										</p>
										<label><input type="radio" name="used" class="form-control" value="1" checked required>This is more closer to my signature!</label>
								    </div>
								    <div class="col-lg-6 text-center">
					            		<h4 class="card-title">Confirm Signature</h4>
								    	<div id="sig2"></div>
								    	<input type="hidden" name="signature2" required="">
										<p style="clear: both;">
											<button type="button" class="btn btn-gradient-warning btn-sm" id="clear2">Clear</button> 
										</p>
										<label><input type="radio" name="used" class="form-control" value="2" required>This is more closer to my signature!</label>
								    </div>
								    
								    <div class="col-lg-12 mt-3">
								    	<button id="dSub" type="button" class="btn btn-lg btn-block btn-purple">SUBMIT</button>
								    </div>
								    <div id="dsvgs"></div>
				            	</div>
				            </div>
				        </form>
			        </div>
			    </div>
			    
			</div>
		</div>

        <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
	</body>
</html>