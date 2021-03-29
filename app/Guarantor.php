<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Guarantor extends Model
{
    protected $fillable = [
        'code', 'passport', 'surname', 'firstname', 'middle_name', 'title', 'marital_status', 'gender', 'dob', 'nationality', 'employer_name', 'office_address', 'currently_guarantor', 'if_yes_name', 'residential_address', 'office_no', 'mobile_no', 'home_no', 'personal_email', 'official_email', 'relationship_with_applicant', 'position_held', 'branch_telephone', 'annual_income', 'did_provide_cheque', 'cheque_count', 'other_outstanding_swiss', 'other_outstanding_other', 'd_offer',
    ];
}
