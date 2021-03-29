@extends('layouts.form')
@section('content')
<div class="steps-area steps-area-fixed">
	<div class="image-holder">
		<img src="{{ asset('multilevel/img/side-img.jpg') }}" alt="">
	</div>
</div>
<form class="multisteps-form__form" action="#" id="wizard" method="POST">
	<div class="form-area position-relative">
		<div class="multisteps-form__panel js-active" data-animation="slideHorz">
			<form action="{{ route('sub1') }}" method="post" id="the_form">
				@csrf
				<div class="wizard-forms">
					<div class="inner pb-100 clearfix">
						<div class="form-content pera-content">
							<div class="step-inner-content">
								<h2>Let’s get you started!!!</h2>
								<p style="visibility: hidden">Let us know what you want to request for from us. We provide 2 of the following on Swiss Salary loans!</p>
								<div class="step-box">
									<div class="alert alert-danger text-sm" id="errorm_"></div>
									<div class="row">
										<div class="col-md-4">
											<label class="step-box-content bg-white text-center relative-position">
											<span class="step-box-icon">
											<img src="{{ asset('multilevel/img/d1.png') }}" alt="">
											</span>
											<span class="step-box-text">
											New Loan Request
											</span>
											<span class="service-check-option">
											<span><input type="radio" name="service_name" value="new loan"></span>
											</span>
											</label>
										</div>
										<div class="col-md-4">
											<label class="step-box-content bg-white text-center relative-position">
											<span class="step-box-icon">
											<img src="{{ asset('multilevel/img/d2.png') }}" alt="">
											</span>
											<span class="step-box-text">
											Top-up/Renewal Request
											</span>
											<span class="service-check-option">
											<span><input type="radio" name="service_name" value="top up / renewal"></span>
											</span>
											</label>
										</div>
										<div class="col-md-4">
											<label class="step-box-content bg-white text-center relative-position">
											<span class="step-box-icon">
											<img src="{{ asset('multilevel/img/d1.png') }}" alt="">
											</span>
											<span class="step-box-text">
											NYSC Loan Request
											</span>
											<span class="service-check-option">
											<span><input type="radio" name="service_name" value="nysc loan request"></span>
											</span>
											</label>
										</div>
									</div>
									<div class="form-inner-area" id="dbvn">
										<h5>Note: For Top up/Renewals, kindly fill in your details to proceed with your application</h5>
										<input type="number" min="11" max="11" name="bvn" class="form-control" placeholder="Bank Verification Number">
										<div class="text-danger text-xl" id="error_"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="actions">
						<ul class="dmain">
							<li class="disable" aria-disabled="true"><span class="js-btn-next" title="NEXT">Backward <i class="fa fa-arrow-right"></i></span></li>
							<li><span class="js-btn-next" title="NEXT">NEXT <i class="fa fa-arrow-right"></i></span></li>
						</ul>
						<ul class="dloader">
							<li><span class="js-btn-next" title="NEXT"><img src="{{ asset('loader.gif') }}" width="90"></span></li>
						</ul>
					</div>
				</div>
			</form>
		</div>
	</div>
</form>
@endsection
@section('foot')
<script type="text/javascript">
	$(function(){
		var selection = $('input[name="service_name"]:checked').val();
		$(".dloader").hide();
		$(".dmain").show();
		$('#dbvn').hide();
		$('#errorm_').hide();
		$('input[name="service_name"]').on('change', function(){
			var nselection = $('input[name="service_name"]:checked').val();
			if(nselection == 'top up / renewal'){
				swal.fire({
				    title: 'Are you sure?',
				    html:
	                  '<b>Renewal: </b> Is your Loan fully paid and you want to request for a renewal?<br>' +
	                  '<b>Top-up: </b>Is your Loan still running and you require additional funds?',
				    type: 'warning',
				}).then((result) => {
					$(".dloader").hide();
					$('#dbvn').show();
					$('#errorm_').hide();
					$(".dmain").show();
					$(".dmain").click(function(){ 
						var bvn = $('input[name="bvn"]').val();
						if(bvn == ''){
							$("#error_").html("Please enter your <b>Bank Verification Number</b>!");
						}/*else if(!validateEmail(email)) { 
							$("#error_").html("Please enter a valid email address!");
						}*/else {
							$("#error_").html("");
							$(".dloader").show();
							$('#errorm_').hide();
							$(".dmain").hide();
				            var url = encodeURI('{{ url("/processChoose") }}?selection='+nselection+'&user='+bvn);
				            $.ajax({    
				                type: "GET",
				                url: url,             
				                success: function(d) {   
				                	if(d == 'successful'){
					                	window.location = '{{ url("/myForm") }}';
					                }else if(d == 'not exists'){
					                	$(".dloader").hide();
					                	$(".dmain").show();
					                	$("#errorm_").html('<b>Bank Verification Number</b> does not exist on our system, Please fill the <big>New Loan</big> form!');
					                	$('#errorm_').show();
					                }
				                }
				            });
						}
					});
				});
			}else {
				$(".dloader").hide();
				$(".dmain").show();
				$('#errorm_').hide();
				$('#dbvn').hide();
				$(".dmain").click(function(){ 
					if(nselection == ''){
						$("#errorm_").html("Please select an option of your choice!");
					}else {
						$(".dloader").show();
						$(".dmain").hide();
						var url = encodeURI('{{ url("/processChoose") }}?selection='+nselection);
			            $.ajax({    
			                type: "GET",
			                url: url,             
			                success: function(d) {  
				                if(d == 'successs'){
				                	window.location = '{{ url("/myForm") }}';
				                }
			                }
			            });
					}
				});
			}
		});

	});
	function validateEmail($email) {
		var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
		return emailReg.test( $email );
	} 

</script>
@endsection