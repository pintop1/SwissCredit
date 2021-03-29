<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'passport', 'title', 'firstname', 'middle_name', 'surname', 'maiden_name', 'bvn', 'date_of_birth', 'gender', 'identification', 'doc_number', 'issue_date', 'expiry_date', 'phone_no', 'mobile_no', 'office_no', 'email', 'official_email', 'address', 'landmark', 'lga_of_residence', 'state', 'time_at_address', 'residential_status', 'prev_address', 'time_prev', 'mstatus', 'no_of_children', 'no_of_dependents', 'emp_status', 'current_employer', 'current_employer_address', 'landmark_office', 'lga_office', 'state_office', 'employer_number', 'work_email', 'staff', 'pension_number', 'tid', 'by_customer', 'by_customer_hos', 'account_officer', 'status', 'declined_by', 'referral_code', 'type', 'folder', 'salary',
    ];
}
