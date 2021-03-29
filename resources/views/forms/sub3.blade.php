@extends('layouts.form')
@section('content')
<div class="steps-area steps-area-fixed">
	<div class="image-holder">
		<img src="{{ asset('multilevel/img/side-img.jpg') }}" alt="">
	</div>
	<div class="steps clearfix">
		<ul class="tablist multisteps-form__progress">
			<li class="multisteps-form__progress-btn js-active current">
				<span>1</span>
			</li>
			<li class="multisteps-form__progress-btn">
				<span>2</span>
			</li>
			<li class="multisteps-form__progress-btn last">
				<span>3</span>
			</li>
		</ul>
	</div>
</div>
<form class="multisteps-form__form" action="{{ route('sub2') }}" id="wizard" method="POST" enctype="multipart/form-data">
	@csrf
	<div class="form-area position-relative">
		<div class="multisteps-form__panel js-active" data-animation="slideHorz">
			<div class="wizard-forms">
				<div class="inner pb-100 clearfix">
					<div class="form-content pera-content">
						<div class="step-inner-content">
							<span class="step-no">Step 1</span>
							<div class="step-progress float-right">
								<span>1 of 3 completed</span>
								<div class="step-progress-bar">
									<div class="progress">
										<div class="progress-bar" style="width:33.4%"></div>
									</div>
								</div>
							</div>
							<h2>Personal details</h2>
							<p style="opacity: 0">Please provide the following information. These informations are very important for the processing of your loan applications.</p>
							@if($customer != null)
							<input type="hidden" name="id" value="{{ $customer->id }}">
							<div class="upload-documents" style="margin-top: -60px;">
								<h3>Upload Passport:</h3>
								<div class="upload-araa bg-white">
									<input type="hidden" value="" name="fileContent" id="fileContent">
									<input type="hidden" value="" name="filename" id="filename">
									<div class="upload-icon float-left">
										<i class="fas fa-cloud-upload-alt"></i>
									</div>
									<div class="upload-text">
										<span>( File accepted: png. jpg/ jpeg -
										Max file size : 150kb)</span>
									</div>
									<div class="upload-option text-center">
										<label for="attach">Upload The Passport</label>
										<input id="attach" name="passport" style="visibility:hidden;" type="file" accept="image/*">
									</div>
								</div>
							</div>
							<div class="gender-selection">
								<h3>Title:</h3>
								<label>
								<input type="radio" name="title" value="Mr" {{ ($customer->title == "Mr")?'checked':'' }}>
								<span class="checkmark"></span>Mr
								</label>
								<label>
								<input type="radio" name="title" value="Mrs" {{ ($customer->title == "Mrs")?'checked':'' }}>
								<span class="checkmark"></span>Mrs
								</label>
								<label>
								<input type="radio" name="title" value="Miss" {{ ($customer->title == "Miss")?'checked':'' }}>
								<span class="checkmark"></span>Miss
								</label>
								<label>
								<input type="radio" name="title" value="Dr" {{ ($customer->title == "Dr")?'checked':'' }}>
								<span class="checkmark"></span>Dr
								</label>
								<label>
								<input type="radio" name="title" value="Chief" {{ ($customer->title == "Chief")?'checked':'' }}>
								<span class="checkmark"></span>Chief
								</label>
							</div>
							<div class="form-inner-area">
								<div class="row">
									<div class="col-md-6 col-lg-6 col-sm-12">
										<input type="text" name="fname" class="form-control" placeholder="First name" required value="{{ $customer->firstname }}">
									</div>
									<div class="col-md-6 col-lg-6 col-sm-12">
										<input type="text" name="mname" class="form-control" placeholder="Middle name" value="{{ $customer->middle_name }}">
									</div>
									<div class="col-md-12 col-lg-12 col-sm-12">
										<input type="text" name="sname" class="form-control" placeholder="Surname" required value="{{ $customer->surname }}">
									</div>
									<div class="col-md-12 col-lg-12 col-sm-12">
										<label>BVN</label>
										<input type="number" name="bvn" class="form-control" placeholder="Bank Verification Number" required value="{{ $customer->bvn }}" readonly>
									</div>
								</div>
							</div>
							<div class="form-inner-area">
								<div class="row">
									<div class="col-md-6 col-lg-6 col-sm-12">
										<input type="text" name="phone" class="form-control" placeholder="Phone Number" required value="{{ $customer->phone_no }}">
									</div>
									<div class="col-md-6 col-lg-6 col-sm-12">
										<input type="email" name="email" class="form-control" placeholder="Email Address" required value="{{ $customer->email }}">
									</div>
								</div>
							</div>
							<div class="step-content-area" style="margin-top: -60px;">
								<div class="budget-area">
									<p><i class="fas fa-location-arrow"></i> State of Residence</p>
									<select required name="state">
										<option value="Lagos">Lagos</option>
									</select>
									<p><i class="fas fa-street-view"></i> LGA of Residence</p>
									<select required name="lga">
										<option value="">--- Select an option ---</option>
									</select>

								</div>
							</div>
							<div class="form-inner-area">
								<div class="row">
									<div class="col-md-6 col-lg-6 col-sm-12">
										<input type="text" name="address" class="form-control" placeholder="Home Address" required value="{{ $customer->address }}">
									</div>
									<div class="col-md-6 col-lg-6 col-sm-12">
										<input type="text" name="landmark" class="form-control" placeholder="Landmark/Nearest Bus Stop" required value="{{ $customer->landmark }}">
									</div>
								</div>
							</div>
							@else
							<div class="upload-documents" style="margin-top: -60px;">
								<h3>Upload Passport:</h3>
								<div class="upload-araa bg-white">
									<input type="hidden" value="" name="fileContent" id="fileContent">
									<input type="hidden" value="" name="filename" id="filename">
									<div class="upload-icon float-left">
										<i class="fas fa-cloud-upload-alt"></i>
									</div>
									<div class="upload-text">
										<span>( File accepted: png. jpg/ jpeg -
										Max file size : 150kb)</span>
									</div>
									<div class="upload-option text-center">
										<label for="attach">Upload The Passport</label>
										<input id="attach" name="passport" style="visibility:hidden;" required type="file" accept="image/*">
									</div>
								</div>
							</div>
							<div class="gender-selection">
								<h3>Title:</h3>
								<label>
								<input type="radio" name="title" value="Mr" checked>
								<span class="checkmark"></span>Mr
								</label>
								<label>
								<input type="radio" name="title" value="Mrs">
								<span class="checkmark"></span>Mrs
								</label>
								<label>
								<input type="radio" name="title" value="Miss">
								<span class="checkmark"></span>Miss
								</label>
								<label>
								<input type="radio" name="title" value="Dr">
								<span class="checkmark"></span>Dr
								</label>
								<label>
								<input type="radio" name="title" value="Chief">
								<span class="checkmark"></span>Chief
								</label>
							</div>
							<div class="form-inner-area">
								<div class="row">
									<div class="col-md-6 col-lg-6 col-sm-12">
										<input type="text" name="fname" class="form-control" placeholder="First name" required>
									</div>
									<div class="col-md-6 col-lg-6 col-sm-12">
										<input type="text" name="mname" class="form-control" placeholder="Middle name">
									</div>
									<div class="col-md-12 col-lg-12 col-sm-12">
										<input type="text" name="sname" class="form-control" placeholder="Surname" required>
									</div>
									<div class="col-md-12 col-lg-12 col-sm-12">
										<label>BVN</label>
										<input type="number" name="bvn" class="form-control" placeholder="Bank Verification Number" readonly  value="{{ $bvn }}">
									</div>
								</div>
							</div>
							<div class="form-inner-area">
								<div class="row">
									<div class="col-md-6 col-lg-6 col-sm-12">
										<input type="text" name="phone" class="form-control" placeholder="Phone Number" required>
									</div>
									<div class="col-md-6 col-lg-6 col-sm-12">
										<input type="email" name="email" class="form-control" placeholder="Email Address" required>
									</div>
								</div>
							</div>
							<div class="step-content-area" style="margin-top: -60px;">
								<div class="budget-area">
									<p><i class="fas fa-location-arrow"></i> State of Residence</p>
									<select required name="state">
										<option value="Lagos">Lagos</option>
									</select>
									<p><i class="fas fa-street-view"></i> LGA of Residence</p>
									<select required name="lga">
										<option value="">--- Select an option ---</option>
									</select>

								</div>
							</div>
							<div class="form-inner-area">
								<div class="row">
									<div class="col-md-6 col-lg-6 col-sm-12">
										<input type="text" name="address" class="form-control" placeholder="Home Address" required>
									</div>
									<div class="col-md-6 col-lg-6 col-sm-12">
										<input type="text" name="landmark" class="form-control" placeholder="Landmark/Nearest Bus Stop" required>
									</div>
								</div>
							</div>
							@endif
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
			var url = "{{ url('/getback2') }}?place=zero";
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
		var dstate = $('select[name="state"]').val();
		var durl = encodeURI('{{ url("/getLgas3") }}/'+dstate);
		$.ajax({    
	        type: "GET",
	        url: durl,             
	        success: function(d) {  
	            $('select[name="lga"]').html(d);
	        }
	    });
		$('select[name="state"]').on('change', function(){
			var state = $('select[name="state"]').val();
			var url = encodeURI('{{ url("/getLgas3") }}/'+state);
			$.ajax({    
                type: "GET",
                url: url,             
                success: function(d) {  
	                $('select[name="lga"]').html(d);
                }
            });
		});
	});
</script>
@endsection