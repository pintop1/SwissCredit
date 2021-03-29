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
			<li class="multisteps-form__progress-btn js-active last current">
				<span>3</span>
			</li>
		</ul>
	</div>
</div>
<form class="multisteps-form__form" action="{{ route('sub2_2') }}" id="wizard" method="POST" enctype="multipart/form-data">
	@csrf
	<div class="form-area position-relative">
		<div class="multisteps-form__panel js-active" data-animation="slideHorz">
			<div class="wizard-forms">
				<div class="inner pb-100 clearfix">
					<div class="form-content pera-content">
						<div class="step-inner-content">
							<span class="step-no">Step 3</span>
							<div class="step-progress float-right">
								<span>3 of 3 completed</span>
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
									<input type="number" step="any" name="amount_requested" class="form-control required" placeholder="Loan Amount Requested " required>
								</div>
							</div>
							<div class="form-inner-area" style="margin-top: -90px;">
								<div class="step-content-area">
									<div class="comment-box">
										<p><i class="fas fa-comments"></i> Purpose of loan</p>
										<textarea name="purpose" placeholder="Write here"></textarea>
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
							<div class="gender-selection required">
								<h3>Loan Type?</h3>
								<label>
								<input type="radio" required name="type" value="Renewal">
								<span class="checkmark"></span>Renewal
								</label>
								<label>
								<input type="radio" required name="type" value="Top-up">
								<span class="checkmark"></span>Top-up
								</label>
							</div>
							<div class="form-inner-area" style="margin-top: 0;">
								<input type="number" step="any" name="existing_loan_amount" class="form-control" placeholder="Outstanding loan balance">
							</div>
							<h2 style="margin-top: -70px;">Other Information</h2>
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
			var url = "{{ url('/getback2') }}?place=two";
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
		$('input[name="type"]').on('change', function(){
			var vall = $('input[name="type"]:checked').val();
			if(vall == "Top-up"){
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