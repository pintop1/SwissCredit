@php
use App\Http\Controllers\Globals as Utils;
use Illuminate\Support\Collection;
@endphp

@extends('layouts.app')

@section('title', __('Customers > Add Customers || Swiss Credit Data Management system'))

@section('data', __('active'))
@section('offers', __('active'))

@section('head')
<link href="{{ asset('assets/plugins/dropify/css/dropify.min.css') }}" rel="stylesheet">
@endsection

@section('breadcrumb')
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="float-right">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="/offers">Offers</a></li>
                    <li class="breadcrumb-item active">Guarantor</li>
                </ol>
            </div>
            <h4 class="page-title">View Guarantor</h4>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 mb-2">
        <div class="float-right">
            <a href="/offers/guarantors/download/{{ $dguar->id }}" class="btn btn-primary btn-sm ml-3">
                <i class="lni-travel mr-2"></i>Download Form
            </a> 
        </div>
    </div>
    <div class="col-lg-12 mb-2">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <h4 class="title bg-gray text-white text-center py-2">GUARANTOR DETAILS</h4>
                        <div class="row">
                            <div class="form-group col-lg-12">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <img class="border border-gray" src="{{ asset($dguar->passport) }}" width="160">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-lg-3">
                                <label class="d-block">First Name</label> 
                                <input type="text" name="fname" class="form-control" readonly="" value="{{ $dguar->firstname }}">
                            </div>
                            <div class="form-group col-lg-3">
                                <label class="d-block">Middle Name</label> 
                                <input type="text" name="mname" class="form-control" readonly="" value="{{ $dguar->middle_name }}">
                            </div>
                            <div class="form-group col-lg-3">
                                <label class="d-block">Surname</label> 
                                <input type="text" name="sname" class="form-control" readonly="" value="{{ $dguar->surname }}">
                            </div>
                            <div class="form-group col-lg-3">
                                <label class="d-block">Title</label> 
                                <input type="text" name="title" class="form-control" readonly="" value="{{ $dguar->title }}">
                            </div>
                            <div class="form-group col-lg-6">
                                <label>Marital Status: </label>
                                <label><input type="radio" name="mstatus" value="Single" disabled="" {{ ($dguar->marital_status == "Single")?'checked':'' }}> <span class="ml-2 text-purple">Single</span></label>
                                <label class="ml-4"><input type="radio" name="mstatus" value="Married" disabled="" {{ ($dguar->marital_status == "Married")?'checked':'' }}> <span class="ml-2 text-purple">Married</span></label>
                                <label class="ml-4"><input type="radio" name="mstatus" value="Others" disabled="" {{ ($dguar->marital_status == "Others")?'checked':'' }}> <span class="ml-2 text-purple">Others</span></label>
                            </div>
                            <div class="form-group col-lg-6 text-right">
                                <label>Sex: </label>
                                <label><input type="radio" name="gender" value="Male" disabled="" {{ ($dguar->gender == "Male")?'checked':'' }}> <span class="ml-2 text-purple">Male</span></label>
                                <label class="ml-4"><input type="radio" name="gender" value="Female" disabled="" {{ ($dguar->gender == "Female")?'checked':'' }}> <span class="ml-2 text-purple">Female</span></label>
                            </div>
                            <div class="form-group col-lg-6">
                                <label class="d-block">Date Of Birth</label> 
                                <input type="date" name="dob" class="form-control" readonly="" value="{{ $dguar->dob }}">
                            </div>
                            <div class="form-group col-lg-6">
                                <label class="d-block">Nationality</label> 
                                <input type="text" name="nation" class="form-control" readonly="" value="{{ $dguar->nationality }}">
                            </div>
                            <div class="form-group col-lg-12">
                                <label class="d-block">Employer Name</label> 
                                <input type="text" name="employer" class="form-control" readonly="" value="{{ $dguar->employer_name }}">
                            </div>
                            <div class="form-group col-lg-12">
                                <label class="d-block">Office Address</label> 
                                <input type="text" name="office_addr" class="form-control" readonly="" value="{{ $dguar->office_address }}">
                            </div>
                            <div class="form-group col-lg-12">
                                <label>Are you currently a guarantor for a SWISS CREDIT LIMITED Client?</label>
                                <label><input type="radio" name="already_guarantor" value="Yes" disabled="" {{ ($dguar->currently_guarantor == "Yes")?'checked':'' }}> <span class="ml-2 text-purple">Yes</span></label>
                                <label class="ml-4"><input type="radio" name="already_guarantor" value="No" disabled{{ ($dguar->currently_guarantor == "No")?'checked':'' }}> <span class="ml-2 text-purple">No</span></label>
                            </div>
                            <div class="form-group col-lg-12">
                                <label class="d-block">If YES, state the name of the client and the Amount Guaranteed</label> 
                                <textarea name="yes_name" class="form-control" readonly="">{{ $dguar->if_yes_name }}</textarea>
                            </div>
                            <div class="form-group col-lg-12">
                                <label class="d-block">Residential Address</label> 
                                <input type="text" name="residential_addr" class="form-control" readonly="" value="{{ $dguar->residential_address }}">
                            </div>
                            <div class="form-group col-lg-4">
                                <label class="d-block">Office</label> 
                                <input type="text" name="office_no" class="form-control" readonly="" value="{{ $dguar->office_no }}">
                            </div>
                            <div class="form-group col-lg-4">
                                <label class="d-block">Mobile</label> 
                                <input type="text" name="mobile_no" class="form-control" readonly="" value="{{ $dguar->mobile_no }}">
                            </div>
                            <div class="form-group col-lg-4">
                                <label class="d-block">Home</label> 
                                <input type="text" name="home_no" class="form-control" readonly="" value="{{ $dguar->home_no }}">
                            </div>
                            <div class="form-group col-lg-12">
                                <label class="d-block">Personal Email</label> 
                                <input type="email" name="personal_email" class="form-control" readonly="" value="{{ $dguar->personal_email }}">
                            </div>
                            <div class="form-group col-lg-12">
                                <label class="d-block">Official Email</label> 
                                <input type="email" name="official_email" class="form-control" readonly="" value="{{ $dguar->official_email }}">
                            </div>
                            <div class="form-group col-lg-12">
                                <label class="d-block">Relationship With Applicant</label> 
                                <input type="text" name="relationship" class="form-control" readonly="" value="{{ $dguar->relationship_with_applicant }}">
                            </div>
                            <div class="form-group col-lg-6">
                                <label class="d-block">Position Held / Designation</label> 
                                <input type="text" name="position" class="form-control" readonly="" value="{{ $dguar->position_held }}">
                            </div>
                            <div class="form-group col-lg-6">
                                <label class="d-block">Branch Telephone</label> 
                                <input type="text" name="branch_telephone" class="form-control" readonly="" value="{{ $dguar->branch_telephone }}">
                            </div>
                            <div class="form-group col-lg-12">
                                <label class="d-block">Annual Income (Please tick as appropriate)</label>
                                <label><input type="radio" name="income" value="N500,000.00 - N1,000,000.00" disabled="" {{ ($dguar->income == "N500,000.00 - N1,000,000.00")?'checked':'' }}> <span class="ml-2 text-purple">N500,000.00 - N1,000,000.00</span></label>
                                <label class="ml-4"><input type="radio" name="income" value="N1,000,000.00 - N5,000,000.00" disabled="" {{ ($dguar->income == "N1,000,000.00 - N5,000,000.00")?'checked':'' }}> <span class="ml-2 text-purple">N1,000,000.00 - N5,000,000.00</span></label>
                                <label class="ml-4"><input type="radio" name="income" value="N5,000,000.00 - N10,000,000.00" disabled="" {{ ($dguar->income == "N5,000,000.00 - N10,000,000.00")?'checked':'' }}> <span class="ml-2 text-purple">N5,000,000.00 - N10,000,000.00</span></label>
                                <label class="ml-4"><input type="radio" name="income" value="N10,000,000.00 - Above" disabled="" {{ ($dguar->income == "N10,000,000.00 - Above")?'checked':'' }}> <span class="ml-2 text-purple">N10,000,000.00 - Above</span></label>
                            </div>
                            <div class="form-group col-lg-12">
                                <div class="d-inline-block">
                                    <label>Did you provide cheque? </label>
                                    <label><input type="radio" name="cheque" value="Yes" disabled=""> <span class="ml-2 text-purple">Yes</span></label>
                                    <label class="ml-4"><input type="radio" name="cheque" value="No" disabled=""> <span class="ml-2 text-purple">No</span></label>
                                </div>
                                <div class="d-inline-block ml-4">
                                    <label class="mr-3">If yes, how many?</label> 
                                    <input type="number" name="cheque_counts" class="form-control-inline" readonly="" value="{{ $dguar->cheque_count }}">
                                </div>
                            </div>
                            <div class="form-group col-lg-12">
                                <label>DETAILS OF OUTSTANDING LOAN IF ANY</label> 
                            </div>
                            <div class="form-group col-lg-6">
                                <label class="d-block">With SWISS CREDIT</label> 
                                <textarea name="with_swiss_credit" class="form-control" readonly="">{{ $dguar->other_outstanding_swiss }}</textarea>
                            </div>
                            <div class="form-group col-lg-6">
                                <label class="d-block">With OTHER BANKS</label> 
                                <textarea name="with_other_banks" class="form-control" readonly="">{{ $dguar->other_outstanding_other }}</textarea>
                            </div>
                            <div class="form-group col-lg-12 border border-dark">
                                <label class="d-block">DECLARATION</label>
                                <div>I <b>{{ ucwords($dguar->firstname.' '.$dguar->middle_name.' '.$dguar->surname) }}</b> undertake to REPAY to SWISS CREDITLIMITED the value of the loan/facilityor any indebtedness and other associated cost of debt recovery/charges on behalf of the above mentioned applicant if he fails to repay the loan/facility. I also attest to the fact that I am duly about this credit transaction between SWISS CREDIT LIMITED and the applicant.<br><br>I have read and understood the information above and therefore sign below.<br>Attached to this form is a copy of my valid employer identity card and post dated cheque.</div>
                                <div class="row">
                                    <div class="col-lg-6 text-left">
                                        <h4 class="card-title d-inline-block">Signature: </h4>
                                        @if($sign->default == null)
                                        <div class="text-danger d-inline-block">No default signature set, click the link to set on now.. <a href="/offers/view/guarantors/signature/{{ $sign->id }}" class="btn btn-primary btn-sm">Click here</a></div>
                                        @else
                                            @if($sign->default == '1')
                                            <img class="d-inline-block" src="data:image/svg+xml;base64,{!! base64_encode($sign->sign_1) !!}" width="200" height="200">
                                            @else
                                            <img class="d-inline-block" src="data:image/svg+xml;base64,{!! base64_encode($sign->sign_2) !!}" width="200" height="200">
                                            @endif
                                        @endif
                                        <a href="/offers/view/guarantors/signature/{{ $sign->id }}" target="_blank" class="btn btn-primary btn-sm">View both signatures</a>
                                    </div>
                                    <div class="col-lg-6 text-left mt-5">
                                        <h4 class="card-title d-inline-block">Date:</h4>
                                        <h2 class="text-danger d-inline-block">
                                            {{ date('d|m|Y', strtotime($sign->created_at)) }}
                                        </h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('foot')
<script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/plugins/dropify/js/dropify.min.js') }}"></script>
<script src="{{ asset('assets/pages/jquery.form-upload.init.js') }}"></script>
@endsection