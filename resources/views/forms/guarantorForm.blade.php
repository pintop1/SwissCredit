<!DOCTYPE html>
<html>
	<head>
		<title>Guarantor`s Form</title>
		<link href="{{ asset('assets/plugins/dropify/css/dropify.min.css') }}" rel="stylesheet">
		<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
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
			        <div class="card">
			            <div class="card-body">
			                <form action="{{ route('fillFormGuarantor') }}" method="post" enctype="multipart/form-data" id="d_form">
			                    @csrf
			                    <div class="row">
			                        <div class="col-lg-12">
			                            <h4 class="title bg-gray text-white text-center py-2">GUARANTOR DETAILS</h4>
			                            <div class="row">
			                            	<div class="form-group col-lg-12">
			                            		<br><br>
			                            		<h3>APPLICANT`S NAME: <span class="text-danger">{{ strtoupper($dform->firstname.' '.$dform->middle_name.' '.$dform->surname) }}</span></h3>
			                            			<input type="hidden" name="guarantor" value="{{ $dguar->id }}">
			                            	</div>
			                            	<div class="form-group col-lg-12">
				                                <label>Passport</label> 
				                                <input type="file" name="passport" id="input-file-now" class="dropify" accept="image/*" required="">
				                            </div>
				                            <div class="form-group col-lg-3">
				                                <label class="d-block">First Name</label> 
				                                <input type="text" name="fname" class="form-control" required="">
				                            </div>
				                            <div class="form-group col-lg-3">
				                                <label class="d-block">Middle Name</label> 
				                                <input type="text" name="mname" class="form-control">
				                            </div>
				                            <div class="form-group col-lg-3">
				                                <label class="d-block">Surname</label> 
				                                <input type="text" name="sname" class="form-control" required="">
				                            </div>
				                            <div class="form-group col-lg-3">
				                                <label class="d-block">Title</label> 
				                                <input type="text" name="title" class="form-control" required="">
				                            </div>
				                            <div class="form-group col-lg-6">
				                            	<label>Marital Status: </label>
				                                <label><input type="radio" name="mstatus" value="Single" required=""> <span class="ml-2 text-purple">Single</span></label>
				                                <label class="ml-4"><input type="radio" name="mstatus" value="Married" required=""> <span class="ml-2 text-purple">Married</span></label>
				                                <label class="ml-4"><input type="radio" name="mstatus" value="Others" required=""> <span class="ml-2 text-purple">Others</span></label>
				                            </div>
				                            <div class="form-group col-lg-6 text-right">
				                            	<label>Sex: </label>
				                                <label><input type="radio" name="gender" value="Male" required=""> <span class="ml-2 text-purple">Male</span></label>
				                                <label class="ml-4"><input type="radio" name="gender" value="Female" required=""> <span class="ml-2 text-purple">Female</span></label>
				                            </div>
				                            <div class="form-group col-lg-6">
				                                <label class="d-block">Date Of Birth</label> 
				                                <input type="date" name="dob" class="form-control" required="">
				                            </div>
				                            <div class="form-group col-lg-6">
				                                <label class="d-block">Nationality</label> 
				                                <input type="text" name="nation" class="form-control" required="">
				                            </div>
				                            <div class="form-group col-lg-12">
				                                <label class="d-block">Employer Name</label> 
				                                <input type="text" name="employer" class="form-control" required="">
				                            </div>
				                            <div class="form-group col-lg-12">
				                                <label class="d-block">Office Address</label> 
				                                <input type="text" name="office_addr" class="form-control" required="">
				                            </div>
				                            <div class="form-group col-lg-12">
				                            	<label>Are you currently a guarantor for a SWISS CREDIT LIMITED Client?</label>
				                                <label><input type="radio" name="already_guarantor" value="Yes" required=""> <span class="ml-2 text-purple">Yes</span></label>
				                                <label class="ml-4"><input type="radio" name="already_guarantor" value="No" required=""> <span class="ml-2 text-purple">No</span></label>
				                            </div>
				                            <div class="form-group col-lg-12">
				                                <label class="d-block">If YES, state the name of the client and the Amount Guaranteed</label> 
				                                <textarea name="yes_name" class="form-control"></textarea>
				                            </div>
				                            <div class="form-group col-lg-12">
				                                <label class="d-block">Residential Address</label> 
				                                <input type="text" name="residential_addr" class="form-control" required="">
				                            </div>
				                            <div class="form-group col-lg-4">
				                                <label class="d-block">Office</label> 
				                                <input type="text" name="office_no" class="form-control">
				                            </div>
				                            <div class="form-group col-lg-4">
				                                <label class="d-block">Mobile</label> 
				                                <input type="text" name="mobile_no" class="form-control" required="">
				                            </div>
				                            <div class="form-group col-lg-4">
				                                <label class="d-block">Home</label> 
				                                <input type="text" name="home_no" class="form-control">
				                            </div>
				                            <div class="form-group col-lg-12">
				                                <label class="d-block">Personal Email</label> 
				                                <input type="email" name="personal_email" class="form-control" required="">
				                            </div>
				                            <div class="form-group col-lg-12">
				                                <label class="d-block">Official Email</label> 
				                                <input type="email" name="official_email" class="form-control">
				                            </div>
				                            <div class="form-group col-lg-12">
				                                <label class="d-block">Relationship With Applicant</label> 
				                                <input type="text" name="relationship" class="form-control" required="">
				                            </div>
				                            <div class="form-group col-lg-6">
				                                <label class="d-block">Position Held / Designation</label> 
				                                <input type="text" name="position" class="form-control" required="">
				                            </div>
				                            <div class="form-group col-lg-6">
				                                <label class="d-block">Branch Telephone</label> 
				                                <input type="text" name="branch_telephone" class="form-control">
				                            </div>
				                            <div class="form-group col-lg-12">
				                            	<label class="d-block">Annual Income (Please tick as appropriate)</label>
				                                <label><input type="radio" name="income" value="N500,000.00 - N1,000,000.00" required=""> <span class="ml-2 text-purple">N500,000.00 - N1,000,000.00</span></label>
				                                <label class="ml-4"><input type="radio" name="income" value="N1,000,000.00 - N5,000,000.00" required=""> <span class="ml-2 text-purple">N1,000,000.00 - N5,000,000.00</span></label>
				                                <label class="ml-4"><input type="radio" name="income" value="N5,000,000.00 - N10,000,000.00" required=""> <span class="ml-2 text-purple">N5,000,000.00 - N10,000,000.00</span></label>
				                                <label class="ml-4"><input type="radio" name="income" value="N10,000,000.00 - Above" required=""> <span class="ml-2 text-purple">N10,000,000.00 - Above</span></label>
				                            </div>
				                            <div class="form-group col-lg-12">
				                            	<div class="d-inline-block">
					                            	<label>Did you provide cheque? </label>
					                                <label><input type="radio" name="cheque" value="Yes" required=""> <span class="ml-2 text-purple">Yes</span></label>
					                                <label class="ml-4"><input type="radio" name="cheque" value="No" required=""> <span class="ml-2 text-purple">No</span></label>
					                            </div>
				                                <div class="d-inline-block ml-4">
					                                <label class="mr-3">If yes, how many?</label> 
					                                <input type="number" name="cheque_counts" class="form-control-inline">
					                            </div>
				                            </div>
				                            <div class="form-group col-lg-12">
				                                <label>DETAILS OF OUTSTANDING LOAN IF ANY</label> 
				                            </div>
				                            <div class="form-group col-lg-6">
				                                <label class="d-block">With SWISS CREDIT</label> 
				                                <textarea name="with_swiss_credit" class="form-control"></textarea>
				                            </div>
				                            <div class="form-group col-lg-6">
				                                <label class="d-block">With OTHER BANKS</label> 
				                                <textarea name="with_other_banks" class="form-control"></textarea>
				                            </div>
				                            <div class="form-group col-lg-12 border border-dark">
				                                <label class="d-block">DECLARATION</label>
				                                <div>I (WITH THE NAME ABOVE) undertake to REPAY to SWISS CREDITLIMITED the value of the loan/facilityor any indebtedness and other associated cost of debt recovery/charges on behalf of the above mentioned applicant if he fails to repay the loan/facility. I also attest to the fact that I am duly about this credit transaction between SWISS CREDIT LIMITED and the applicant.<br><br>I have read and understood the information above and therefore sign below.<br>Attached to this form is a copy of my valid employer identity card and post dated cheque.</div> 
				                                <div class="row">
				                                	<div class="col-lg-6 text-center">
									            		<h4 class="card-title">Signature</h4>
												    	<div id="sig"></div>
												    	<input type="hidden" name="signature" required="">
														<p style="clear: both;">
															<button type="button" class="btn btn-warning btn-sm" id="clear">Clear</button> 
														</p>
														<label><input type="radio" name="used" class="form-control" value="1" checked required>This is more closer to my signature!</label>
												    </div>
												    <div class="col-lg-6 text-center">
									            		<h4 class="card-title">Confirm Signature</h4>
												    	<div id="sig2"></div>
												    	<input type="hidden" name="signature2" required="">
														<p style="clear: both;">
															<button type="button" class="btn btn-warning btn-sm" id="clear2">Clear</button> 
														</p>
														<label><input type="radio" name="used" class="form-control" value="2" required>This is more closer to my signature!</label>
												    </div>
				                                </div>
				                            </div>
			                            </div>
			                        </div>
			                    </div>
			                    <button id="dSub" class="btn btn-purple btn-block" type="button">UPLOAD</button>
			                    <div id="dsvgs"></div>
			                </form>
			            </div>
			        </div>
			    </div>
			</div>
		</div>

        <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
		<script src="{{ asset('assets/plugins/dropify/js/dropify.min.js') }}"></script>
		<script src="{{ asset('assets/pages/jquery.form-upload.init.js') }}"></script>
	</body>
</html>