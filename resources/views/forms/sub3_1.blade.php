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
			<li class="multisteps-form__progress-btn js-active current">
				<span>2</span>
			</li>
			<li class="multisteps-form__progress-btn last">
				<span>3</span>
			</li>
		</ul>
	</div>
</div>
<form class="multisteps-form__form" action="{{ route('sub2_1') }}" id="wizard" method="POST" enctype="multipart/form-data">
	@csrf
	<div class="form-area position-relative">
		<div class="multisteps-form__panel js-active" data-animation="slideHorz">
			<div class="wizard-forms">
				<div class="inner pb-100 clearfix">
					<div class="form-content pera-content">
						<div class="step-inner-content">
							<span class="step-no">Step 2</span>
							<div class="step-progress float-right">
								<span>2 of 3 completed</span>
								<div class="step-progress-bar">
									<div class="progress">
										<div class="progress-bar" style="width:66.7%"></div>
									</div>
								</div>
							</div>
							<p style="opacity: 0;">Tation argumentum et usu, dicit viderer evertitur te has. Eu dictas concludaturque usu, facete detracto patrioque an per, lucilius pertinacia eu vel.</p>
							<input type="hidden" name="id" value="{{ $customer->id }}">
							<div class="step-content-area" style="margin-top: -60px;">
								<div class="budget-area">
									<p><i class="fas fa-user-md"></i> Employment Status</p>
									<select name="employment_status" required>
										<option {{ ($customer->emp_status == "Full Time")?'selected':'' }}>Full Time</option>
										<option {{ ($customer->emp_status == "Part  Time")?'selected':'' }}>Part Time</option>
										<option {{ ($customer->emp_status == "Retired")?'selected':'' }}>Retired</option>
										<option {{ ($customer->emp_status == "Self Employed")?'selected':'' }}>Self Employed</option>
										<option {{ ($customer->emp_status == "Student")?'selected':'' }}>Student</option>
										<option {{ ($customer->emp_status == "Temp Contract")?'selected':'' }}>Temp Contract</option>
										<option {{ ($customer->emp_status == "Unemployed")?'selected':'' }}>Unemployed</option>
										<option {{ ($customer->emp_status == "House Wife")?'selected':'' }}>House Wife</option>
										<option {{ ($customer->emp_status == "Outsourced/Contract")?'selected':'' }}>Outsourced/Contract</option>
										<option {{ ($customer->emp_status == "Public")?'selected':'' }}>Public</option>
										<option {{ ($customer->emp_status == "Private")?'selected':'' }}>Private</option>
									</select>
								</div>
							</div>
							<div class="form-inner-area">
								<div class="row">
									<div class="col-md-6 col-lg-6 col-sm-12">
										<input type="text" name="current_employer" class="form-control required" placeholder="Current Employer" required value="{{ $customer->current_employer }}">
									</div>
									<div class="col-md-6 col-lg-6 col-sm-12">
										<input type="text" name="current_employer_address" class="form-control required" placeholder="Current Employer Address" required value="{{ $customer->current_employer_address }}">
									</div>
									<div class="col-md-12 col-lg-12 col-sm-12">
										<input type="text" name="current_employer_landmark" class="form-control required" placeholder="Landmark/Nearest Bus Stop" required value="{{ $customer->landmark_office }}">
									</div>
									<div class="col-md-12 col-lg-12 col-sm-12">
										<input type="number" step="any" name="salary" class="form-control required" placeholder="Current Net Monthly income" required value="{{ $customer->salary }}">
									</div>
								</div>
							</div>
							<div class="step-content-area" style="margin-top: -60px;">
								<div class="budget-area">
									<p><i class="fas fa-location-arrow"></i> State (Of Work)</p>
									<select required name="current_employer_state">
										<option value="">--- Select an option ---</option>
										@foreach($states as $state)
										<option {{ ($customer->state_office == $state->state->name)?'selected':'' }}>{{ $state->state->name }}</option>
										@endforeach
									</select>
									<p><i class="fas fa-street-view"></i> LGA (Of Work)</p>
									<select required name="current_employer_lga">
										<option value="">--- Select an option ---</option>
									</select>

								</div>
							</div>
							<div class="form-inner-area">
								<div class="row">
									<div class="col-md-12 col-lg-12 col-sm-12">
										<input type="email" name="current_employer_work_email" class="form-control required" placeholder="Employer's Work Email" required value="{{ $customer->work_email }}">
									</div>
								</div>
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
			var url = "{{ url('/getback2') }}?place=one";
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
		var dstate = $('select[name="current_employer_state"]').val();
		var durl = encodeURI('{{ url("/getLgas4") }}/'+dstate);
		$.ajax({    
	        type: "GET",
	        url: durl,             
	        success: function(d) {  
	            $('select[name="current_employer_lga"]').html(d);
	        }
	    });
		$('select[name="current_employer_state"]').on('change', function(){
			var state = $('select[name="current_employer_state"]').val();
			var url = encodeURI('{{ url("/getLgas4") }}/'+state);
			$.ajax({    
                type: "GET",
                url: url,             
                success: function(d) {  
	                $('select[name="current_employer_lga"]').html(d);
                }
            });
		});
	});
</script>
@endsection