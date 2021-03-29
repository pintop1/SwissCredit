<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $fillable = [
        'name', 'address', 'employer', 'date_employed', 'position', 'empStatus', 'category', 'purpose', 'amount_requested', 'amount_recommended', 'tenor', 'request_status', 'last_request', 'performance_last_request', 'repayment_structure', 'repayment_source', 'interest', 'prorated', 'processing_fee', 'management_fee', 'salary', 'other_obligations', 'credit_check', 'collateral', 'sourced_by', 'account_officer', 'office_address', 'statement_of_ac', 'staff', 'last_edit', 'disbursement_account', 'd_form', 'file_code', 'pay_date', 'status', 'bvn', 'phone_number', 'customers_email', 'pay_starts', 'default_charge',
    ];
}
