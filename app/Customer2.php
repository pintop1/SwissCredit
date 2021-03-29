<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer2 extends Model
{
    protected $fillable = [
        'reason', 'position', 'dept', 'date_employed', 'prev_employer', 'prev_employer_address', 'number_of_months_in_prev', 'last_five_years_count', 'net_monthly_income', 'pay_date', 'industry', 'services_sector', 'educational_status', 'purpose', 'other_purpose', 'existing_loan', 'loan_amount', 'next_kin_firstname', 'next_kin_lastname', 'next_kin_relationship', 'next_kin_address', 'next_kin_mobile', 'loan_amount_requested', 'loan_tenure', 'affordable_monthly', 'account_name', 'account_number', 'bank_name', 'bank_branch', 'sort_code', 'poll', 'poll_newspaper', 'poll_magazine', 'signature', 'date_signed', 'payment_by', 'account_officer', 'hos', 'risk', 'customer', 'operations', 'finance', 'internal_control', 'status', 'customer_id','declined_by', 'status_notice',
    ];
}
