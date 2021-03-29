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
			<li class="multisteps-form__progress-btn">
				<span>3</span>
			</li>
			<li class="multisteps-form__progress-btn">
				<span>4</span>
			</li>
			<li class="multisteps-form__progress-btn last">
				<span>5</span>
			</li>
		</ul>
	</div>
</div>
<form class="multisteps-form__form" action="{{ route('sub4_1') }}" id="wizard" method="POST" enctype="multipart/form-data">
	@csrf
	<div class="form-area position-relative">
		<div class="multisteps-form__panel js-active" data-animation="slideHorz">
			<div class="wizard-forms">
				<div class="inner pb-100 clearfix">
					<div class="form-content pera-content">
						<div class="step-inner-content">
							<span class="step-no">Step 2</span>
							<div class="step-progress float-right">
								<span>2 of 5 completed</span>
								<div class="step-progress-bar">
									<div class="progress">
										<div class="progress-bar" style="width:40%"></div>
									</div>
								</div>
							</div>
							<h2>Marital Status</h2>
							<p style="opacity: 0">Tation argumentum et usu, dicit viderer evertitur te has. Eu dictas concludaturque usu, facete detracto patrioque an per, lucilius pertinacia eu vel.</p>
							<input type="hidden" name="id" value="{{ $customer->id }}">
							<div class="services-select-option" style="margin-top: -50px">
								<ul>
									@if($customer->mstatus == "Single" || $customer->mstatus == '')<li class="bg-white active">@else<li class="bg-white">@endif<label>Single <input type="radio" name="mstatus" value="Single" {{ ($customer->mstatus == "Single")?'checked':'' }} {{ ($customer->mstatus == '')?'checked':'' }}></label></li>
									@if($customer->mstatus == "Married")<li class="bg-white active">@else<li class="bg-white">@endif<label>Married <input type="radio" name="mstatus" value="Married" {{ ($customer->mstatus == "Married")?'checked':'' }}></label></li>
									@if($customer->mstatus == "Seperated")<li class="bg-white active">@else<li class="bg-white">@endif<label>Seperated <input type="radio" name="mstatus" value="Seperated" {{ ($customer->mstatus == "Seperated")?'checked':'' }}></label></li>
									@if($customer->mstatus == "Divorced")<li class="bg-white active">@else<li class="bg-white">@endif<label>Divorced <input type="radio" name="mstatus" value="Divorced" {{ ($customer->mstatus == "Divorced")?'checked':'' }}></label></li>
									@if($customer->mstatus == "Widowed")<li class="bg-white active">@else<li class="bg-white">@endif<label>Widowed <input type="radio" name="mstatus" value="Widowed" {{ ($customer->mstatus == "Widowed")?'checked':'' }}></label></li>
								</ul>
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
			var url = "{{ url('/getback') }}?place=one";
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
		var durl = encodeURI('{{ url("/getLgas") }}/'+dstate);
		$.ajax({    
	        type: "GET",
	        url: durl,             
	        success: function(d) {  
	            $('select[name="lga"]').html(d);
	        }
	    });
		$('select[name="state"]').on('change', function(){
			var state = $('select[name="state"]').val();
			var url = encodeURI('{{ url("/getLgas") }}/'+state);
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