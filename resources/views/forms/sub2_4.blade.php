@extends('layouts.form')
@section('content')
<div class="steps-area steps-area-fixed">
	<div class="image-holder">
		<img src="{{ asset('multilevel/img/side-img.jpg') }}" alt="">
	</div>
	<div class="steps clearfix">
		<ul class="tablist multisteps-form__progress">
			<li class="multisteps-form__progress-btn js-active">
				<span>1</span>
			</li>
			<li class="multisteps-form__progress-btn js-active ">
				<span>2</span>
			</li>
			<li class="multisteps-form__progress-btn js-active">
				<span>3</span>
			</li>
			<li class="multisteps-form__progress-btn js-active">
				<span>4</span>
			</li>
			<li class="multisteps-form__progress-btn last js-active current">
				<span>5</span>
			</li>
		</ul>
	</div>
</div>
<form class="multisteps-form__form" action="{{ route('sub1_4') }}" id="wizard" method="POST" enctype="multipart/form-data">
	@csrf
	<div class="form-area position-relative">
		<div class="multisteps-form__panel js-active" data-animation="slideHorz">
			<div class="wizard-forms">
				<div class="inner pb-100 clearfix">
					<div class="form-content pera-content">
						<div class="step-inner-content">
							<span class="step-no">Step 5</span>
							<div class="step-progress float-right">
								<span>5 of 5 completed</span>
								<div class="step-progress-bar">
									<div class="progress">
										<div class="progress-bar" style="width:100%"></div>
									</div>
								</div>
							</div>
							<h2>Loan Details</h2>
							<input type="hidden" name="id" value="{{ $customer->id }}">
							<p style="opacity: 0;z-index: 0;">Tation argumentum et usu, dicit viderer evertitur te has. Eu dictas concludaturque usu, facete detracto patrioque an per, lucilius pertinacia eu vel.</p>
							<div class="form-inner-area" style="margin-top: -90px;">
								<div class="step-content-area">
									<input type="number" step="any" name="amount_requested" class="form-control required" placeholder="Loan Amount Requested" required>
									<p>Purpose of Loan</p>
								</div>
							</div>
							<div class="services-select-option">
								<ul>
									<li class="bg-white active"><input type="radio" name="purpose" value="Portable Goods" checked>Portable Goods</li>
									<li class="bg-white"><input type="checkbox" name="purpose" value="Travel/Holiday">Travel/Holiday</li>
									<li class="bg-white"><input type="checkbox" name="purpose" value="Medical">Medical</li>
									<li class="bg-white"><input type="checkbox" name="purpose" value="House maintenance">House maintenance</li>
									<li class="bg-white"><input type="checkbox" name="purpose" value="Rent">Rent</li>
									<li class="bg-white"><input type="checkbox" name="purpose" value="School Fees">School Fees</li>
									<li class="bg-white"><input type="checkbox" name="purpose" value="Wedding/Events">Wedding/Events</li>
									<li class="bg-white"><input type="checkbox" name="purpose" value="Fashion Goods">Fashion Goods</li>
								</ul>
							</div>
							<div class="form-inner-area" style="margin-top: -90px;">
								<div class="step-content-area">
									<div class="comment-box">
										<p><i class="fas fa-comments"></i> Other Purpose of loan</p>
										<textarea name="other_purpose" placeholder="Write here"></textarea>
									</div>
									<div class="budget-area">
										<p><i class="fas fa-time"></i> Tenure </p>
										<select name="tenure">
											<option value="1">1 month</option>
											@for($i=2;$i<16;$i++)
											<option value="{{ $i }}">{{ $i }} months</option>
											@endfor
										</select>
									</div>
								</div>
							</div>
							<h2 style="margin-top: -70px;">Other Information</h2>
							<div class="gender-selection required">
								<h3>Do you have an existing Loan?</h3>
								<label>
								<input type="radio" required name="existing_loan" value="Yes">
								<span class="checkmark"></span>Yes
								</label>
								<label>
								<input type="radio" required name="existing_loan" value="No">
								<span class="checkmark"></span>No
								</label>
							</div>
							<div class="form-inner-area" style="margin-top: 0;">
								<input type="number" step="any" name="existing_loan_amount" class="form-control" placeholder="Loan Amount">
							</div>
							<h2 style="margin-top: -70px;">How did you hear about us?</h2>
							<div class="gender-selection">
								<label><input type="radio" required name="hear_us" value="Flyer"><span class="checkmark"></span> Flyer</label>
								<label><input type="radio" name="hear_us" value="Salesman"><span class="checkmark"></span> Salesman</label>
								<label><input type="radio" name="hear_us" value="Facebook"><span class="checkmark"></span> Facebook</label>
								<label><input type="radio" name="hear_us" value="Instagram"><span class="checkmark"></span> Instagram</label>
								<label><input type="radio" name="hear_us" value="Twitter"><span class="checkmark"></span> Twitter</label>
								<label><input type="radio" name="hear_us" value="LinkedIn"><span class="checkmark"></span> LinkedIn</label>
								<label><input type="radio" name="hear_us" value="Google Ads"><span class="checkmark"></span> Google Ads</label>
								<label><input type="radio" name="hear_us" value="Cinema"><span class="checkmark"></span> Cinema</label>
								<label><input type="radio" name="hear_us" value="Radio"><span class="checkmark"></span> Radio</label>
								<label><input type="radio" name="hear_us" value="Swiss Club"><span class="checkmark"></span>  Swiss Club</label>
								<label><input type="radio" name="hear_us" value="Friendly Referral"><span class="checkmark"></span>  Friendly Referral</label>
								<label><input type="radio" name="hear_us" value="Telesales"><span class="checkmark"></span> Telesales</label>
								<label><input type="radio" name="hear_us" value="SMS"><span class="checkmark"></span> SMS</label>
								<label><input type="radio" name="hear_us" value="BRT"><span class="checkmark"></span> BRT</label>
								<label><input type="radio" name="hear_us" value="Billboard"><span class="checkmark"></span> Billboard</label>
								<label><input type="radio" name="hear_us" value="Magazine"><span class="checkmark"></span> Magazine</label>
								<label><input type="radio" name="hear_us" value="Newspaper"><span class="checkmark"></span> Newspaper</label>
							</div>
							<div class="form-inner-area" style="margin-top: 0;">
								<div class="text-success refR"></div>
								<input type="text" name="referral_code" class="form-control" placeholder="Referral Code (If available)">
							</div>
						</div>
					</div>
				</div>
				<div class="actions">
					<ul>
						<li mmi><span class="js-btn-prev" id="dprev" title="BACK"><i class="fa fa-arrow-left"></i> BACK </span></li>
						<li class="disable mmi" aria-disabled="true"><span class="js-btn-next" title="NEXT">Backward <i class="fa fa-arrow-right"></i></span></li>
						<li class="mmi"><button type="submit" title="NEXT">SUBMIT <i class="fa fa-arrow-right"></i></button></li>
						<li class="ddi"><span class="js-btn-next" title="NEXT"><img src="{{ asset('loader.gif') }}" width="90"></span></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</form>
@endsection
@section('foot')
<script>
	$(function() {
		$(".ddi").hide();
		$(".mmi").show();
		$("#dprev").click(function(){
			$(".mmi").hide();
			$(".ddi").show();
			var url = "{{ url('/getback') }}?place=four";
			$.ajax({    
                type: "GET",
                url: url,             
                success: function(d) {  
	                if(d == 'success'){
	                	window.location = '{{ url("/myForm") }}';
	                } 
                }
            });
		});
		$('input[name="existing_loan_amount"]').hide();
		$('input[name="existing_loan"]').on('change', function(){
			var vall = $('input[name="existing_loan"]:checked').val();
			if(vall == "Yes"){
				$('input[name="existing_loan_amount"]').show();
			}else {
				$('input[name="existing_loan_amount"]').hide();
			}
		});
		$('input[name="referral_code"]').on('input', function(){
			$(".refR").html("Verifying referral.... Please wait.");
			var query = $('input[name="referral_code"]').val();
			var url = '{{ url("/verifyReferral/?code=") }}'+query; 
			$.get(url, function(data){
	            $(".refR").html(data);
          	});
		});
	});
</script>
@endsection