@php
use App\Http\Controllers\Globals as Utils;
use Illuminate\Support\Collection;
@endphp

@extends('layouts.app')

@if(isset($edit))
@section('title', __('Customers > Edit Customer || Swiss Credit Data Management system'))
@else
@section('title', __('Customers > Add Customers || Swiss Credit Data Management system'))
@endif

@section('forms', __('active'))
@section('hn', __('active'))

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
                    <li class="breadcrumb-item"><a href="/customers">Customers</a></li>
                    @if(isset($edit))
                    <li class="breadcrumb-item active">Edit Customer</li>
                    @else
                    <li class="breadcrumb-item active">Add Customers</li>
                    @endif
                </ol>
            </div>
            @if(isset($edit))
            <h4 class="page-title">Edit Customer</h4>
            @else
            <h4 class="page-title">Add Customers</h4>
            @endif
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    @if(!isset($edit))
    <div class="col-lg-12 mb-2">
        <div class="float-right">
            <a href="/customers/add/generate-form" data-toggle="modal" data-target="#modalLoginForm" class="btn btn-primary btn-sm ml-3">
                <i class="lni-travel mr-2"></i>Generate Form To User
            </a> 
        </div>
    </div>
    @endif
    @if(isset($edit))
    <div class="col-lg-12 mb-2">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('editCustomer') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6 border-right border-gray">
                            <h4 class="title bg-gray text-white text-center py-2">PERSONAL DETAILS</h4>
                            <div class="form-group">
                                <label>Passport</label> 
                                <input type="file" name="passport" id="input-file-now" class="dropify" accept="image/*" data-default-file="{{ asset($customer->passport) }}">
                                <input type="hidden" name="id" value="{{ $id }}">
                            </div>
                            <div class="form-group">
                                <label class="block">Title</label> 
                                <label><input type="radio" name="title" value="Mr" {{ ($customer->title == 'Mr')?'checked':'' }} > <span class="ml-2 text-purple">Mr</span></label>
                                <label class="ml-4"><input type="radio" name="title" value="Mrs" {{ ($customer->title == 'Mrs')?'checked':'' }} > <span class="ml-2 text-purple">Mrs</span></label>
                                <label class="ml-4"><input type="radio" name="title" value="Miss" {{ ($customer->title == 'Miss')?'checked':'' }} > <span class="ml-2 text-purple">Miss</span></label>
                                <label class="ml-4"><input type="radio" name="title" value="Dr" {{ ($customer->title == 'Dr')?'checked':'' }} > <span class="ml-2 text-purple">Dr</span></label>
                                <label class="ml-4"><input type="radio" name="title" value="Chief" {{ ($customer->title == 'Chief')?'checked':'' }} > <span class="ml-2 text-purple">Chief</span></label>
                            </div>
                            <div class="form-group">
                                <label class="d-block">First Name</label> 
                                <input type="text" name="fname" class="form-control" value="{{ $customer->firstname }}" >
                            </div>
                            <div class="form-group">
                                <label class="d-block">Middle Name</label> 
                                <input type="text" name="mname" class="form-control" value="{{ $customer->middle_name }}" >
                            </div>
                            <div class="form-group">
                                <label class="d-block">Surname</label> 
                                <input type="text" name="sname" class="form-control" value="{{ $customer->surname }}" >
                            </div>
                            <div class="form-group">
                                <label class="d-block">Maiden name</label> 
                                <input type="text" name="maiden_name" class="form-control" value="{{ $customer->maiden_name }}" >
                            </div>
                            <div class="form-group">
                                <label class="d-block">Bank Verification Number</label> 
                                <input type="text" name="bvn" class="form-control" value="{{ $customer->bvn }}" >
                            </div>
                            <div class="form-group">
                                <label class="d-block">Date Of Birth</label> 
                                <input type="text" name="dob" class="form-control" value="{{ $customer->date_of_birth }}" >
                            </div>
                            <div class="form-group">
                                <label class="d-block">Gender</label> 
                                <label><input type="radio" name="gender" value="Male" {{ ($customer->gender == 'Male')?'checked':'' }} > <span class="ml-2 text-purple">Male</span></label>
                                <label class="ml-4"><input type="radio" name="gender" value="Female" {{ ($customer->gender == 'Female')?'checked':'' }} > <span class="ml-2 text-purple">Female</span></label>
                            </div>
                            <div class="form-group">
                                <label class="d-block">Means of Identification</label> 
                                <label><input type="radio" name="moi" value="International Passport" {{ ($customer->identification == 'International Passport')?'checked':'' }} > <span class="ml-2 text-purple">International Passport</span></label>
                                <label class="ml-4"><input type="radio" name="moi" value="Voter's Card" {{ ($customer->identification == 'Voter\'s Card')?'checked':'' }} > <span class="ml-2 text-purple">Voter's Card</span></label>
                                <label class="ml-4"><input type="radio" name="moi" value="National ID Card" {{ ($customer->identification == 'National ID Card')?'checked':'' }} > <span class="ml-2 text-purple">National ID Card</span></label>
                                <label class="ml-4"><input type="radio" name="moi" value="Driver's License"  {{ ($customer->identification == 'Driver\'s License')?'checked':'' }} > <span class="ml-2 text-purple">Driver's License</span></label>
                                <label class="ml-4"><input type="radio" name="moi" value="Others" {{ ($customer->identification == 'Others')?'checked':'' }} > <span class="ml-2 text-purple">Others</span></label>
                            </div>
                            <div class="form-group">
                                <label class="d-block">Document Number</label> 
                                <input type="text" name="doc_number" class="form-control" value="{{ $customer->doc_number }}" >
                            </div>
                            <div class="form-group">
                                <label class="d-block">Document Issued Date</label> 
                                <input type="text" name="doc_issued_date" class="form-control" value="{{ $customer->issue_date }}" >
                            </div>
                            <div class="form-group">
                                <label class="d-block">Document Expiry Date</label> 
                                <input type="text" name="doc_expiry_date" class="form-control" value="{{ $customer->expiry_date }}" >
                            </div>
                            <div class="form-group">
                                <label class="d-block">Phone Number</label> 
                                <input type="text" name="phone_no" class="form-control" value="{{ $customer->phone_no }}" >
                            </div>
                            <div class="form-group">
                                <label class="d-block">Mobile Number</label> 
                                <input type="text" name="mobile_no" class="form-control" value="{{ $customer->mobile_no }}" >
                            </div>
                            <div class="form-group">
                                <label class="d-block">Office Number</label> 
                                <input type="text" name="office_no" class="form-control" value="{{ $customer->office_no }}" >
                            </div>
                            <div class="form-group">
                                <label class="d-block">Email Address</label> 
                                <input type="text" name="email" class="form-control"  value="{{ $customer->email }}" >
                            </div>
                            <div class="form-group">
                                <label class="d-block">Official Email Address</label> 
                                <input type="text" name="official_email" class="form-control" value="{{ $customer->official_email }}" >
                            </div>
                            <div class="form-group">
                                <label class="d-block">Home Address</label> 
                                <input type="text" name="home_address" class="form-control" value="{{ $customer->address }}" >
                            </div>
                            <div class="form-group">
                                <label class="d-block">Landmark/Nearest Bus Stop</label> 
                                <input type="text" name="landmark" class="form-control" value="{{ $customer->landmark }}" >
                            </div>
                            <div class="form-group">
                                <label class="d-block">LGA (of Residence)</label> 
                                <input type="text" name="lga_residence" class="form-control"  value="{{ $customer->lga_of_residence }}" >
                            </div>
                            <div class="form-group">
                                <label class="d-block">State</label> 
                                <input type="text" name="state_residence" class="form-control" value="{{ $customer->state }}" >
                            </div>
                            <div class="form-group">
                                <label class="d-block">Time at Current Address</label> 
                                <label><input type="radio" name="time_current_address" value="Years" {{ ($customer->time_at_address == 'Years')?'checked':'' }} > <span class="ml-2 text-purple">Years</span></label>
                                <label class="ml-4"><input type="radio" name="time_current_address" value="Months" {{ ($customer->time_at_address == 'Months')?'checked':'' }} > <span class="ml-2 text-purple">Months</span></label>
                            </div>
                            <div class="form-group">
                                <label class="d-block">Residential Status</label> 
                                <label><input type="radio" name="residential_status" value="Tenant" {{ ($customer->residential_status == 'Tenant')?'checked':'' }} > <span class="ml-2 text-purple">Tenant</span></label>
                                <label class="ml-4"><input type="radio" name="residential_status" value="Owner" {{ ($customer->residential_status == 'Owner')?'checked':'' }} > <span class="ml-2 text-purple">Owner</span></label>
                                <label class="ml-4"><input type="radio" name="residential_status" value="With Relatives" {{ ($customer->residential_status == 'With Relatives')?'checked':'' }} > <span class="ml-2 text-purple">With Relatives</span></label>
                                <label class="ml-4"><input type="radio" name="residential_status" value="With Parents" {{ ($customer->residential_status == 'With Parents')?'checked':'' }} > <span class="ml-2 text-purple">With Parents</span></label>
                            </div>
                            <div class="form-group">
                                <label class="d-block">Previous Address if resident at Current address is less than 3 years</label> 
                                <input type="text" name="prev_address" class="form-control" value="{{ $customer->prev_address }}" >
                            </div>
                            <div class="form-group">
                                <label class="d-block">Time at Previous Address</label> 
                                <label><input type="radio" name="time_prev_address" value="Years" {{ ($customer->time_prev == 'Years')?'checked':'' }} > <span class="ml-2 text-purple">Years</span></label>
                                <label class="ml-4"><input type="radio" name="time_current_address" value="Months" {{ ($customer->time_prev == 'Months')?'checked':'' }} > <span class="ml-2 text-purple">Months</span></label>
                            </div>
                            <h4 class="title bg-gray text-white text-center py-2">EDUCATIONAL STATUS</h4>
                            <div class="form-group">
                                <label><input type="radio" name="eduStatus" value="Primary" {{ ($customer->educational_status == 'Primary')?'checked':'' }} > <span class="ml-2 text-purple">Primary</span></label>
                                <label class="ml-4"><input type="radio" name="eduStatus" value="Secondary" {{ ($customer->educational_status == 'Secondary')?'checked':'' }} > <span class="ml-2 text-purple">Secondary</span></label>
                                <label class="ml-4"><input type="radio" name="eduStatus" value="Graduate" {{ ($customer->educational_status == 'Graduate')?'checked':'' }} > <span class="ml-2 text-purple">Graduate</span></label>
                                <label class="ml-4"><input type="radio" name="eduStatus" value="Post Graduate" {{ ($customer->educational_status == 'Post Graduate')?'checked':'' }} > <span class="ml-2 text-purple">Post Graduate</span></label>
                            </div>
                            <h4 class="title bg-gray text-white text-center py-2">PURPOSE OF LOAN</h4>
                            <div class="form-group">
                                <label><input type="radio" name="purpose" value="Portable Goods" {{ ($customer->purpose == 'Portable Goods')?'checked':'' }} > <span class="ml-2 text-purple">Portable Goods</span></label>
                                <label class="ml-4"><input type="radio" name="purpose" value="Travel/Holiday" {{ ($customer->purpose == 'Travel/Holiday')?'checked':'' }} > <span class="ml-2 text-purple">Travel/Holiday</span></label>
                                <label class="ml-4"><input type="radio" name="purpose" value="Medical" {{ ($customer->purpose == 'Medical')?'checked':'' }} > <span class="ml-2 text-purple">Medical</span></label>
                                <label class="ml-4"><input type="radio" name="purpose" value="House Maintenance" {{ ($customer->purpose == 'House Maintenance')?'checked':'' }} > <span class="ml-2 text-purple">House Maintenance</span></label>
                                <label class="ml-4"><input type="radio" name="purpose" value="Rent" {{ ($customer->purpose == 'Rent')?'checked':'' }} > <span class="ml-2 text-purple">Rent</span></label>
                                <label class="ml-4"><input type="radio" name="purpose" value="School Fees" {{ ($customer->purpose == 'School Fees')?'checked':'' }} > <span class="ml-2 text-purple">School Fees</span></label>
                                <label class="ml-4"><input type="radio" name="purpose" value="Wedding/Events" {{ ($customer->purpose == 'Wedding/Events')?'checked':'' }} > <span class="ml-2 text-purple">Wedding/Events</span></label>
                                <label class="ml-4"><input type="radio" name="purpose" value="Fashion Goods" {{ ($customer->purpose == 'Fashion Goods')?'checked':'' }} > <span class="ml-2 text-purple">Fashion Goods</span></label>
                            </div>
                            <div class="form-group">
                                <label class="d-block">Other Purpose (Please Specify)</label> 
                                <input type="text" name="other_purpose" class="form-control" value="{{ $customer->other_purpose }}" >
                            </div>
                            <h4 class="title bg-gray text-white text-center py-2">OTHER INFORMATION</h4>
                            <div class="form-group">
                                <label class="d-block">Do you have an existing Loan?</label>
                                <label><input type="radio" name="existing_loan" value="Yes" {{ ($customer->existing_loan == 'Yes')?'checked':'' }} > <span class="ml-2 text-purple">Yes</span></label>
                                <label class="ml-4"><input type="radio" name="existing_loan" value="No" {{ ($customer->existing_loan == 'No')?'checked':'' }} > <span class="ml-2 text-purple">No</span></label>
                            </div>
                            <div class="form-group">
                                <label class="d-block">Loan Amount</label> 
                                <input type="number" name="existing_loan_amount" class="form-control" value="{{ $customer->loan_amount }}" step="any">
                            </div>
                            <h4 class="title bg-gray text-white text-center py-2">NEXT OF KIN</h4>
                            <div class="form-group">
                                <label class="d-block">First Name</label> 
                                <input type="text" name="kin_fname" class="form-control" value="{{ $customer->next_kin_firstname }}" >
                            </div>
                            <div class="form-group">
                                <label class="d-block">Surname</label> 
                                <input type="text" name="kin_sname" class="form-control" value="{{ $customer->next_kin_lastname }}" >
                            </div>
                            <div class="form-group">
                                <label class="d-block">Relationship</label> 
                                <input type="text" name="kin_relationship" class="form-control" value="{{ $customer->next_kin_relationship }}" >
                            </div>
                            <div class="form-group">
                                <label class="d-block">Home Address</label> 
                                <input type="text" name="kin_address" class="form-control" value="{{ $customer->next_kin_address }}" >
                            </div>
                            <div class="form-group">
                                <label class="d-block">Mobile Number</label> 
                                <input type="text" name="kin_number" class="form-control" value="{{ $customer->next_kin_mobile }}" >
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <h4 class="title bg-gray text-white text-center py-2">MARITAL STATUS & DEPENDENTS</h4>
                            <div class="form-group">
                                <label><input type="radio" name="mstatus" value="Single" {{ ($customer->mstatus == 'Single')?'checked':'' }} > <span class="ml-2 text-purple">Single</span></label>
                                <label class="ml-4"><input type="radio" name="mstatus" value="Married" {{ ($customer->mstatus == 'Married')?'checked':'' }} > <span class="ml-2 text-purple">Married</span></label>
                                <label class="ml-4"><input type="radio" name="mstatus" value="Seperated" {{ ($customer->mstatus == 'Seperated')?'checked':'' }} > <span class="ml-2 text-purple">Seperated</span></label>
                                <label class="ml-4"><input type="radio" name="mstatus" value="Divorced" {{ ($customer->mstatus == 'Divorced')?'checked':'' }} > <span class="ml-2 text-purple">Divorced</span></label>
                                <label class="ml-4"><input type="radio" name="mstatus" value="Widowed" {{ ($customer->mstatus == 'Widowed')?'checked':'' }} > <span class="ml-2 text-purple">Widowed</span></label>
                            </div>
                            <div class="form-group">
                                <label class="d-block">Number Of Children</label> 
                                <label><input type="radio" name="no_children" value="0" {{ ($customer->no_of_children == '0')?'checked':'' }} > <span class="ml-2 text-purple">0</span></label>
                                <label class="ml-4"><input type="radio" name="no_children" value="1"{{ ($customer->no_of_children == '1')?'checked':'' }} > <span class="ml-2 text-purple">1</span></label>
                                <label class="ml-4"><input type="radio" name="no_children" value="2" {{ ($customer->no_of_children == '2')?'checked':'' }} > <span class="ml-2 text-purple">2</span></label>
                                <label class="ml-4"><input type="radio" name="no_children" value="3" {{ ($customer->no_of_children == '3')?'checked':'' }} > <span class="ml-2 text-purple">3</span></label>
                                <label class="ml-4"><input type="radio" name="no_children" value="4" {{ ($customer->no_of_children == '4')?'checked':'' }} > <span class="ml-2 text-purple">4</span></label>
                                <label class="ml-4"><input type="radio" name="no_children" value="5" {{ ($customer->no_of_children == '5')?'checked':'' }} > <span class="ml-2 text-purple">5</span></label>
                                <label class="ml-4"><input type="radio" name="no_children" value="6" {{ ($customer->no_of_children == '6')?'checked':'' }} > <span class="ml-2 text-purple">6</span></label>
                                <label class="ml-4"><input type="radio" name="no_children" value="7" {{ ($customer->no_of_children == '7')?'checked':'' }} > <span class="ml-2 text-purple">7</span></label>
                                <label class="ml-4"><input type="radio" name="no_children" value="8" {{ ($customer->no_of_children == '8')?'checked':'' }} > <span class="ml-2 text-purple">8</span></label>
                                <label class="ml-4"><input type="radio" name="no_children" value="9" {{ ($customer->no_of_children == '9')?'checked':'' }} > <span class="ml-2 text-purple">9</span></label>
                            </div>
                            <div class="form-group">
                                <label class="d-block">Number Of Dependants</label> 
                                <label><input type="radio" name="no_dependants" value="0" {{ ($customer->no_of_dependents == '0')?'checked':'' }} > <span class="ml-2 text-purple">0</span></label>
                                <label class="ml-4"><input type="radio" name="no_dependants" value="1" {{ ($customer->no_of_dependents == '1')?'checked':'' }} > <span class="ml-2 text-purple">1</span></label>
                                <label class="ml-4"><input type="radio" name="no_dependants" value="2" {{ ($customer->no_of_dependents == '2')?'checked':'' }} > <span class="ml-2 text-purple">2</span></label>
                                <label class="ml-4"><input type="radio" name="no_dependants" value="3" {{ ($customer->no_of_dependents == '3')?'checked':'' }} > <span class="ml-2 text-purple">3</span></label>
                                <label class="ml-4"><input type="radio" name="no_dependants" value="4" {{ ($customer->no_of_dependents == '4')?'checked':'' }} > <span class="ml-2 text-purple">4</span></label>
                                <label class="ml-4"><input type="radio" name="no_dependants" value="5" {{ ($customer->no_of_dependents == '5')?'checked':'' }} > <span class="ml-2 text-purple">5</span></label>
                                <label class="ml-4"><input type="radio" name="no_dependants" value="6" {{ ($customer->no_of_dependents == '6')?'checked':'' }} > <span class="ml-2 text-purple">6</span></label>
                                <label class="ml-4"><input type="radio" name="no_dependants" value="7" {{ ($customer->no_of_dependents == '7')?'checked':'' }} > <span class="ml-2 text-purple">7</span></label>
                                <label class="ml-4"><input type="radio" name="no_dependants" value="8" {{ ($customer->no_of_dependents == '8')?'checked':'' }} > <span class="ml-2 text-purple">8</span></label>
                                <label class="ml-4"><input type="radio" name="no_dependants" value="9" {{ ($customer->no_of_dependents == '9')?'checked':'' }} > <span class="ml-2 text-purple">9</span></label>
                            </div>
                            <h4 class="title bg-gray text-white text-center py-2">EMPLOYMENT STATUS</h4>
                            <div class="form-group">
                                <label><input type="radio" name="empStatus" value="Full Time" {{ ($customer->emp_status == 'Full Time')?'checked':'' }} > <span class="ml-2 text-purple">Full Time</span></label>
                                <label class="ml-4"><input type="radio" name="empStatus" value="Part Time" {{ ($customer->emp_status == 'Part Time')?'checked':'' }} > <span class="ml-2 text-purple">Part Time</span></label>
                                <label class="ml-4"><input type="radio" name="empStatus" value="Retired" {{ ($customer->emp_status == 'Retired')?'checked':'' }} > <span class="ml-2 text-purple">Retired</span></label>
                                <label class="ml-4"><input type="radio" name="empStatus" value="Self Employed" {{ ($customer->emp_status == 'Self Employed')?'checked':'' }} > <span class="ml-2 text-purple">Self Employed</span></label>
                                <label class="ml-4"><input type="radio" name="empStatus" value="Student" {{ ($customer->emp_status == 'Student')?'checked':'' }} > <span class="ml-2 text-purple">Student</span></label>
                                <label class="ml-4"><input type="radio" name="empStatus" value="Temp Contract" {{ ($customer->emp_status == 'Temp Contract')?'checked':'' }} > <span class="ml-2 text-purple">Temp Contract</span></label>
                                <label class="ml-4"><input type="radio" name="empStatus" value="Unemployed" {{ ($customer->emp_status == 'Unemployed')?'checked':'' }} > <span class="ml-2 text-purple">Unemployed</span></label>
                                <label class="ml-4"><input type="radio" name="empStatus" value="House Wife" {{ ($customer->emp_status == 'House Wife')?'checked':'' }} > <span class="ml-2 text-purple">House Wife</span></label>
                                <label class="ml-4"><input type="radio" name="empStatus" value="Outsourced/Contract" {{ ($customer->emp_status == 'Outsourced/Contract')?'checked':'' }} > <span class="ml-2 text-purple">Outsourced/Contract</span></label>
                                <label class="ml-4"><input type="radio" name="empStatus" value="Public" {{ ($customer->emp_status == 'Public')?'checked':'' }} > <span class="ml-2 text-purple">Public</span></label>
                                <label class="ml-4"><input type="radio" name="empStatus" value="Private" {{ ($customer->emp_status == 'Private')?'checked':'' }} > <span class="ml-2 text-purple">Private</span></label>
                            </div>
                            <div class="form-group">
                                <label class="d-block">Current Employer</label> 
                                <input type="text" name="employer" class="form-control" value="{{ $customer->current_employer }}" >
                            </div>
                            <div class="form-group">
                                <label class="d-block">Current Employer Address</label> 
                                <input type="text" name="employer_address" class="form-control" value="{{ $customer->current_employer_address }}" >
                            </div>
                            <div class="form-group">
                                <label class="d-block">Landmark/ Nearest Bus Stop</label> 
                                <input type="text" name="employer_landmark" class="form-control" value="{{ $customer->landmark_office }}" >
                            </div>
                            <div class="form-group">
                                <label class="d-block">LGA (of Office)</label> 
                                <input type="text" name="lga_office" class="form-control" value="{{ $customer->lga_office }}" >
                            </div>
                            <div class="form-group">
                                <label class="d-block">State (of Office)</label> 
                                <input type="text" name="state_office" class="form-control" value="{{ $customer->state_office }}" >
                            </div>
                            <div class="form-group">
                                <label class="d-block">Employer's Number</label> 
                                <input type="text" name="employer_number" class="form-control" value="{{ $customer->employer_number }}" >
                            </div>
                            <div class="form-group">
                                <label class="d-block">Employer's Work Email</label> 
                                <input type="text" name="employer_email" class="form-control" value="{{ $customer->work_email }}" >
                            </div>
                            <div class="form-group">
                                <label class="d-block">Staff ID Number</label> 
                                <input type="text" name="staff_id_number" class="form-control" value="{{ $customer->staff }}" >
                            </div>
                            <div class="form-group">
                                <label class="d-block">Pension Number</label> 
                                <input type="text" name="pension_number" class="form-control" value="{{ $customer->pension_number }}" >
                            </div>
                            <div class="form-group">
                                <label class="d-block">Tax Identification Number</label> 
                                <input type="text" name="tid" class="form-control" value="{{ $customer->tid }}" >
                            </div>
                            <div class="form-group">
                                <label class="d-block">Position / Job Title at Workplace</label> 
                                <input type="text" name="position" class="form-control" value="{{ $customer->position }}" >
                            </div>
                            <div class="form-group">
                                <label class="d-block">Dept. & Unit of Workplace</label> 
                                <input type="text" name="dept" class="form-control" value="{{ $customer->dept }}" >
                            </div>
                            <div class="form-group">
                                <label class="d-block">Date Employed</label> 
                                <input type="text" name="date_employed" class="form-control" value="{{ $customer->date_employed }}" >
                            </div>
                            <fieldset>
                                <legend>If present Employment is less than 1 year</legend>
                                <div class="form-group">
                                    <label class="d-block">Previous Employer</label> 
                                    <input type="text" name="prev_employer" class="form-control" value="{{ $customer->prev_employer }}" >
                                </div>
                                <div class="form-group">
                                    <label class="d-block">Previous Employer's Address</label> 
                                    <input type="text" name="prev_employer_address" class="form-control" value="{{ $customer->prev_employer_address }}" >
                                </div>
                                <div class="form-group">
                                    <label class="d-block">Number of months in previous Employment</label> 
                                    <input type="number" name="prev_employer_months" class="form-control"  value="{{ $customer->number_of_months_in_prev }}" >
                                </div>
                                <div class="form-group">
                                    <label class="d-block">How many Jobs have you had in the last 5 years?</label> 
                                    <input type="number" name="no_of_prev_jobs" class="form-control" value="{{ $customer->last_five_years_count }}" >
                                </div>
                            </fieldset>
                            <div class="form-group">
                                <label class="d-block">Current Net Monthly income</label> 
                                <input type="number" name="income" class="form-control" value="{{ $customer->net_monthly_income }}" step="any">
                            </div>
                            <div class="form-group">
                                <label class="d-block">Current Pay Date</label> 
                                <input type="text" name="pay_date" class="form-control" value="{{ $customer->pay_date }}" >
                            </div>
                            <h4 class="title bg-gray text-white text-center py-2">INDUSTRY</h4>
                            <div class="form-group">
                                <label><input type="radio" name="industry" value="Agriculture"  {{ ($customer->industry == 'Agriculture')?'checked':'' }} > <span class="ml-2 text-purple">Agriculture</span></label>
                                <label class="ml-4"><input type="radio" name="industry" value="Military" {{ ($customer->industry == 'Military')?'checked':'' }} > <span class="ml-2 text-purple">Military</span></label>
                                <label class="ml-4"><input type="radio" name="industry" value="Banking" {{ ($customer->industry == 'Banking')?'checked':'' }} > <span class="ml-2 text-purple">Banking</span></label>
                                <label class="ml-4"><input type="radio" name="industry" value="Construction/Engineering" {{ ($customer->industry == 'Construction/Engineering')?'checked':'' }} > <span class="ml-2 text-purple">Construction / Engineering</span></label>
                                <label class="ml-4"><input type="radio" name="industry" value="Real Estate" {{ ($customer->industry == 'Real Estate')?'checked':'' }} > <span class="ml-2 text-purple">Real Estate</span></label>
                                <label class="ml-4"><input type="radio" name="industry" value="Manufacturing" {{ ($customer->industry == 'Manufacturing')?'checked':'' }} > <span class="ml-2 text-purple">Manufacturing</span></label>
                                <label class="ml-4"><input type="radio" name="industry" value="Oil/gas" {{ ($customer->industry == 'Oil/gas')?'checked':'' }} > <span class="ml-2 text-purple">Oil/Gas</span></label>
                                <label class="ml-4"><input type="radio" name="industry" value="Retail/Sales" {{ ($customer->industry == 'Retail/Sales')?'checked':'' }} > <span class="ml-2 text-purple">Retail/Sales</span></label>
                                <label class="ml-4"><input type="radio" name="industry" value="Telecom" {{ ($customer->industry == 'Telecom')?'checked':'' }} > <span class="ml-2 text-purple">Telecom</span></label>
                                <label class="ml-4"><input type="radio" name="industry" value="Media/Entertainment" {{ ($customer->industry == 'Media/Entertainment')?'checked':'' }} > <span class="ml-2 text-purple">Media/Entertainment</span></label>
                                <label class="ml-4"><input type="radio" name="industry" value="Other Financial Institution" {{ ($customer->industry == 'Other Financial Institution')?'checked':'' }} > <span class="ml-2 text-purple">Other Financial Institution</span></label>
                                <label class="ml-4"><input type="radio" name="industry" value="Health/Education/Government" {{ ($customer->industry == 'Health/Education/Government')?'checked':'' }} > <span class="ml-2 text-purple">Health/Education/Government</span></label>
                                <label class="ml-4"><input type="radio" name="industry" value="Finance" {{ ($customer->industry == 'Finance')?'checked':'' }} > <span class="ml-2 text-purple">Finance</span></label>
                                <label class="ml-4"><input type="radio" name="industry" value="Power" {{ ($customer->industry == 'Power')?'checked':'' }} > <span class="ml-2 text-purple">Power</span></label>
                            </div>
                            <div class="form-group">
                                <label class="d-block">Service Sector</label> 
                                <input type="text" name="service_sector" class="form-control" value="{{ $customer->services_sector }}" >
                            </div>
                            <h4 class="title bg-gray text-white text-center py-2">LOAN DETAILS</h4>
                            <div class="form-group">
                                <label class="d-block">Loan Amount Requested</label> 
                                <input type="number" name="loan_amount" class="form-control" value="{{ $customer->loan_amount_requested }}" step="any">
                            </div>
                            <div class="form-group">
                                <label class="d-block">Loan Tenure (Months)</label> 
                                <select class="form-control" name="loan_tenure" >
                                <option value="1" {{ ($customer->loan_tenure == '1')?'selected':'' }}>1 Month</option>
                                <option value="2" {{ ($customer->loan_tenure == '2')?'selected':'' }}>2 Months</option>
                                <option value="3" {{ ($customer->loan_tenure == '3')?'selected':'' }}>3 Months</option>
                                <option value="4" {{ ($customer->loan_tenure == '4')?'selected':'' }}>4 Months</option>
                                <option value="5" {{ ($customer->loan_tenure == '5')?'selected':'' }}>5 Months</option>
                                <option value="6" {{ ($customer->loan_tenure == '6')?'selected':'' }}>6 Months</option>
                                <option value="7" {{ ($customer->loan_tenure == '7')?'selected':'' }}>7 Months</option>
                                <option value="8" {{ ($customer->loan_tenure == '8')?'selected':'' }}>8 Months</option>
                                <option value="9" {{ ($customer->loan_tenure == '9')?'selected':'' }}>9 Months</option>
                                <option value="10" {{ ($customer->loan_tenure == '10')?'selected':'' }}>10 Months</option>
                                <option value="11" {{ ($customer->loan_tenure == '11')?'selected':'' }}>11 Months</option>
                                <option value="12" {{ ($customer->loan_tenure == '12')?'selected':'' }}>12 Months</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="d-block">Affordable Monthly Repayments</label> 
                                <input type="text" name="affordable" class="form-control" value="{{ $customer->affordable_monthly }}" >
                            </div>
                            <h4 class="title bg-gray text-white text-center py-2">DISBURSEMENT DETAILS</h4>
                            <label class="text-danger">If your application is successful which Bank account would you like to receive your money?</label>
                            <div class="form-group">
                                <label class="d-block">Account Name</label> 
                                <input type="text" name="account_name" class="form-control" value="{{ $customer->account_name }}" >
                            </div>
                            <div class="form-group">
                                <label class="d-block">Account Number</label> 
                                <input type="text" name="account_number" class="form-control" value="{{ $customer->account_number }}" >
                            </div>
                            <div class="form-group">
                                <label class="d-block">Bank Name</label> 
                                <input type="text" name="bank_name" class="form-control" value="{{ $customer->bank_name }}" >
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label class="d-block">Branch</label> 
                                    <input type="text" name="branch" class="form-control" value="{{ $customer->bank_branch }}" >
                                </div>
                                <div class="form-group col-lg-6">
                                    <label class="d-block">Sort Code</label> 
                                    <input type="text" name="sort" class="form-control" value="{{ $customer->sort_code }}" >
                                </div>
                            </div>
                            <h4 class="title bg-gray text-white text-center py-2">HOW DID YOU HEAR ABOUT US?</h4>
                            <div class="form-group">
                                <label><input type="radio" name="poll" value="Leaflet" {{ ($customer->poll == 'Leaflet')?'checked':'' }} > <span class="ml-2 text-purple">Leaflet</span></label>
                                <label class="ml-4"><input type="radio" name="poll" value="Salesman" {{ ($customer->poll == 'Salesman')?'checked':'' }} > <span class="ml-2 text-purple">Salesman</span></label>
                                <label class="ml-4"><input type="radio" name="poll" value="Online (Social Media)" {{ ($customer->poll == 'Online (Social Media)')?'checked':'' }} > <span class="ml-2 text-purple">Online (Social Media)</span></label>
                                <label class="ml-4"><input type="radio" name="poll" value="Cinema" {{ ($customer->poll == 'Cinema')?'checked':'' }} > <span class="ml-2 text-purple">Cinema</span></label>
                                <label class="ml-4"><input type="radio" name="poll" value="Radio" {{ ($customer->poll == 'Radio')?'checked':'' }} > <span class="ml-2 text-purple">Radio</span></label>
                                <label class="ml-4"><input type="radio" name="poll" value="Friendly Referral" {{ ($customer->poll == 'Friendly Referral')?'checked':'' }} > <span class="ml-2 text-purple">Friendly Referral</span></label>
                                <label class="ml-4"><input type="radio" name="poll" value="Telesales" {{ ($customer->poll == 'Telesales')?'checked':'' }} > <span class="ml-2 text-purple">Telesales</span></label>
                                <label class="ml-4"><input type="radio" name="poll" value="SMS" {{ ($customer->poll == 'SMS')?'checked':'' }} > <span class="ml-2 text-purple">SMS</span></label>
                                <label class="ml-4"><input type="radio" name="poll" value="BRT" {{ ($customer->poll == 'BRT')?'checked':'' }} > <span class="ml-2 text-purple">BRT</span></label>
                                <label class="ml-4"><input type="radio" name="poll" value="Billboard" {{ ($customer->poll == 'Billboard')?'checked':'' }} > <span class="ml-2 text-purple">Billboard</span></label>
                            </div>
                            <div class="form-group">
                                <label class="d-block">Newspaper (Please specify)</label> 
                                <input type="text" name="poll_newspaper" class="form-control" value="{{ $customer->poll_newspaper }}" >
                            </div>
                            <div class="form-group">
                                <label class="d-block">Magazine (Please specify)</label> 
                                <input type="text" name="poll_magazine" class="form-control" value="{{ $customer->poll_magazine }}" >
                            </div>
                        </div>
                        <button class="btn btn-purple btn-block" type="submit">Edit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @else
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('addCustomer') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6 border-right border-gray">
                            <h4 class="title bg-gray text-white text-center py-2">PERSONAL DETAILS</h4>
                            <div class="form-group">
                                <label>Passport</label> 
                                <input type="file" name="passport" id="input-file-now" class="dropify" accept="image/*" required="">
                            </div>
                            <div class="form-group">
                                <label class="d-block">Title</label> 
                                <label><input type="radio" name="title" value="Mr" required=""> <span class="ml-2 text-purple">Mr</span></label>
                                <label class="ml-4"><input type="radio" name="title" value="Mrs" required=""> <span class="ml-2 text-purple">Mrs</span></label>
                                <label class="ml-4"><input type="radio" name="title" value="Miss" required=""> <span class="ml-2 text-purple">Miss</span></label>
                                <label class="ml-4"><input type="radio" name="title" value="Dr" required=""> <span class="ml-2 text-purple">Dr</span></label>
                                <label class="ml-4"><input type="radio" name="title" value="Chief" required=""> <span class="ml-2 text-purple">Chief</span></label>
                            </div>
                            <div class="form-group">
                                <label class="d-block">First Name</label> 
                                <input type="text" name="fname" class="form-control" required="">
                            </div>
                            <div class="form-group">
                                <label class="d-block">Middle Name</label> 
                                <input type="text" name="mname" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="d-block">Surname</label> 
                                <input type="text" name="sname" class="form-control" required="">
                            </div>
                            <div class="form-group">
                                <label class="d-block">Maiden name</label> 
                                <input type="text" name="maiden_name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="d-block">Bank Verification Number</label> 
                                <input type="text" name="bvn" class="form-control" required="">
                            </div>
                            <div class="form-group">
                                <label class="d-block">Date Of Birth</label> 
                                <input type="date" name="dob" class="form-control" required="">
                            </div>
                            <div class="form-group">
                                <label class="d-block">Gender</label> 
                                <label><input type="radio" name="gender" value="Male" required=""> <span class="ml-2 text-purple">Male</span></label>
                                <label class="ml-4"><input type="radio" name="gender" value="Female" required=""> <span class="ml-2 text-purple">Female</span></label>
                            </div>
                            <div class="form-group">
                                <label class="d-block">Means of Identification</label> 
                                <label><input type="radio" name="moi" value="International Passport" required=""> <span class="ml-2 text-purple">International Passport</span></label>
                                <label class="ml-4"><input type="radio" name="moi" value="Voter's Card" required=""> <span class="ml-2 text-purple">Voter's Card</span></label>
                                <label class="ml-4"><input type="radio" name="moi" value="National ID Card" required=""> <span class="ml-2 text-purple">National ID Card</span></label>
                                <label class="ml-4"><input type="radio" name="moi" value="Driver's License" required=""> <span class="ml-2 text-purple">Driver's License</span></label>
                                <label class="ml-4"><input type="radio" name="moi" value="Others" required=""> <span class="ml-2 text-purple">Others</span></label>
                            </div>
                            <div class="form-group">
                                <label class="d-block">Document Number</label> 
                                <input type="text" name="doc_number" class="form-control" >
                            </div>
                            <div class="form-group">
                                <label class="d-block">Document Issued Date</label> 
                                <input type="date" name="doc_issued_date" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="d-block">Document Expiry Date</label> 
                                <input type="date" name="doc_expiry_date" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="d-block">Phone Number</label> 
                                <input type="text" name="phone_no" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="d-block">Mobile Number</label> 
                                <input type="text" name="mobile_no" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="d-block">Office Number</label> 
                                <input type="text" name="office_no" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="d-block">Email Address</label> 
                                <input type="email" name="email" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="d-block">Official Email Address</label> 
                                <input type="email" name="official_email" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="d-block">Home Address</label> 
                                <input type="text" name="home_address" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="d-block">Landmark/Nearest Bus Stop</label> 
                                <input type="text" name="landmark" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="d-block">LGA (of Residence)</label> 
                                <input type="text" name="lga_residence" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="d-block">State</label> 
                                <input type="text" name="state_residence" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="d-block">Time at Current Address</label> 
                                <label><input type="radio" name="time_current_address" value="Years" required=""> <span class="ml-2 text-purple">Years</span></label>
                                <label class="ml-4"><input type="radio" name="time_current_address" value="Months" required=""> <span class="ml-2 text-purple">Months</span></label>
                            </div>
                            <div class="form-group">
                                <label class="d-block">Residential Status</label> 
                                <label><input type="radio" name="residential_status" value="Tenant" required=""> <span class="ml-2 text-purple">Tenant</span></label>
                                <label class="ml-4"><input type="radio" name="residential_status" value="Owner" required=""> <span class="ml-2 text-purple">Owner</span></label>
                                <label class="ml-4"><input type="radio" name="residential_status" value="With Relatives" required=""> <span class="ml-2 text-purple">With Relatives</span></label>
                                <label class="ml-4"><input type="radio" name="residential_status" value="With Parents" required=""> <span class="ml-2 text-purple">With Parents</span></label>
                            </div>
                            <div class="form-group">
                                <label class="d-block">Previous Address if resident at Current address is less than 3 years</label> 
                                <input type="text" name="prev_address" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="d-block">Time at Previous Address</label> 
                                <label><input type="radio" name="time_prev_address" value="Years"> <span class="ml-2 text-purple">Years</span></label>
                                <label class="ml-4"><input type="radio" name="time_current_address" value="Months"> <span class="ml-2 text-purple">Months</span></label>
                            </div>
                            <h4 class="title bg-gray text-white text-center py-2">EDUCATIONAL STATUS</h4>
                            <div class="form-group">
                                <label><input type="radio" name="eduStatus" value="Primary" required=""> <span class="ml-2 text-purple">Primary</span></label>
                                <label class="ml-4"><input type="radio" name="eduStatus" value="Secondary" required=""> <span class="ml-2 text-purple">Secondary</span></label>
                                <label class="ml-4"><input type="radio" name="eduStatus" value="Graduate" required=""> <span class="ml-2 text-purple">Graduate</span></label>
                                <label class="ml-4"><input type="radio" name="eduStatus" value="Post Graduate" required=""> <span class="ml-2 text-purple">Post Graduate</span></label>
                            </div>
                            <h4 class="title bg-gray text-white text-center py-2">PURPOSE OF LOAN</h4>
                            <div class="form-group">
                                <label><input type="radio" name="purpose" value="Portable Goods" required=""> <span class="ml-2 text-purple">Portable Goods</span></label>
                                <label class="ml-4"><input type="radio" name="purpose" value="Travel/Holiday" required=""> <span class="ml-2 text-purple">Travel/Holiday</span></label>
                                <label class="ml-4"><input type="radio" name="purpose" value="Medical" required=""> <span class="ml-2 text-purple">Medical</span></label>
                                <label class="ml-4"><input type="radio" name="purpose" value="House Maintenance" required=""> <span class="ml-2 text-purple">House Maintenance</span></label>
                                <label class="ml-4"><input type="radio" name="purpose" value="Rent" required=""> <span class="ml-2 text-purple">Rent</span></label>
                                <label class="ml-4"><input type="radio" name="purpose" value="School Fees" required=""> <span class="ml-2 text-purple">School Fees</span></label>
                                <label class="ml-4"><input type="radio" name="purpose" value="Wedding/Events" required=""> <span class="ml-2 text-purple">Wedding/Events</span></label>
                                <label class="ml-4"><input type="radio" name="purpose" value="Fashion Goods" required=""> <span class="ml-2 text-purple">Fashion Goods</span></label>
                            </div>
                            <div class="form-group">
                                <label class="d-block">Other Purpose (Please Specify)</label> 
                                <input type="text" name="other_purpose" class="form-control">
                            </div>
                            <h4 class="title bg-gray text-white text-center py-2">OTHER INFORMATION</h4>
                            <div class="form-group">
                                <label class="d-block">Do you have an existing Loan?</label>
                                <label><input type="radio" name="existing_loan" value="Yes" required=""> <span class="ml-2 text-purple">Yes</span></label>
                                <label class="ml-4"><input type="radio" name="existing_loan" value="No" required=""> <span class="ml-2 text-purple">No</span></label>
                            </div>
                            <div class="form-group">
                                <label class="d-block">Loan Amount</label> 
                                <input type="number" name="existing_loan_amount" class="form-control" step="any">
                            </div>
                            <h4 class="title bg-gray text-white text-center py-2">NEXT OF KIN</h4>
                            <div class="form-group">
                                <label class="d-block">First Name</label> 
                                <input type="text" name="kin_fname" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="d-block">Surname</label> 
                                <input type="text" name="kin_sname" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="d-block">Relationship</label> 
                                <input type="text" name="kin_relationship" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="d-block">Home Address</label> 
                                <input type="text" name="kin_address" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="d-block">Mobile Number</label> 
                                <input type="text" name="kin_number" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <h4 class="title bg-gray text-white text-center py-2">MARITAL STATUS & DEPENDENTS</h4>
                            <div class="form-group">
                                <label><input type="radio" name="mstatus" value="Single" required=""> <span class="ml-2 text-purple">Single</span></label>
                                <label class="ml-4"><input type="radio" name="mstatus" value="Married" required=""> <span class="ml-2 text-purple">Married</span></label>
                                <label class="ml-4"><input type="radio" name="mstatus" value="Seperated" required=""> <span class="ml-2 text-purple">Seperated</span></label>
                                <label class="ml-4"><input type="radio" name="mstatus" value="Divorced" required=""> <span class="ml-2 text-purple">Divorced</span></label>
                                <label class="ml-4"><input type="radio" name="mstatus" value="Widowed" required=""> <span class="ml-2 text-purple">Widowed</span></label>
                            </div>
                            <div class="form-group">
                                <label class="d-block">Number Of Children</label> 
                                <label><input type="radio" name="no_children" value="0" required=""> <span class="ml-2 text-purple">0</span></label>
                                <label class="ml-4"><input type="radio" name="no_children" value="1" required=""> <span class="ml-2 text-purple">1</span></label>
                                <label class="ml-4"><input type="radio" name="no_children" value="2" required=""> <span class="ml-2 text-purple">2</span></label>
                                <label class="ml-4"><input type="radio" name="no_children" value="3" required=""> <span class="ml-2 text-purple">3</span></label>
                                <label class="ml-4"><input type="radio" name="no_children" value="4" required=""> <span class="ml-2 text-purple">4</span></label>
                                <label class="ml-4"><input type="radio" name="no_children" value="5" required=""> <span class="ml-2 text-purple">5</span></label>
                                <label class="ml-4"><input type="radio" name="no_children" value="6" required=""> <span class="ml-2 text-purple">6</span></label>
                                <label class="ml-4"><input type="radio" name="no_children" value="7" required=""> <span class="ml-2 text-purple">7</span></label>
                                <label class="ml-4"><input type="radio" name="no_children" value="8" required=""> <span class="ml-2 text-purple">8</span></label>
                                <label class="ml-4"><input type="radio" name="no_children" value="9" required=""> <span class="ml-2 text-purple">9</span></label>
                            </div>
                            <div class="form-group">
                                <label class="d-block">Number Of Dependants</label> 
                                <label><input type="radio" name="no_dependants" value="0" required=""> <span class="ml-2 text-purple">0</span></label>
                                <label class="ml-4"><input type="radio" name="no_dependants" value="1" required=""> <span class="ml-2 text-purple">1</span></label>
                                <label class="ml-4"><input type="radio" name="no_dependants" value="2" required=""> <span class="ml-2 text-purple">2</span></label>
                                <label class="ml-4"><input type="radio" name="no_dependants" value="3" required=""> <span class="ml-2 text-purple">3</span></label>
                                <label class="ml-4"><input type="radio" name="no_dependants" value="4" required=""> <span class="ml-2 text-purple">4</span></label>
                                <label class="ml-4"><input type="radio" name="no_dependants" value="5" required=""> <span class="ml-2 text-purple">5</span></label>
                                <label class="ml-4"><input type="radio" name="no_dependants" value="6" required=""> <span class="ml-2 text-purple">6</span></label>
                                <label class="ml-4"><input type="radio" name="no_dependants" value="7" required=""> <span class="ml-2 text-purple">7</span></label>
                                <label class="ml-4"><input type="radio" name="no_dependants" value="8" required=""> <span class="ml-2 text-purple">8</span></label>
                                <label class="ml-4"><input type="radio" name="no_dependants" value="9" required=""> <span class="ml-2 text-purple">9</span></label>
                            </div>
                            <h4 class="title bg-gray text-white text-center py-2">EMPLOYMENT STATUS</h4>
                            <div class="form-group">
                                <label><input type="radio" name="empStatus" value="Full Time" required=""> <span class="ml-2 text-purple">Full Time</span></label>
                                <label class="ml-4"><input type="radio" name="empStatus" value="Part Time" required=""> <span class="ml-2 text-purple">Part Time</span></label>
                                <label class="ml-4"><input type="radio" name="empStatus" value="Retired" required=""> <span class="ml-2 text-purple">Retired</span></label>
                                <label class="ml-4"><input type="radio" name="empStatus" value="Self Employed" required=""> <span class="ml-2 text-purple">Self Employed</span></label>
                                <label class="ml-4"><input type="radio" name="empStatus" value="Student" required=""> <span class="ml-2 text-purple">Student</span></label>
                                <label class="ml-4"><input type="radio" name="empStatus" value="Temp Contract" required=""> <span class="ml-2 text-purple">Temp Contract</span></label>
                                <label class="ml-4"><input type="radio" name="empStatus" value="Unemployed" required=""> <span class="ml-2 text-purple">Unemployed</span></label>
                                <label class="ml-4"><input type="radio" name="empStatus" value="House Wife" required=""> <span class="ml-2 text-purple">House Wife</span></label>
                                <label class="ml-4"><input type="radio" name="empStatus" value="Outsourced/Contract" required=""> <span class="ml-2 text-purple">Outsourced/Contract</span></label>
                                <label class="ml-4"><input type="radio" name="empStatus" value="Public" required=""> <span class="ml-2 text-purple">Public</span></label>
                                <label class="ml-4"><input type="radio" name="empStatus" value="Private" required=""> <span class="ml-2 text-purple">Private</span></label>
                            </div>
                            <div class="form-group">
                                <label class="d-block">Current Employer</label> 
                                <input type="text" name="employer" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="d-block">Current Employer Address</label> 
                                <input type="text" name="employer_address" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="d-block">Landmark/ Nearest Bus Stop</label> 
                                <input type="text" name="employer_landmark" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="d-block">LGA (of Office)</label> 
                                <input type="text" name="lga_office" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="d-block">State (of Office)</label> 
                                <input type="text" name="state_office" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="d-block">Employer's Number</label> 
                                <input type="text" name="employer_number" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="d-block">Employer's Work Email</label> 
                                <input type="email" name="employer_email" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="d-block">Staff ID Number</label> 
                                <input type="text" name="staff_id_number" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="d-block">Pension Number</label> 
                                <input type="text" name="pension_number" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="d-block">Tax Identification Number</label> 
                                <input type="text" name="tid" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="d-block">Position / Job Title at Workplace</label> 
                                <input type="text" name="position" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="d-block">Dept. & Unit of Workplace</label> 
                                <input type="text" name="dept" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="d-block">Date Employed</label> 
                                <input type="date" name="date_employed" class="form-control">
                            </div>
                            <fieldset>
                                <legend>If present Employment is less than 1 year</legend>
                                <div class="form-group">
                                    <label class="d-block">Previous Employer</label> 
                                    <input type="text" name="prev_employer" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label class="d-block">Previous Employer's Address</label> 
                                    <input type="text" name="prev_employer_address" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label class="d-block">Number of months in previous Employment</label> 
                                    <input type="number" name="prev_employer_months" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label class="d-block">How many Jobs have you had in the last 5 years?</label> 
                                    <input type="number" name="no_of_prev_jobs" class="form-control">
                                </div>
                            </fieldset>
                            <div class="form-group">
                                <label class="d-block">Current Net Monthly income</label> 
                                <input type="number" name="income" class="form-control" step="any" required="">
                            </div>
                            <div class="form-group">
                                <label class="d-block">Current Pay Date</label> 
                                <input type="date" name="pay_date" required="" class="form-control">
                            </div>
                            <h4 class="title bg-gray text-white text-center py-2">INDUSTRY</h4>
                            <div class="form-group">
                                <label><input type="radio" name="industry" value="Agriculture" required=""> <span class="ml-2 text-purple">Agriculture</span></label>
                                <label class="ml-4"><input type="radio" name="industry" value="Military" required=""> <span class="ml-2 text-purple">Military</span></label>
                                <label class="ml-4"><input type="radio" name="industry" value="Banking" required=""> <span class="ml-2 text-purple">Banking</span></label>
                                <label class="ml-4"><input type="radio" name="industry" value="Construction/Engineering" required=""> <span class="ml-2 text-purple">Construction / Engineering</span></label>
                                <label class="ml-4"><input type="radio" name="industry" value="Real Estate" required=""> <span class="ml-2 text-purple">Real Estate</span></label>
                                <label class="ml-4"><input type="radio" name="industry" value="Manufacturing" required=""> <span class="ml-2 text-purple">Manufacturing</span></label>
                                <label class="ml-4"><input type="radio" name="industry" value="Oil/gas" required=""> <span class="ml-2 text-purple">Oil/Gas</span></label>
                                <label class="ml-4"><input type="radio" name="industry" value="Retail/Sales" required=""> <span class="ml-2 text-purple">Retail/Sales</span></label>
                                <label class="ml-4"><input type="radio" name="industry" value="Telecom" required=""> <span class="ml-2 text-purple">Telecom</span></label>
                                <label class="ml-4"><input type="radio" name="industry" value="Media/Entertainment" required=""> <span class="ml-2 text-purple">Media/Entertainment</span></label>
                                <label class="ml-4"><input type="radio" name="industry" value="Other Financial Institution" required=""> <span class="ml-2 text-purple">Other Financial Institution</span></label>
                                <label class="ml-4"><input type="radio" name="industry" value="Health/Education/Government" required=""> <span class="ml-2 text-purple">Health/Education/Government</span></label>
                                <label class="ml-4"><input type="radio" name="industry" value="Finance" required=""> <span class="ml-2 text-purple">Finance</span></label>
                                <label class="ml-4"><input type="radio" name="industry" value="Power" required=""> <span class="ml-2 text-purple">Power</span></label>
                            </div>
                            <div class="form-group">
                                <label class="d-block">Service Sector</label> 
                                <input type="text" name="service_sector" required="" class="form-control">
                            </div>
                            <h4 class="title bg-gray text-white text-center py-2">LOAN DETAILS</h4>
                            <div class="form-group">
                                <label class="d-block">Loan Amount Requested</label> 
                                <input type="number" name="loan_amount" required="" class="form-control" step="any">
                            </div>
                            <div class="form-group">
                                <label class="d-block">Loan Tenure (Months)</label> 
                                <select class="form-control" name="loan_tenure">
                                    <option value="1">1 Month</option>
                                    <option value="2">2 Months</option>
                                    <option value="3">3 Months</option>
                                    <option value="4">4 Months</option>
                                    <option value="5">5 Months</option>
                                    <option value="6">6 Months</option>
                                    <option value="7">7 Months</option>
                                    <option value="8">8 Months</option>
                                    <option value="9">9 Months</option>
                                    <option value="10">10 Months</option>
                                    <option value="11">11 Months</option>
                                    <option value="12">12 Months</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="d-block">Affordable Monthly Repayments</label> 
                                <input type="number" name="affordable" required="" class="form-control" step="any">
                            </div>
                            <h4 class="title bg-gray text-white text-center py-2">DISBURSEMENT DETAILS</h4>
                            <label class="text-danger">If your application is successful which Bank account would you like to receive your money?</label>
                            <div class="form-group">
                                <label class="d-block">Account Name</label> 
                                <input type="text" name="account_name" required="" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="d-block">Account Number</label> 
                                <input type="text" name="account_number" required="" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="d-block">Bank Name</label> 
                                <input type="text" name="bank_name" required="" class="form-control">
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label class="d-block">Branch</label> 
                                    <input type="text" name="branch" class="form-control">
                                </div>
                                <div class="form-group col-lg-6">
                                    <label class="d-block">Sort Code</label> 
                                    <input type="text" name="sort" class="form-control">
                                </div>
                            </div>
                            <h4 class="title bg-gray text-white text-center py-2">HOW DID YOU HEAR ABOUT US?</h4>
                            <div class="form-group">
                                <label><input type="radio" name="poll" value="Leaflet" required=""> <span class="ml-2 text-purple">Leaflet</span></label>
                                <label class="ml-4"><input type="radio" name="poll" value="Salesman" required=""> <span class="ml-2 text-purple">Salesman</span></label>
                                <label class="ml-4"><input type="radio" name="poll" value="Online (Social Media)" required=""> <span class="ml-2 text-purple">Online (Social Media)</span></label>
                                <label class="ml-4"><input type="radio" name="poll" value="Cinema" required=""> <span class="ml-2 text-purple">Cinema</span></label>
                                <label class="ml-4"><input type="radio" name="poll" value="Radio" required=""> <span class="ml-2 text-purple">Radio</span></label>
                                <label class="ml-4"><input type="radio" name="poll" value="Friendly Referral" required=""> <span class="ml-2 text-purple">Friendly Referral</span></label>
                                <label class="ml-4"><input type="radio" name="poll" value="Telesales" required=""> <span class="ml-2 text-purple">Telesales</span></label>
                                <label class="ml-4"><input type="radio" name="poll" value="SMS" required=""> <span class="ml-2 text-purple">SMS</span></label>
                                <label class="ml-4"><input type="radio" name="poll" value="BRT" required=""> <span class="ml-2 text-purple">BRT</span></label>
                                <label class="ml-4"><input type="radio" name="poll" value="Billboard" required=""> <span class="ml-2 text-purple">Billboard</span></label>
                            </div>
                            <div class="form-group">
                                <label class="d-block">Newspaper (Please specify)</label> 
                                <input type="text" name="poll_newspaper" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="d-block">Magazine (Please specify)</label> 
                                <input type="text" name="poll_magazine" class="form-control">
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-purple btn-block" type="submit">UPLOAD</button>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
<div class="modal fade" id="modalLoginForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
      <form action="{{ route('generateForm') }}" method="post">
          @csrf
        <div class="modal-content">
          <div class="modal-body mx-3">
            <div class="md-form mb-5">
              <div class="form-group">
                  <label>Emails seperated by comma</label>
                  <textarea class="form-control" name="emails"></textarea>
              </div>
              <div class="form-group">
                  <label>Do you want to send mails out?<input type="checkbox" name="send_mail" class="form-control" value="yes">Yes</label>
              </div>
            </div>
          </div>
          <div class="modal-footer d-flex justify-content-center">
            <button class="btn btn-default btn-block">Submit</button>
          </div>
        </div>
    </form>
  </div>
</div>
@endsection

@section('foot')
<script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/plugins/dropify/js/dropify.min.js') }}"></script>
<script src="{{ asset('assets/pages/jquery.form-upload.init.js') }}"></script>
@endsection