<!DOCTYPE html>
<html>
    <head>
      <style type="text/css">
            h4 {
                background-color: #222 !important;padding: 10px;text-align: center;color: #fff;
            }
            .block {
                display: block;
            }
            label {
                color: #444;
                padding: 5px 10px 5px 0;
                font-weight: bolder;
            }
            label input[type='radio'] {
                margin-top: 2px; 
                margin-left: 10px;
            }
            .form-group {
                margin: 10px 0;
                padding: 10px 0;
            }
            input[type='text']{
                border: 0;
                border-bottom: 1px solid #222;
                width: 240px;
                padding: 10px 5px;
                margin-top: 15px;
                margin-left: 12px;
                color: blue;
            }
            select{
                border: 0;
                border-bottom: 1px solid #222;
                width: 240px;
                padding: 10px 5px;
                margin-top: 15px;
                margin-left: 12px;
                color: blue;
            }
        </style>  
    </head>
    <body>
        <div style="width: 100%;font-family: sans-serif;">
            <div>
                <div style="display: inline-block;width: 50%">
                    <a href=""><img src="{{ env('APP_URL').'/'.$customer->passport }}" style="position: absolute; left: 0" width="160" height="160"></a>
                </div>
                <div style="display: inline-block;width: 50%">
                    <a href="https://swisscredit.ng" target="_blank"><img src="{{ env('APP_URL') }}/assets/images/logo-transparent.png" style="position: absolute; right: 0" width="160" height="50"></a>
                </div>
                <div style="width: 100%;margin-top: 200px">
                    <div>
                        <div>
                            <form>
                                <div style="100%">
                                    <div style="display: inline-block;width: 100%;">
                                        <h4>PERSONAL DETAILS</h4>
                                        <div class="form-group">
                                            <label class="block">Title</label> 
                                            <label><input type="radio" name="title" value="Mr" {{ ($customer->title == 'Mr')?'checked':'' }} disabled="disabled"> <span class="ml-2 text-purple">Mr</span></label>
                                            <label class="ml-4"><input type="radio" name="title" value="Mrs" {{ ($customer->title == 'Mrs')?'checked':'' }} disabled="disabled"> <span class="ml-2 text-purple">Mrs</span></label>
                                            <label class="ml-4"><input type="radio" name="title" value="Miss" {{ ($customer->title == 'Miss')?'checked':'' }} disabled="disabled"> <span class="ml-2 text-purple">Miss</span></label>
                                            <label class="ml-4"><input type="radio" name="title" value="Dr" {{ ($customer->title == 'Dr')?'checked':'' }} disabled="disabled"> <span class="ml-2 text-purple">Dr</span></label>
                                            <label class="ml-4"><input type="radio" name="title" value="Chief" {{ ($customer->title == 'Chief')?'checked':'' }} disabled="disabled"> <span class="ml-2 text-purple">Chief</span></label>
                                        </div>
                                        <div class="form-group">
                                            <label class="d-block">First Name</label> 
                                            <input type="text" name="fname" class="form-control" value="{{ $customer->firstname }}" readonly="">
                                        </div>
                                        <div class="form-group">
                                            <label class="d-block">Middle Name</label> 
                                            <input type="text" name="mname" class="form-control" value="{{ $customer->middle_name }}" readonly="">
                                        </div>
                                        <div class="form-group">
                                            <label class="d-block">Surname</label> 
                                            <input type="text" name="sname" class="form-control" value="{{ $customer->surname }}" readonly="">
                                        </div>
                                        <div class="form-group">
                                            <label class="d-block">Maiden name</label> 
                                            <input type="text" name="maiden_name" class="form-control" value="{{ $customer->maiden_name }}" readonly="">
                                        </div>
                                        <div class="form-group">
                                            <label class="d-block">Bank Verification Number</label> 
                                            <input type="text" name="bvn" class="form-control" value="{{ $customer->bvn }}" readonly="">
                                        </div>
                                        <div class="form-group">
                                            <label class="d-block">Date Of Birth</label> 
                                            <input type="text" name="dob" class="form-control" value="{{ $customer->date_of_birth }}" readonly="">
                                        </div>
                                        <div class="form-group">
                                            <label class="d-block">Gender</label> 
                                            <label><input type="radio" name="gender" value="Male" {{ ($customer->gender == 'Male')?'checked':'' }} disabled="disabled"> <span class="ml-2 text-purple">Male</span></label>
                                            <label class="ml-4"><input type="radio" name="title" value="Female" {{ ($customer->gender == 'Female')?'checked':'' }} disabled="disabled"> <span class="ml-2 text-purple">Female</span></label>
                                        </div>
                                        <div class="form-group">
                                            <label class="d-block">Means of Identification</label> 
                                            <label><input type="radio" name="moi" value="International Passport" {{ ($customer->identification == 'International Passport')?'checked':'' }} disabled="disabled"> <span class="ml-2 text-purple">International Passport</span></label>
                                            <label class="ml-4"><input type="radio" name="moi" value="Voter's Card" {{ ($customer->identification == 'Voter\'s Card')?'checked':'' }} disabled="disabled"> <span class="ml-2 text-purple">Voter's Card</span></label>
                                            <label class="ml-4"><input type="radio" name="moi" value="National ID Card" {{ ($customer->identification == 'National ID Card')?'checked':'' }} disabled="disabled"> <span class="ml-2 text-purple">National ID Card</span></label>
                                            <label class="ml-4"><input type="radio" name="moi" value="Driver's License"  {{ ($customer->identification == 'Driver\'s License')?'checked':'' }} disabled="disabled"> <span class="ml-2 text-purple">Driver's License</span></label>
                                            <label class="ml-4"><input type="radio" name="moi" value="Others" {{ ($customer->identification == 'Others')?'checked':'' }} disabled="disabled"> <span class="ml-2 text-purple">Others</span></label>
                                        </div>
                                        <div class="form-group">
                                            <label class="d-block">Document Number</label> 
                                            <input type="text" name="doc_number" class="form-control" value="{{ $customer->doc_number }}" readonly="">
                                        </div>
                                        <div class="form-group">
                                            <label class="d-block">Document Issued Date</label> 
                                            <input type="text" name="doc_issued_date" class="form-control" value="{{ $customer->issue_date }}" readonly="">
                                        </div>
                                        <div class="form-group">
                                            <label class="d-block">Document Expiry Date</label> 
                                            <input type="text" name="doc_expiry_date" class="form-control" value="{{ $customer->expiry_date }}" readonly="">
                                        </div>
                                        <div class="form-group">
                                            <label class="d-block">Phone Number</label> 
                                            <input type="text" name="phone_no" class="form-control" value="{{ $customer->phone_no }}" readonly="">
                                        </div>
                                        <div class="form-group">
                                            <label class="d-block">Mobile Number</label> 
                                            <input type="text" name="mobile_no" class="form-control" value="{{ $customer->mobile_no }}" readonly="">
                                        </div>
                                        <div class="form-group">
                                            <label class="d-block">Office Number</label> 
                                            <input type="text" name="office_no" class="form-control" value="{{ $customer->office_no }}" readonly="">
                                        </div>
                                        <div class="form-group">
                                            <label class="d-block">Email Address</label> 
                                            <input type="text" name="email" class="form-control"  value="{{ $customer->email }}" readonly="">
                                        </div>
                                        <div class="form-group">
                                            <label class="d-block">Official Email Address</label> 
                                            <input type="text" name="official_email" class="form-control" value="{{ $customer->official_email }}" readonly="">
                                        </div>
                                        <div class="form-group">
                                            <label class="d-block">Home Address</label> 
                                            <input type="text" name="home_address" class="form-control" value="{{ $customer->address }}" readonly="">
                                        </div>
                                        <div class="form-group">
                                            <label class="d-block">Landmark/Nearest Bus Stop</label> 
                                            <input type="text" name="landmark" class="form-control" value="{{ $customer->landmark }}" readonly="">
                                        </div>
                                        <div class="form-group">
                                            <label class="d-block">LGA (of Residence)</label> 
                                            <input type="text" name="lga_residence" class="form-control"  value="{{ $customer->lga_of_residence }}" readonly="">
                                        </div>
                                        <div class="form-group">
                                            <label class="d-block">State</label> 
                                            <input type="text" name="state_residence" class="form-control" value="{{ $customer->state }}" readonly="">
                                        </div>
                                        <h4 class="title bg-gray text-white text-center py-2">PURPOSE OF LOAN</h4>
                                        <div class="form-group">
                                            <label><input type="radio" name="purpose" value="Portable Goods" {{ ($customer->purpose == 'Portable Goods')?'checked':'' }} disabled="disabled"> <span class="ml-2 text-purple">Portable Goods</span></label>
                                            <label class="ml-4"><input type="radio" name="purpose" value="Travel/Holiday" {{ ($customer->purpose == 'Travel/Holiday')?'checked':'' }} disabled="disabled"> <span class="ml-2 text-purple">Travel/Holiday</span></label>
                                            <label class="ml-4"><input type="radio" name="purpose" value="Medical" {{ ($customer->purpose == 'Medical')?'checked':'' }} disabled="disabled"> <span class="ml-2 text-purple">Medical</span></label>
                                            <label class="ml-4"><input type="radio" name="purpose" value="House Maintenance" {{ ($customer->purpose == 'House Maintenance')?'checked':'' }} disabled="disabled"> <span class="ml-2 text-purple">House Maintenance</span></label>
                                            <label class="ml-4"><input type="radio" name="purpose" value="Rent" {{ ($customer->purpose == 'Rent')?'checked':'' }} disabled="disabled"> <span class="ml-2 text-purple">Rent</span></label>
                                            <label class="ml-4"><input type="radio" name="purpose" value="School Fees" {{ ($customer->purpose == 'School Fees')?'checked':'' }} disabled="disabled"> <span class="ml-2 text-purple">School Fees</span></label>
                                            <label class="ml-4"><input type="radio" name="purpose" value="Wedding/Events" {{ ($customer->purpose == 'Wedding/Events')?'checked':'' }} disabled="disabled"> <span class="ml-2 text-purple">Wedding/Events</span></label>
                                            <label class="ml-4"><input type="radio" name="purpose" value="Fashion Goods" {{ ($customer->purpose == 'Fashion Goods')?'checked':'' }} disabled="disabled"> <span class="ml-2 text-purple">Fashion Goods</span></label>
                                        </div>
                                        <div class="form-group">
                                            <label class="d-block">Other Purpose (Please Specify)</label> 
                                            <input type="text" name="other_purpose" class="form-control" value="{{ $customer->other_purpose }}" readonly="">
                                        </div>
                                        <h4 class="title bg-gray text-white text-center py-2">OTHER INFORMATION</h4>
                                        <div class="form-group">
                                            <label class="d-block">Do you have an existing Loan?</label>
                                            <label><input type="radio" name="existing_loan" value="Yes" {{ ($customer->existing_loan == 'Yes')?'checked':'' }} disabled="disabled"> <span class="ml-2 text-purple">Yes</span></label>
                                            <label class="ml-4"><input type="radio" name="existing_loan" value="No" {{ ($customer->existing_loan == 'No')?'checked':'' }} disabled="disabled"> <span class="ml-2 text-purple">No</span></label>
                                        </div>
                                        <div class="form-group">
                                            <label class="d-block">Loan Amount</label> 
                                            <input type="text" name="existing_loan_amount" class="form-control" value="N{{ number_format($customer->loan_amount,2) }}" readonly="">
                                        </div>
                                        <h4 class="title bg-gray text-white text-center py-2">NEXT OF KIN</h4>
                                        <div class="form-group">
                                            <label class="d-block">First Name</label> 
                                            <input type="text" name="kin_fname" class="form-control" value="{{ $customer->next_kin_firstname }}" readonly="">
                                        </div>
                                        <div class="form-group">
                                            <label class="d-block">Surname</label> 
                                            <input type="text" name="kin_sname" class="form-control" value="{{ $customer->next_kin_lastname }}" readonly="">
                                        </div>
                                        <div class="form-group">
                                            <label class="d-block">Relationship</label> 
                                            <input type="text" name="kin_relationship" class="form-control" value="{{ $customer->next_kin_relationship }}" readonly="">
                                        </div>
                                        <div class="form-group">
                                            <label class="d-block">Home Address</label> 
                                            <input type="text" name="kin_address" class="form-control" value="{{ $customer->next_kin_address }}" readonly="">
                                        </div>
                                        <div class="form-group">
                                            <label class="d-block">Mobile Number</label> 
                                            <input type="text" name="kin_number" class="form-control" value="{{ $customer->next_kin_mobile }}" readonly="">
                                        </div>
                                    </div>
                                    <div style="display: block;width: 100%;">
                                        <h4 class="title bg-gray text-white text-center py-2">MARITAL STATUS & DEPENDENTS</h4>
                                        <div class="form-group">
                                            <label><input type="radio" name="mstatus" value="Single" {{ ($customer->mstatus == 'Single')?'checked':'' }} disabled="disabled"> <span class="ml-2 text-purple">Single</span></label>
                                            <label class="ml-4"><input type="radio" name="mstatus" value="Married" {{ ($customer->mstatus == 'Married')?'checked':'' }} disabled="disabled"> <span class="ml-2 text-purple">Married</span></label>
                                            <label class="ml-4"><input type="radio" name="mstatus" value="Seperated" {{ ($customer->mstatus == 'Seperated')?'checked':'' }} disabled="disabled"> <span class="ml-2 text-purple">Seperated</span></label>
                                            <label class="ml-4"><input type="radio" name="mstatus" value="Divorced" {{ ($customer->mstatus == 'Divorced')?'checked':'' }} disabled="disabled"> <span class="ml-2 text-purple">Divorced</span></label>
                                            <label class="ml-4"><input type="radio" name="mstatus" value="Widowed" {{ ($customer->mstatus == 'Widowed')?'checked':'' }} disabled="disabled"> <span class="ml-2 text-purple">Widowed</span></label>
                                        </div>
                                        <h4 class="title bg-gray text-white text-center py-2">EMPLOYMENT STATUS</h4>
                                        <div class="form-group">
                                            <label><input type="radio" name="empStatus" value="Full Time" {{ ($customer->emp_status == 'Full Time')?'checked':'' }} disabled="disabled"> <span class="ml-2 text-purple">Full Time</span></label>
                                            <label class="ml-4"><input type="radio" name="empStatus" value="Part Time" {{ ($customer->emp_status == 'Part Time')?'checked':'' }} disabled="disabled"> <span class="ml-2 text-purple">Part Time</span></label>
                                            <label class="ml-4"><input type="radio" name="empStatus" value="Retired" {{ ($customer->emp_status == 'Retired')?'checked':'' }} disabled="disabled"> <span class="ml-2 text-purple">Retired</span></label>
                                            <label class="ml-4"><input type="radio" name="empStatus" value="Self Employed" {{ ($customer->emp_status == 'Self Employed')?'checked':'' }} disabled="disabled"> <span class="ml-2 text-purple">Self Employed</span></label>
                                            <label class="ml-4"><input type="radio" name="empStatus" value="Student" {{ ($customer->emp_status == 'Student')?'checked':'' }} disabled="disabled"> <span class="ml-2 text-purple">Student</span></label>
                                            <label class="ml-4"><input type="radio" name="empStatus" value="Temp Contract" {{ ($customer->emp_status == 'Temp Contract')?'checked':'' }} disabled="disabled"> <span class="ml-2 text-purple">Temp Contract</span></label>
                                            <label class="ml-4"><input type="radio" name="empStatus" value="Unemployed" {{ ($customer->emp_status == 'Unemployed')?'checked':'' }} disabled="disabled"> <span class="ml-2 text-purple">Unemployed</span></label>
                                            <label class="ml-4"><input type="radio" name="empStatus" value="House Wife" {{ ($customer->emp_status == 'House Wife')?'checked':'' }} disabled="disabled"> <span class="ml-2 text-purple">House Wife</span></label>
                                            <label class="ml-4"><input type="radio" name="empStatus" value="Outsourced/Contract" {{ ($customer->emp_status == 'Outsourced/Contract')?'checked':'' }} disabled="disabled"> <span class="ml-2 text-purple">Outsourced/Contract</span></label>
                                            <label class="ml-4"><input type="radio" name="empStatus" value="Public" {{ ($customer->emp_status == 'Public')?'checked':'' }} disabled="disabled"> <span class="ml-2 text-purple">Public</span></label>
                                            <label class="ml-4"><input type="radio" name="empStatus" value="Private" {{ ($customer->emp_status == 'Private')?'checked':'' }} disabled="disabled"> <span class="ml-2 text-purple">Private</span></label>
                                        </div>
                                        <div class="form-group">
                                            <label class="d-block">Current Employer</label> 
                                            <input type="text" name="employer" class="form-control" value="{{ $customer->current_employer }}" readonly="">
                                        </div>
                                        <div class="form-group">
                                            <label class="d-block">Current Employer Address</label> 
                                            <input type="text" name="employer_address" class="form-control" value="{{ $customer->current_employer_address }}" readonly="">
                                        </div>
                                        <div class="form-group">
                                            <label class="d-block">Landmark/ Nearest Bus Stop</label> 
                                            <input type="text" name="employer_landmark" class="form-control" value="{{ $customer->landmark_office }}" readonly="">
                                        </div>
                                        <div class="form-group">
                                            <label class="d-block">LGA (of Office)</label> 
                                            <input type="text" name="lga_office" class="form-control" value="{{ $customer->lga_office }}" readonly="">
                                        </div>
                                        <div class="form-group">
                                            <label class="d-block">State (of Office)</label> 
                                            <input type="text" name="state_office" class="form-control" value="{{ $customer->state_office }}" readonly="">
                                        </div>
                                        <div class="form-group">
                                            <label class="d-block">Employer's Number</label> 
                                            <input type="text" name="employer_number" class="form-control" value="{{ $customer->employer_number }}" readonly="">
                                        </div>
                                        <div class="form-group">
                                            <label class="d-block">Employer's Work Email</label> 
                                            <input type="text" name="employer_email" class="form-control" value="{{ $customer->work_email }}" readonly="">
                                        </div>
                                        <div class="form-group">
                                            <label class="d-block">Staff ID Number</label> 
                                            <input type="text" name="staff_id_number" class="form-control" value="{{ $customer->staff }}" readonly="">
                                        </div>
                                        <div class="form-group">
                                            <label class="d-block">Pension Number</label> 
                                            <input type="text" name="pension_number" class="form-control" value="{{ $customer->pension_number }}" readonly="">
                                        </div>
                                        <div class="form-group">
                                            <label class="d-block">Tax Identification Number</label> 
                                            <input type="text" name="tid" class="form-control" value="{{ $customer->tid }}" readonly="">
                                        </div>
                                        <div class="form-group">
                                            <label class="d-block">Position / Job Title at Workplace</label> 
                                            <input type="text" name="position" class="form-control" value="{{ $customer->position }}" readonly="">
                                        </div>
                                        <div class="form-group">
                                            <label class="d-block">Date Employed</label> 
                                            <input type="text" name="date_employed" class="form-control" value="{{ $customer->date_employed }}" readonly="">
                                        </div>
                                        <div class="form-group">
                                            <label class="d-block">Current Current Net Monthly income</label> 
                                            <input type="text" name="employer" class="form-control" value="N{{ number_format($customer->salary ?? 0.0,2) }}" readonly="">
                                        </div>
                                        <h4 class="title bg-gray text-white text-center py-2">LOAN DETAILS</h4>
                                        <div class="form-group">
                                            <label class="d-block">Loan Amount Requested</label> 
                                            <input type="text" name="loan_amount" class="form-control" value="N{{ number_format($customer->loan_amount_requested,2) }}" readonly="">
                                        </div>
                                        <div class="form-group">
                                            <label class="d-block">Loan Tenure (Months)</label> 
                                            <select class="form-control" name="loan_tenure" disabled="">
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
                                                <option value="13" {{ ($customer->loan_tenure == '13')?'selected':'' }}>13 Months</option>
                                                <option value="14" {{ ($customer->loan_tenure == '14')?'selected':'' }}>14 Months</option>
                                                <option value="15" {{ ($customer->loan_tenure == '15')?'selected':'' }}>15 Months</option>
                                                <option value="16" {{ ($customer->loan_tenure == '16')?'selected':'' }}>16 Months</option>
                                                <option value="17" {{ ($customer->loan_tenure == '17')?'selected':'' }}>17 Months</option>
                                                <option value="18" {{ ($customer->loan_tenure == '18')?'selected':'' }}>18 Months</option>
                                                <option value="19" {{ ($customer->loan_tenure == '19')?'selected':'' }}>19 Months</option>
                                                <option value="20" {{ ($customer->loan_tenure == '20')?'selected':'' }}>20 Months</option>
                                                <option value="21" {{ ($customer->loan_tenure == '21')?'selected':'' }}>21 Months</option>
                                                <option value="22" {{ ($customer->loan_tenure == '22')?'selected':'' }}>22 Months</option>
                                                <option value="23" {{ ($customer->loan_tenure == '23')?'selected':'' }}>23 Months</option>
                                                <option value="24" {{ ($customer->loan_tenure == '24')?'selected':'' }}>24 Months</option>
                                            </select>
                                        </div>
                                        <h4 class="title bg-gray text-white text-center py-2">HOW DID YOU HEAR ABOUT US?</h4>
                                        <div class="form-group">
                                            <label><input type="radio" name="poll" value="Leaflet" {{ ($customer->poll == 'Leaflet')?'checked':'' }} disabled="disabled"> <span class="ml-2 text-purple">Leaflet</span></label>
                                            <label class="ml-4"><input type="radio" name="poll" value="Salesman" {{ ($customer->poll == 'Salesman')?'checked':'' }} disabled="disabled"> <span class="ml-2 text-purple">Salesman</span></label>
                                            <label class="ml-4"><input type="radio" name="poll" value="Online (Social Media)" {{ ($customer->poll == 'Online (Social Media)')?'checked':'' }} disabled="disabled"> <span class="ml-2 text-purple">Online (Social Media)</span></label>
                                            <label class="ml-4"><input type="radio" name="poll" value="Cinema" {{ ($customer->poll == 'Cinema')?'checked':'' }} disabled="disabled"> <span class="ml-2 text-purple">Cinema</span></label>
                                            <label class="ml-4"><input type="radio" name="poll" value="Radio" {{ ($customer->poll == 'Radio')?'checked':'' }} disabled="disabled"> <span class="ml-2 text-purple">Radio</span></label>
                                            <label class="ml-4"><input type="radio" name="poll" value="Friendly Referral" {{ ($customer->poll == 'Friendly Referral')?'checked':'' }} disabled="disabled"> <span class="ml-2 text-purple">Friendly Referral</span></label>
                                            <label class="ml-4"><input type="radio" name="poll" value="Telesales" {{ ($customer->poll == 'Telesales')?'checked':'' }} disabled="disabled"> <span class="ml-2 text-purple">Telesales</span></label>
                                            <label class="ml-4"><input type="radio" name="poll" value="SMS" {{ ($customer->poll == 'SMS')?'checked':'' }} disabled="disabled"> <span class="ml-2 text-purple">SMS</span></label>
                                            <label class="ml-4"><input type="radio" name="poll" value="BRT" {{ ($customer->poll == 'BRT')?'checked':'' }} disabled="disabled"> <span class="ml-2 text-purple">BRT</span></label>
                                            <label class="ml-4"><input type="radio" name="poll" value="Billboard" {{ ($customer->poll == 'Billboard')?'checked':'' }} disabled="disabled"> <span class="ml-2 text-purple">Billboard</span></label>
                                        </div>
                                        <div class="form-group">
                                            <label class="d-block">Referral Code</label> 
                                            <input type="text" name="referral_code" class="form-control" value="{{ $customer->referral_code }}" readonly="">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>