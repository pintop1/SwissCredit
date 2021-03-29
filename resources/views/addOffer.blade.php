@php
use App\Http\Controllers\Globals as Utils;
@endphp

@extends('layouts.app')

@if(isset($edit))
@section('title', __('Offers > Edit Offers || Swiss Credit Data Management system'))
@else
@section('title', __('Offers > Add Offer || Swiss Credit Data Management system'))
@endif

@section('forms', __('active'))
@section('aoffer', __('active'))

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
                    @if(isset($edit))
                    <li class="breadcrumb-item active">Edit Offers</li>
                    @else
                    <li class="breadcrumb-item active">Add Offers</li>
                    @endif
                </ol>
            </div>
            @if(isset($edit))
            <h4 class="page-title">Edit Offers</h4>
            @else
            <h4 class="page-title">Add Offers</h4>
            @endif
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    @if(isset($edit))
    <div class="col-lg-12">
        <div class="card">
            @if($offer->d_form != null)
            @php
            $form = Utils::getForm($offer->d_form);
            @endphp
            <h4 class="my-3 mx-3 header-title">EDIT OFFER FOR <q>{{ ucwords($form->firstname.' '.$form->surname) }}</q> </h4>
            <div class="card-body">
                <form action="{{ route('editOfferTwo') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="form-group col-lg-6">
                            <label class="d-block">Applicant's Name</label> 
                            <input type="text" name="name" class="form-control" value="{{ ucwords($offer->name) }}">
                            <input type="hidden" name="id" value="{{ $offer->id }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Residential Address</label> 
                            <input type="text" name="address" class="form-control" value="{{ $offer->address }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Employer</label> 
                            <input type="text" name="employer" class="form-control" value="{{ $offer->employer }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Date Employed</label> 
                            <input type="text" name="date_employed" class="form-control" value="{{ $offer->date_employed }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Position Held</label> 
                            <input type="text" name="position" class="form-control" value="{{ $offer->position }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Employment Status</label> 
                            <input type="text" name="empStatus" class="form-control" value="{{ $offer->empStatus }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Purpose</label> 
                            <input type="text" name="purpose" class="form-control" value="{{ $offer->purpose }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Amount Requested</label> 
                            <input type="number" name="amount_requested" class="form-control" value="{{ $offer->amount_requested }}" step="any">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Amount Recommended</label> 
                            <input type="number" name="amount_recommended" class="form-control" required="" step="any" value="{{ $offer->amount_recommended }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Outstanding Balance</label> 
                            <input type="number" name="outstanding_bal" class="form-control" required="" step="any" value="{{ $offer->outstanding_bal }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Moratorium</label> 
                            <input type="number" name="prorated" class="form-control" required="" value="{{ $offer->prorated }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Loan Tenor</label> 
                            <input type="number" name="loan_tenor" class="form-control" required="" value="{{ $offer->tenor }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Request Status</label> 
                            <input type="text" name="request_status" class="form-control" required="" value="{{ $offer->request_status }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Last Request</label> 
                            <input type="text" name="last_request" class="form-control" required="" value="{{ $offer->last_request }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Performance of Last Request</label> 
                            <input type="text" name="last_performance" class="form-control" required="" value="{{ $offer->performance_last_request }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Repayment Structure</label> 
                            <input type="text" name="repayment_structure" class="form-control" required="" value="{{ $offer->repayment_structure }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Repayment Source</label> 
                            <input type="text" name="repayment_source" class="form-control" required="" value="{{ $offer->repayment_source }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Interest Rate Monthly</label> 
                            <input type="number" name="interest" class="form-control" required="" step="any" value="{{ $offer->interest }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Processing Fee</label> 
                            <input type="number" name="processing_fee" class="form-control" required="" step="any" value="{{ $offer->processing_fee }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Management Fee</label> 
                            <input type="number" name="management_fee" class="form-control" required="" step="any" value="{{ $offer->management_fee }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Monthly Salary</label> 
                            <input type="number" name="salary" class="form-control" required="" step="any" value="{{ $offer->salary }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Loans/Other Obligations</label> 
                            <input type="text" name="other_loans" class="form-control" required="" value="{{ $offer->other_obligations }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Credit Check Report</label> 
                            <input type="text" name="credit_check" class="form-control" required="" value="{{ $offer->credit_check }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Collateral/Security</label> 
                            <input type="text" name="collateral" class="form-control" required="" value="{{ $offer->collateral }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Sourced By</label> 
                            <input type="text" name="sourced_by" class="form-control" required="" value="{{ $offer->sourced_by }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Account Officer</label> 
                            <input type="text" name="account_officer" class="form-control" value="{{ $offer->account_officer }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Client's Office Address</label> 
                            <input type="text" name="office_address" class="form-control" value="{{ $offer->office_address }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Statement of Account</label> 
                            <input type="text" name="account_statement" class="form-control" required="" value="{{ $offer->statement_of_ac }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Disbursement Account</label> 
                            <input type="text" name="disbursement" class="form-control" required="" value="{{ $offer->disbursement_account }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">File Code</label> 
                            <input type="text" name="file_code" class="form-control" required="" value="{{ $offer->file_code }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Repay Date</label> 
                            <input type="number" name="pay_date" class="form-control" required="" value="{{ $offer->pay_date }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Start Payment </label> 
                            <select class="form-control" name="pay_starts" required>
                                <option value="">Please select an option</option>
                                <option value="next" {{ ($offer->pay_starts == 'next')? 'selected':'' }} }}>Next Month</option>
                                <option value="this" {{ ($offer->pay_starts == 'this')? 'selected':'' }} }}>This Month</option>
                            </select>
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Default Charge</label> 
                            <input type="number" step="any" name="default_charge" class="form-control" required="" value="{{ $offer->default_charge }}">
                        </div>
                    </div>
                    <button class="btn btn-purple btn-block" type="submit">EDIT</button>
                </form>
            </div>
            @else
            <h4 class="my-3 mx-3 header-title">EDIT OFFER FOR <q>{{ ucwords($offer->name) }}</q> </h4>
            <div class="card-body">
                <form action="{{ route('editOffer') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="form-group col-lg-6">
                            <label class="d-block">Applicant's Name</label> 
                            <input type="text" name="name" class="form-control" value="{{ ucwords($offer->name) }}" required="">
                            <input type="hidden" name="id" value="{{ $offer->id }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">BVN</label> 
                            <input type="text" name="bvn" class="form-control" value="{{ ucwords($offer->bvn) }}" required="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Phone Number</label> 
                            <input type="text" name="phone_number" class="form-control" value="{{ ucwords($offer->phone_number) }}" required="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Customers Email</label> 
                            <input type="text" name="customers_email" class="form-control" value="{{ ucwords($offer->customers_email) }}" required="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Residential Address</label> 
                            <input type="text" name="address" class="form-control" value="{{ $offer->address }}" required="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Employer</label> 
                            <input type="text" name="employer" class="form-control" value="{{ $offer->employer }}" required="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Date Employed</label> 
                            <input type="text" name="date_employed" class="form-control" value="{{ $offer->date_employed }}" required="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Position Held</label> 
                            <input type="text" name="position" class="form-control" value="{{ $offer->position }}" required="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Employment Status</label> 
                            <input type="text" name="empStatus" class="form-control" value="{{ $offer->empStatus }}" required="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Purpose</label> 
                            <input type="text" name="purpose" class="form-control" value="{{ $offer->purpose }}" required="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Amount Requested</label> 
                            <input type="number" name="amount_requested" class="form-control" value="{{ $offer->amount_requested }}" required="" step="any">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Amount Recommended</label> 
                            <input type="number" name="amount_recommended" class="form-control" required="" step="any" value="{{ $offer->amount_recommended }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Outstanding Balance</label> 
                            <input type="number" name="outstanding_bal" class="form-control" required="" step="any" value="{{ $offer->outstanding_bal }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Moratorium</label> 
                            <input type="number" name="prorated" class="form-control" required="" value="{{ $offer->prorated }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Loan Tenor</label> 
                            <input type="number" name="loan_tenor" class="form-control" required="" value="{{ $offer->tenor }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Request Status</label> 
                            <input type="text" name="request_status" class="form-control" required="" value="{{ $offer->request_status }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Last Request</label> 
                            <input type="text" name="last_request" class="form-control" required="" value="{{ $offer->last_request }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Performance of Last Request</label> 
                            <input type="text" name="last_performance" class="form-control" required="" value="{{ $offer->performance_last_request }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Repayment Structure</label> 
                            <input type="text" name="repayment_structure" class="form-control" required="" value="{{ $offer->repayment_structure }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Repayment Source</label> 
                            <input type="text" name="repayment_source" class="form-control" required="" value="{{ $offer->repayment_source }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Interest Rate Monthly</label> 
                            <input type="number" name="interest" class="form-control" required="" step="any" value="{{ $offer->interest }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Processing Fee</label> 
                            <input type="number" name="processing_fee" class="form-control" required="" step="any" value="{{ $offer->processing_fee }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Management Fee</label> 
                            <input type="number" name="management_fee" class="form-control" required="" step="any" value="{{ $offer->management_fee }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Monthly Salary</label> 
                            <input type="number" name="salary" class="form-control" required="" step="any" value="{{ $offer->salary }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Loans/Other Obligations</label> 
                            <input type="text" name="other_loans" class="form-control" required="" value="{{ $offer->other_obligations }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Credit Check Report</label> 
                            <input type="text" name="credit_check" class="form-control" required="" value="{{ $offer->credit_check }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Collateral/Security</label> 
                            <input type="text" name="collateral" class="form-control" required="" value="{{ $offer->collateral }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Sourced By</label> 
                            <input type="text" name="sourced_by" class="form-control" required="" value="{{ $offer->sourced_by }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Account Officer</label> 
                            <input type="text" name="account_officer" class="form-control" value="{{ ucwords($offer->account_officer) }}" required="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Client's Office Address</label> 
                            <input type="text" name="office_address" class="form-control" value="{{ $offer->office_address }}" required="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Statement of Account</label> 
                            <input type="text" name="account_statement" class="form-control" required="" value="{{ $offer->statement_of_ac }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Disbursement Account</label> 
                            <input type="text" name="disbursement" class="form-control" required="" value="{{ $offer->disbursement_account }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">File Code</label> 
                            <input type="text" name="file_code" class="form-control" required="" value="{{ $offer->file_code }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Repay Date</label> 
                            <input type="number" name="pay_date" class="form-control" required="" value="{{ $offer->pay_date }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Start Payment </label> 
                            <select class="form-control" name="pay_starts" required>
                                <option value="">Please select an option</option>
                                <option value="next" {{ ($offer->pay_starts == 'next')? 'selected':'' }} }}>Next Month</option>
                                <option value="this" {{ ($offer->pay_starts == 'this')? 'selected':'' }} }}>This Month</option>
                            </select>
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Default Charge</label> 
                            <input type="number" step="any" name="default_charge" class="form-control" required="" value="{{ $offer->default_charge }}">
                        </div>
                    </div>
                    <button class="btn btn-purple btn-block" type="submit">EDIT</button>
                </form>
            </div>
            @endif
        </div>
    </div>
    @else
    <div class="col-lg-12">
        <div class="card">
            @if($id != null)
            @php
            $form = Utils::getForm($id);
            @endphp
            <h4 class="my-3 mx-3 header-title">OFFER CREATION FOR <q>{{ ucwords($form->firstname.' '.$form->surname) }}</q> </h4>
            <div class="card-body">
                <form action="{{ route('addOfferTwo') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="form-group col-lg-6">
                            <label class="d-block">Applicant's Name</label> 
                            <input type="text" name="name" class="form-control" value="{{ ucwords($form->firstname.' '.$form->middle_name.' '.$form->surname) }}">
                            <input type="hidden" name="id" value="{{ $id }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Residential Address</label> 
                            <input type="text" name="address" class="form-control" value="{{ $form->address }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Employer</label> 
                            <input type="text" name="employer" class="form-control" value="{{ $form->current_employer }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Date Employed</label> 
                            <input type="text" name="date_employed" class="form-control" value="{{ $form->date_employed }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Position Held</label> 
                            <input type="text" name="position" class="form-control" value="{{ $form->position }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Employment Status</label> 
                            <input type="text" name="empStatus" class="form-control" value="{{ $form->emp_status }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Purpose</label> 
                            <input type="text" name="purpose" class="form-control" value="{{ $form->purpose }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Amount Requested</label> 
                            <input type="number" name="amount_requested" class="form-control" value="{{ $form->loan_amount_requested }}" step="any">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Amount Recommended</label> 
                            <input type="number" name="amount_recommended" class="form-control" required="" step="any">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Outstanding Balance</label> 
                            <input type="number" name="outstanding_bal" class="form-control" required="" step="any" value="0">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Moratorium</label> 
                            <input type="number" name="prorated" class="form-control" required="" value="0">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Loan Tenor</label> 
                            <input type="number" name="loan_tenor" class="form-control" required="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Request Status</label> 
                            <input type="text" name="request_status" class="form-control" required="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Last Request</label> 
                            <input type="text" name="last_request" class="form-control" required="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Performance of Last Request</label> 
                            <input type="text" name="last_performance" class="form-control" required="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Repayment Structure</label> 
                            <input type="text" name="repayment_structure" class="form-control" required="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Repayment Source</label> 
                            <input type="text" name="repayment_source" class="form-control" required="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Interest Rate Monthly</label> 
                            <input type="number" name="interest" class="form-control" required="" step="any">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Processing Fee</label> 
                            <input type="number" name="processing_fee" class="form-control" required="" step="any">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Management Fee</label> 
                            <input type="number" name="management_fee" class="form-control" required="" step="any">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Monthly Salary</label> 
                            <input type="number" name="salary" class="form-control" required="" step="any">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Loans/Other Obligations</label> 
                            <input type="text" name="other_loans" class="form-control" required="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Credit Check Report</label> 
                            <input type="text" name="credit_check" class="form-control" required="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Collateral/Security</label> 
                            <input type="text" name="collateral" class="form-control" required="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Sourced By</label> 
                            <input type="text" name="sourced_by" class="form-control" required="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Account Officer</label> 
                            <input type="text" name="account_officer" class="form-control" value="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Client's Office Address</label> 
                            <input type="text" name="office_address" class="form-control" value="{{ $form->current_employer_address }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Statement of Account</label> 
                            <input type="text" name="account_statement" class="form-control" required="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Disbursement Account</label> 
                            <input type="text" name="disbursement" class="form-control" required="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">File Code</label> 
                            <input type="text" name="file_code" class="form-control" required="" value="{{ Utils::getSerial($form) }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Repay Date</label> 
                            <input type="number" name="pay_date" class="form-control" required="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Start Payment </label> 
                            <select class="form-control" name="pay_starts" required>
                                <option value="">Please select an option</option>
                                <option value="next">Next Month</option>
                                <option value="this">This Month</option>
                            </select>
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Default Charge</label> 
                            <input type="number" step="any" name="default_charge" class="form-control" required="">
                        </div>
                    </div>
                    <button class="btn btn-purple btn-block" type="submit">ADD</button>
                </form>
            </div>
            @else
            <h4 class="my-3 mx-3 header-title">OFFER CREATION</h4>
            <div class="card-body">
                <form action="{{ route('addOffer') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="form-group col-lg-6">
                            <label class="d-block">Applicant's Name</label> 
                            <input type="text" name="name" class="form-control" required="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">BVN</label> 
                            <input type="text" name="bvn" class="form-control" required="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Phone Number</label> 
                            <input type="text" name="phone_number" class="form-control" required="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Customers Email</label> 
                            <input type="text" name="customers_email" class="form-control" required="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Residential Address</label> 
                            <input type="text" name="address" class="form-control" required="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Employer</label> 
                            <input type="text" name="employer" class="form-control" required="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Date Employed</label> 
                            <input type="text" name="date_employed" class="form-control" required="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Position Held</label> 
                            <input type="text" name="position" class="form-control" required="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Employment Status</label> 
                            <input type="text" name="empStatus" class="form-control" required="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Purpose</label> 
                            <input type="text" name="purpose" class="form-control" required="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Amount Requested</label> 
                            <input type="number" name="amount_requested" class="form-control" required="" step="any">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Amount Recommended</label> 
                            <input type="number" name="amount_recommended" class="form-control" required="" step="any">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Outstanding Balance</label> 
                            <input type="number" name="outstanding_bal" class="form-control" required="" step="any" value="0">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Moratorium</label> 
                            <input type="number" name="prorated" class="form-control" required="" value="0">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Loan Tenor</label> 
                            <input type="number" name="loan_tenor" class="form-control" required="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Request Status</label> 
                            <input type="text" name="request_status" class="form-control" required="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Last Request</label> 
                            <input type="text" name="last_request" class="form-control" required="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Performance of Last Request</label> 
                            <input type="text" name="last_performance" class="form-control" required="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Repayment Structure</label> 
                            <input type="text" name="repayment_structure" class="form-control" required="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Repayment Source</label> 
                            <input type="text" name="repayment_source" class="form-control" required="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Interest Rate Monthly</label> 
                            <input type="number" name="interest" class="form-control" required="" step="any">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Processing Fee</label> 
                            <input type="number" name="processing_fee" class="form-control" required="" step="any">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Management Fee</label> 
                            <input type="number" name="management_fee" class="form-control" required="" step="any">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Monthly Salary</label> 
                            <input type="number" name="salary" class="form-control" required="" step="any">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Loans/Other Obligations</label> 
                            <input type="text" name="other_loans" class="form-control" required="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Credit Check Report</label> 
                            <input type="text" name="credit_check" class="form-control" required="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Collateral/Security</label> 
                            <input type="text" name="collateral" class="form-control" required="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Sourced By</label> 
                            <input type="text" name="sourced_by" class="form-control" required="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Account Officer</label> 
                            <input type="text" name="account_officer" class="form-control" required="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Client's Office Address</label> 
                            <input type="text" name="office_address" class="form-control" required="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Statement of Account</label> 
                            <input type="text" name="account_statement" class="form-control" required="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">File Code</label> 
                            <input type="text" name="file_code" class="form-control" required="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Repay Date</label> 
                            <input type="number" name="pay_date" class="form-control" required="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Disbursement Account</label> 
                            <input type="text" name="disbursement" class="form-control" required="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Start Payment </label> 
                            <select class="form-control" name="pay_starts" required>
                                <option value="">Please select an option</option>
                                <option value="next">Next Month</option>
                                <option value="this">This Month</option>
                            </select>
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="d-block">Default Charge</label> 
                            <input type="number" step="any" name="default_charge" class="form-control" required="">
                        </div>
                    </div>
                    <button class="btn btn-purple btn-block" type="submit">ADD</button>
                </form>
            </div>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection

@section('foot')
<script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/plugins/dropify/js/dropify.min.js') }}"></script>
<script src="{{ asset('assets/pages/jquery.form-upload.init.js') }}"></script>
@endsection