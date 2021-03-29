<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Globals as Utils;
use PDF;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailerCustomer;
use Cookie;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class LandingController extends Controller
{

    public function getLgas($state){
        $token = csrf_token();
        $states = json_decode(file_get_contents(public_path('states_lgas.json'), true));
        $id = Utils::search_states($state, $states);
        $lgas = $states[$id]->state->locals;
        $data = '<option value="">--- Select an option ---</option>';
        $old_form_id = $_COOKIE[$token."_new_form_id"] ?? '';
        $customer = DB::table('customers')->where('id', $old_form_id)->first();
        foreach($lgas as $lga){
            if($customer != null){
                $data .= '<option';
                if($customer->lga_of_residence == $lga->name){
                    $data .= ' selected';
                }
                $data .= '>'.$lga->name.'</option>';
            }else {
                $data .= '<option>'.$lga->name.'</option>';
            }
        }
        echo $data;
    }

    public function getLgas2($state){
        $token = csrf_token();
        $states = json_decode(file_get_contents(asset('states_lgas.json'), true));
        $id = Utils::search_states($state, $states);
        $lgas = $states[$id]->state->locals;
        $data = '<option value="">--- Select an option ---</option>';
        $old_form_id = $_COOKIE[$token."_new_form_id"] ?? '';
        $customer = DB::table('customers')->where('id', $old_form_id)->first();
        foreach($lgas as $lga){
            if($customer != null){
                $data .= '<option';
                if($customer->lga_office == $lga->name){
                    $data .= ' selected';
                }
                $data .= '>'.$lga->name.'</option>';
            }else {
                $data .= '<option>'.$lga->name.'</option>';
            }
        }
        echo $data;
    }

    public function getLgas3($state){
        $token = csrf_token();
        $states = json_decode(file_get_contents(asset('states_lgas.json'), true));
        $id = Utils::search_states($state, $states);
        $lgas = $states[$id]->state->locals;
        $data = '<option value="">--- Select an option ---</option>';
        $old_form_id = $_COOKIE[$token."_renew_form_id"] ?? '';
        $customer = DB::table('customers')->where('id', $old_form_id)->first();
        foreach($lgas as $lga){
            if($customer != null){
                $data .= '<option';
                if($customer->lga_of_residence == $lga->name){
                    $data .= ' selected';
                }
                $data .= '>'.$lga->name.'</option>';
            }else {
                $data .= '<option>'.$lga->name.'</option>';
            }
        }
        echo $data;
    }

    public function getLgas4($state){
        $token = csrf_token();
        $states = json_decode(file_get_contents(asset('states_lgas.json'), true));
        $id = Utils::search_states($state, $states);
        $lgas = $states[$id]->state->locals;
        $data = '<option value="">--- Select an option ---</option>';
        $old_form_id = $_COOKIE[$token."_renew_form_id"] ?? '';
        $customer = DB::table('customers')->where('id', $old_form_id)->first();
        foreach($lgas as $lga){
            if($customer != null){
                $data .= '<option';
                if($customer->lga_office == $lga->name){
                    $data .= ' selected';
                }
                $data .= '>'.$lga->name.'</option>';
            }else {
                $data .= '<option>'.$lga->name.'</option>';
            }
        }
        echo $data;
    }

    public function getSub(){
        $token = csrf_token();
        $cache = $_COOKIE[$token] ?? '';
        if($cache == 'new loan'){
            setcookie($token."_new_form_stop", '', time() - 3600, "/");
            $states = json_decode(file_get_contents(public_path('states_lgas.json'), true));
            $old_form_id = $_COOKIE[$token."_new_form_id"] ?? '';
            $old_form_stops = $_COOKIE[$token."_new_form_stop"] ?? '';
            $customer = DB::table('customers')->where('id', $old_form_id)->first();
            if($old_form_stops == "one"){
                return view('forms.sub2_1', ['states'=>$states, 'customer'=>$customer]);
            }else if($old_form_stops == "two"){
                $customer2 = DB::table('customer2s')->where('customer_id', $customer->id)->first();
                return view('forms.sub2_2', ['states'=>$states, 'customer'=>$customer, 'customer2'=>$customer2]);
            }else if($old_form_stops == "three"){
                $customer2 = DB::table('customer2s')->where('customer_id', $customer->id)->first();
                return view('forms.sub2_3', ['states'=>$states, 'customer'=>$customer, 'customer2'=>$customer2]);
            }else if($old_form_stops == "four"){
                return view('forms.sub2_4', ['states'=>$states, 'customer'=>$customer]);
            }else if($old_form_stops == "five"){
                return view('forms.sub2_5', ['states'=>$states, 'customer'=>$customer]);
            }else {
                return view('forms.sub2', ['states'=>$states, 'customer'=>$customer]);
            }
        }else if($cache == 'top up / renewal') {
            setcookie($token."_renew_form_stop", '', time() -3600, "/");
            $states = json_decode(file_get_contents(public_path('states_lgas.json'), true));
            $old_form_id = $_COOKIE[$token."_renew_form_id"] ?? '';
            $old_form_stops = $_COOKIE[$token."_renew_form_stop"] ?? '';
            $customer = DB::table('customers')->where('id', $old_form_id)->first();
            $bvn = $_COOKIE[$token.'_bvn'] ?? '';
            if($old_form_stops == "one"){
                $customer2 = DB::table('customer2s')->where('customer_id', $customer->id)->first();
                return view('forms.sub3_1', ['states'=>$states, 'customer'=>$customer, 'customer2'=>$customer2]);
            }else if($old_form_stops == "two"){
                $customer2 = DB::table('customer2s')->where('customer_id', $customer->id)->first();
                return view('forms.sub3_2', ['states'=>$states, 'customer'=>$customer, 'customer2'=>$customer2]);
            }else {
                return view('forms.sub3', ['states'=>$states, 'customer'=>$customer, 'bvn'=>$bvn]);
            }
        }elseif($cache == 'nysc loan request'){
            setcookie($token."_new_nysc_form_stop", '', time() - 3600, "/");
            $states = json_decode(file_get_contents(public_path('states_lgas.json'), true));
            $old_form_id = $_COOKIE[$token."_new_nysc_form_id"] ?? '';
            $old_form_stops = $_COOKIE[$token."_new_nysc_form_stop"] ?? '';
            $customer = DB::table('customers')->where('id', $old_form_id)->first();
            if($old_form_stops == "one"){
                return view('forms.sub4_1', ['states'=>$states, 'customer'=>$customer]);
            }else if($old_form_stops == "two"){
                $customer2 = DB::table('customer2s')->where('customer_id', $customer->id)->first();
                return view('forms.sub4_2', ['states'=>$states, 'customer'=>$customer, 'customer2'=>$customer2]);
            }else if($old_form_stops == "three"){
                $customer2 = DB::table('customer2s')->where('customer_id', $customer->id)->first();
                return view('forms.sub4_3', ['states'=>$states, 'customer'=>$customer, 'customer2'=>$customer2]);
            }else if($old_form_stops == "four"){
                return view('forms.sub4_4', ['states'=>$states, 'customer'=>$customer]);
            }else if($old_form_stops == "five"){
                return view('forms.sub4_5', ['states'=>$states, 'customer'=>$customer]);
            }else {
                return view('forms.sub4', ['states'=>$states, 'customer'=>$customer]);
            }
        }else {
            return view('forms.sub1');
        }
    }

    public function processChoose(Request $req){
        $token = csrf_token();
        $type = urldecode($req->selection);
        if($type == 'top up / renewal'){
            $bvn = urldecode($req->user);
            $counts = DB::table('customers')->where('bvn', $bvn)->count();
            if($counts < 1){
                echo 'not exists';
            }else {
                setcookie($token, $type, time() + (86400), "/");
                setcookie($token.'_bvn', $bvn, time() + (86400), "/");
                echo 'successful';
            }
        }else {
            setcookie($token, $type, time() + (86400), "/");
            echo 'successs';
        }
    }

    public function getback(Request $req){
        $place = $req->place;
        $token = csrf_token();
        $cache = Cookie::get($token);
        if($cache == 'new loan'){
            setcookie($token."_new_form_stop", '', time() - 3600, "/");
            if($place == "zero"){
                setcookie($token, '', time() - 3600, "/");
                setcookie($token."_new_form_id", '', time() - 3600, "/");
                setcookie($token."_new_form_stop", '', time() - 3600, "/");
                echo 'success';
            }else if($place == "one"){
                setcookie($token."_new_form_stop", '', time() - 3600, "/");
                echo 'success';
            }else if($place == "two"){
                setcookie($token."_new_form_stop", "one", time() + (86400), "/");
                echo 'success';
            }else if($place == "three"){
                setcookie($token."_new_form_stop", "two", time() + (86400), "/");
                echo 'success';
            }else if($place == "four"){
                setcookie($token."_new_form_stop", "three", time() + (86400), "/");
                echo 'success';
            }else if($place == "five"){
                setcookie($token."_new_form_stop", "four", time() + (86400), "/");
                echo 'success';
            }
        }else {
            setcookie($token, '', time() - 3600, "/");
            setcookie($token.'_bvn', '', time() - 3600, "/");
            echo 'success';
        }

    }

    public function getback2(Request $req){
        $place = $req->place;
        $token = csrf_token();
        $cache = Cookie::get($token);
        if($cache == 'top up / renewal'){
            Cookie::queue(Cookie::forget($token."_renew_form_stop"));
            if($place == "zero"){
                setcookie($token, '', time() - 3600, "/");
                setcookie($token."_renew_form_stop", '', time() - 3600, "/");
                setcookie($token."_renew_form_id", '', time() - 3600, "/");
                echo 'success';
            }else if($place == "one"){
                setcookie($token."_renew_form_stop", '', time() - 3600, "/");
                echo 'success';
            }else if($place == "two"){
                setcookie($token."_new_form_stop", "one", time() + (86400), "/");
                echo 'success';
            }
        }else {
            setcookie($token, '', time() - 3600, "/");
            setcookie($token.'_bvn', '', time() - 3600, "/");
            echo 'success';
        }

    }

    public function form(Request $req){
        if($req->ref != '' && $req->ref != null){
            $agent = DB::connection('mysql2')->table('agents')->where('referral_code', $req->ref)->first();
            if($agent != null){
                $req->session()->put('swiss_club_agent', $agent->referral_code);
            }
            return view('forms.addforms');
        }else {
            return view('forms.addforms');
        }
        
    }

    public function sub1Post(Request $req){
        $token = csrf_token();
        $agent = $req->session()->get('swiss_club_agent', null);
        if(isset($req->id)){
            if ($files = $req->file('passport')) {
                $destinationPath = 'uploads/passports';
                $dimagename = $files->getClientOriginalName();
                $filename = pathinfo($dimagename, PATHINFO_FILENAME);
                $extension = pathinfo($dimagename, PATHINFO_EXTENSION);
                $dfile = uniqid().'_'.time().'_'.mt_rand(000000,999999).'_'.Utils::removeSpaces($filename).'.'.$extension;
                $files->move($destinationPath, $dfile);
                DB::table('customers')->where('id', $req->id)->update(['passport'=>$destinationPath.'/'.$dfile]);
            }
            DB::table('customers')->where('id', $req->id)->update([
                'title'=>$req->title,
                'firstname'=>$req->fname,
                'middle_name'=>$req->mname,
                'surname'=>$req->sname,
                'maiden_name'=>$req->maname,
                'bvn'=>$req->bvn,
                'date_of_birth'=>$req->dob,
                'gender'=>$req->gender,
                'identification'=>$req->means_of_id,
                'doc_number'=>$req->doc_number,
                'issue_date'=>$req->doc_issued_date,
                'expiry_date'=>$req->doc_expiry_date,
                'phone_no'=>$req->phone,
                'mobile_no'=>$req->mobile_number,
                'office_no'=>$req->office_number,
                'email'=>$req->email,
                'official_email'=>$req->official_email,
                'address'=>$req->address,
                'landmark'=>$req->landmark,
                'lga_of_residence'=>$req->lga,
                'state'=>$req->state,
                'by_customer'=>'yes',
                'updated_at'=>Carbon::now(),
            ]);
            setcookie($token."_new_form_stop", '', time() - 3600, "/");
            setcookie($token."_new_form_stop", "one", time() + (86400), "/");
            return redirect('/myForm');
        }else {
            $counts = Utils::getExist($req->bvn);
            if($counts > 0){
                return redirect('/myForm/status/errorswb4');
            }else{
                setcookie($token."_new_form_stop", '', time() - 3600, "/");
                setcookie($token."_new_form_id", '', time() - 3600, "/");
                if ($files = $req->file('passport')) {
                    $destinationPath = 'uploads/passports';
                    $dimagename = $files->getClientOriginalName();
                    $filename = pathinfo($dimagename, PATHINFO_FILENAME);
                    $extension = pathinfo($dimagename, PATHINFO_EXTENSION);
                    $dfile = uniqid().'_'.time().'_'.mt_rand(000000,999999).'_'.Utils::removeSpaces($filename).'.'.$extension;
                    $files->move($destinationPath, $dfile);
                    $customer = DB::table('customers')->insertGetId([
                        'passport'=>$destinationPath.'/'.$dfile,
                        'title'=>$req->title,
                        'firstname'=>$req->fname,
                        'middle_name'=>$req->mname,
                        'surname'=>$req->sname,
                        'maiden_name'=>$req->maname,
                        'bvn'=>$req->bvn,
                        'date_of_birth'=>$req->dob,
                        'gender'=>$req->gender,
                        'identification'=>$req->means_of_id,
                        'doc_number'=>$req->doc_number,
                        'issue_date'=>$req->doc_issued_date,
                        'expiry_date'=>$req->doc_expiry_date,
                        'phone_no'=>$req->phone,
                        'mobile_no'=>$req->mobile_number,
                        'office_no'=>$req->office_number,
                        'email'=>$req->email,
                        'official_email'=>$req->official_email,
                        'address'=>$req->address,
                        'landmark'=>$req->landmark,
                        'lga_of_residence'=>$req->lga,
                        'state'=>$req->state,
                        'by_customer'=>'yes',
                        'swiss_club_agent'=>$agent,
                        'created_at'=>Carbon::now(),
                        'updated_at'=>Carbon::now(),
                    ]);
                    $counts = DB::table('customer2s')->where('customer_id', $customer)->count();
                    if($counts < 1){
                        DB::table('customer2s')->insert([
                            'customer_id'=>$customer,
                            'updated_at'=>Carbon::now(),
                        ]);
                    }
                    $token = csrf_token();
                    setcookie($token."_new_form_id", $customer, time() + (86400), "/");
                    setcookie($token."_new_form_stop", "one", time() + (86400), "/");
                    return redirect('/myForm');
                }
            }
        }
    }

    public function sub4Post(Request $req){
        $token = csrf_token();
        $agent = $req->session()->get('swiss_club_agent', null);
        if(isset($req->id)){
            if ($files = $req->file('passport')) {
                $destinationPath = 'uploads/passports';
                $dimagename = $files->getClientOriginalName();
                $filename = pathinfo($dimagename, PATHINFO_FILENAME);
                $extension = pathinfo($dimagename, PATHINFO_EXTENSION);
                $dfile = uniqid().'_'.time().'_'.mt_rand(000000,999999).'_'.Utils::removeSpaces($filename).'.'.$extension;
                $files->move($destinationPath, $dfile);
                DB::table('customers')->where('id', $req->id)->update(['passport'=>$destinationPath.'/'.$dfile]);
            }
            DB::table('customers')->where('id', $req->id)->update([
                'title'=>$req->title,
                'firstname'=>$req->fname,
                'middle_name'=>$req->mname,
                'surname'=>$req->sname,
                'maiden_name'=>$req->maname,
                'bvn'=>$req->bvn,
                'date_of_birth'=>$req->dob,
                'gender'=>$req->gender,
                'identification'=>$req->means_of_id,
                'doc_number'=>$req->doc_number,
                'issue_date'=>$req->doc_issued_date,
                'expiry_date'=>$req->doc_expiry_date,
                'phone_no'=>$req->phone,
                'mobile_no'=>$req->mobile_number,
                'office_no'=>$req->office_number,
                'email'=>$req->email,
                'official_email'=>$req->official_email,
                'address'=>$req->address,
                'landmark'=>$req->landmark,
                'lga_of_residence'=>$req->lga,
                'state'=>$req->state,
                'by_customer'=>'yes',
                'updated_at'=>Carbon::now(),
                'type'=>'nysc',
            ]);
            setcookie($token."_new_nysc_form_stop", '', time() - 3600, "/");
            setcookie($token."_new_nysc_form_stop", "one", time() + (86400), "/");
            return redirect('/myForm');
        }else {
            $counts = Utils::getExist($req->bvn);
            if($counts > 0){
                return redirect('/myForm/status/errorswb4');
            }else{
                setcookie($token."_new_form_stop", '', time() - 3600, "/");
                setcookie($token."_new_form_id", '', time() - 3600, "/");
                if ($files = $req->file('passport')) {
                    $destinationPath = 'uploads/passports';
                    $dimagename = $files->getClientOriginalName();
                    $filename = pathinfo($dimagename, PATHINFO_FILENAME);
                    $extension = pathinfo($dimagename, PATHINFO_EXTENSION);
                    $dfile = uniqid().'_'.time().'_'.mt_rand(000000,999999).'_'.Utils::removeSpaces($filename).'.'.$extension;
                    $files->move($destinationPath, $dfile);
                    $customer = DB::table('customers')->insertGetId([
                        'passport'=>$destinationPath.'/'.$dfile,
                        'title'=>$req->title,
                        'firstname'=>$req->fname,
                        'middle_name'=>$req->mname,
                        'surname'=>$req->sname,
                        'maiden_name'=>$req->maname,
                        'bvn'=>$req->bvn,
                        'date_of_birth'=>$req->dob,
                        'gender'=>$req->gender,
                        'identification'=>$req->means_of_id,
                        'doc_number'=>$req->doc_number,
                        'issue_date'=>$req->doc_issued_date,
                        'expiry_date'=>$req->doc_expiry_date,
                        'phone_no'=>$req->phone,
                        'mobile_no'=>$req->mobile_number,
                        'office_no'=>$req->office_number,
                        'email'=>$req->email,
                        'official_email'=>$req->official_email,
                        'address'=>$req->address,
                        'landmark'=>$req->landmark,
                        'lga_of_residence'=>$req->lga,
                        'state'=>$req->state,
                        'by_customer'=>'yes',
                        'swiss_club_agent'=>$agent,
                        'created_at'=>Carbon::now(),
                        'updated_at'=>Carbon::now(),
                        'type'=>'nysc',
                    ]);
                    $counts = DB::table('customer2s')->where('customer_id', $customer)->count();
                    if($counts < 1){
                        DB::table('customer2s')->insert([
                            'customer_id'=>$customer,
                            'updated_at'=>Carbon::now(),
                        ]);
                    }
                    $token = csrf_token();
                    setcookie($token."_new_nysc_form_id", $customer, time() + (86400), "/");
                    setcookie($token."_new_nysc_form_stop", "one", time() + (86400), "/");
                    return redirect('/myForm');
                }
            }
        }
    }

    public function sub4_1Post(Request $req){
        $token = csrf_token();
        setcookie($token."_new_nysc_form_stop", '', time() - 3600, "/");
        DB::table('customers')->where('id', $req->id)->update(['mstatus'=>$req->mstatus]);
        setcookie($token."_new_nysc_form_stop", "two", time() + (86400), "/");
        return redirect('/myForm');
    }

    public function sub4_2Post(Request $req){
        $token = csrf_token();
        setcookie($token."_new_nysc_form_stop", '', time() - 3600, "/");
        $counts = DB::table('customer2s')->where('customer_id', $req->id)->count();
        if($counts > 0){
            DB::table('customer2s')->where('customer_id', $req->id)->update([
                'next_kin_firstname'=>$req->kin_fname,
                'next_kin_lastname'=>$req->kin_lname,
                'next_kin_relationship'=>$req->kin_relationship,
                'next_kin_address'=>$req->kin_address,
                'next_kin_mobile'=>$req->kin_mobile_number,
                'updated_at'=>Carbon::now(),
            ]);
        }else {
            $customer2 = DB::table('customer2s')->insertGetId([
                'next_kin_firstname'=>$req->kin_fname,
                'next_kin_lastname'=>$req->kin_lname,
                'next_kin_relationship'=>$req->kin_relationship,
                'next_kin_address'=>$req->kin_address,
                'next_kin_mobile'=>$req->kin_mobile_number,
                'customer_id'=>$req->id,
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now(),
            ]);
        }
        setcookie($token."_new_nysc_form_stop", "three", time() + (86400), "/");
        return redirect('/myForm');
    }

    public function sub4_3Post(Request $req){
        DB::disableQueryLog();
        $token = csrf_token();
        setcookie($token."_new_nysc_form_stop", '', time() - 3600, "/");
        $data = $req->except(['_token']);
        DB::table('customers')->where('id', $req->id)->update([
            'other_details'=>json_encode($data),
            'emp_status'=>$req->employment_status,
            'current_employer'=>$req->current_employer,
            'current_employer_address'=>$req->current_employer_address,
            'landmark_office'=>$req->current_employer_landmark,
            'lga_office'=>$req->current_employer_lga,
            'state_office'=>$req->current_employer_state,
            'employer_number'=>$req->current_employer_number,
            'work_email'=>$req->current_employer_work_email,
            'staff'=>$req->current_employer_staff_id,
            'pension_number'=>$req->current_employer_pension_number,
            'tid'=>$req->current_employer_tax_id_number,
            'salary'=>$req->salary,
            'updated_at'=>Carbon::now(),
        ]);
        setcookie($token."_new_nysc_form_stop", "four", time() + (86400), "/");
        return redirect('/myForm');
    }

    public function sub4_4Post(Request $req){
        $token = csrf_token();
        $agent = $req->session()->get('swiss_club_agent', null);
        $agentData = DB::connection('mysql2')->table('agents')->where('referral_code', $agent)->first();
        DB::table('customer2s')->where('customer_id', $req->id)->update([
            'purpose'=>$req->purpose,
            'other_purpose'=>$req->other_purpose,
            'existing_loan'=>$req->existing_loan,
            'loan_amount'=>$req->existing_loan_amount,
            'loan_amount_requested'=>$req->amount_requested,
            'loan_tenure'=>$req->tenure,
            'poll'=>$req->hear_us,
            'updated_at'=>Carbon::now(),
        ]);
        DB::table('customers')->where('id', $req->id)->update([
            'referral_code'=>$req->referral_code,
        ]);
        $customer = DB::table('customers')->where('id', $req->id)->first();
        $name = $customer->firstname.' '.$customer->middle_name.' '.$customer->surname;
        $message = 'Your loan application has been received. Kindly send the available documents to risk@swisscredit.ng<br>Loan Requirements Include;<ul><li>Recent Passport Photograph</li><li>Clear Picture of work ID Card</li><li>Utility Bill</li><li>Social media handle.</li><li>Letter of Employment /Letter of Confirmation</li><li>Valid means of ID.</li><li>Picture of Office Building ( Picture of Gate and Office Building in one Photo)</li></ul><p>Kindly notify your bank to send your <b>Statement of Account (Six Months till Date)</b> to statements@swisscredit.ng.<br>';
        $subject = 'Loan Request Received';
        $email = $customer->email;
        Mail::to($email)->send(new SendMailerCustomer($name, $message, $subject));
        Mail::to($customer->official_email)->send(new SendMailerCustomer($name, $message, $subject));
        if($agent != null && $agentData != null){
            $message = 'A customer just successfully applied using your referral link.';
            $subject = 'Loan Application';
            Mail::to($agentData->email)->send(new SendMailerCustomer($agentData->name, $message, $subject));
            $req->session()->forget('swiss_club_agent');
        }
        return redirect('/myForm/status/success');
    }

    public function sub1_1Post(Request $req){
        $token = csrf_token();
        setcookie($token."_new_form_stop", '', time() - 3600, "/");
        DB::table('customers')->where('id', $req->id)->update(['mstatus'=>$req->mstatus]);
        setcookie($token."_new_form_stop", "two", time() + (86400), "/");
        return redirect('/myForm');
    }

    public function sub1_2Post(Request $req){
        $token = csrf_token();
        setcookie($token."_new_form_stop", '', time() - 3600, "/");
        $counts = DB::table('customer2s')->where('customer_id', $req->id)->count();
        if($counts > 0){
            DB::table('customer2s')->where('customer_id', $req->id)->update([
                'next_kin_firstname'=>$req->kin_fname,
                'next_kin_lastname'=>$req->kin_lname,
                'next_kin_relationship'=>$req->kin_relationship,
                'next_kin_address'=>$req->kin_address,
                'next_kin_mobile'=>$req->kin_mobile_number,
                'updated_at'=>Carbon::now(),
            ]);
        }else {
            $customer2 = DB::table('customer2s')->insertGetId([
                'next_kin_firstname'=>$req->kin_fname,
                'next_kin_lastname'=>$req->kin_lname,
                'next_kin_relationship'=>$req->kin_relationship,
                'next_kin_address'=>$req->kin_address,
                'next_kin_mobile'=>$req->kin_mobile_number,
                'customer_id'=>$req->id,
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now(),
            ]);
        }
        setcookie($token."_new_form_stop", "three", time() + (86400), "/");
        return redirect('/myForm');
    }

    public function sub1_3Post(Request $req){
        DB::disableQueryLog();
        $token = csrf_token();
        setcookie($token."_new_form_stop", '', time() - 3600, "/");
        DB::table('customers')->where('id', $req->id)->update([
            'emp_status'=>$req->employment_status,
            'current_employer'=>$req->current_employer,
            'current_employer_address'=>$req->current_employer_address,
            'landmark_office'=>$req->current_employer_landmark,
            'lga_office'=>$req->current_employer_lga,
            'state_office'=>$req->current_employer_state,
            'employer_number'=>$req->current_employer_number,
            'work_email'=>$req->current_employer_work_email,
            'staff'=>$req->current_employer_staff_id,
            'pension_number'=>$req->current_employer_pension_number,
            'tid'=>$req->current_employer_tax_id_number,
            'salary'=>$req->salary,
            'updated_at'=>Carbon::now(),
        ]);
        DB::table('customer2s')->where('customer_id', $req->id)->update([
            'position'=>$req->current_employer_position,
            'date_employed'=>$req->date_employed,
            'updated_at'=>Carbon::now(),
        ]);
        setcookie($token."_new_form_stop", "four", time() + (86400), "/");
        return redirect('/myForm');
    }

    public function sub1_4Post(Request $req){
        $token = csrf_token();
        $agent = $req->session()->get('swiss_club_agent', null);
        $agentData = DB::connection('mysql2')->table('agents')->where('referral_code', $agent)->first();
        DB::table('customer2s')->where('customer_id', $req->id)->update([
            'purpose'=>$req->purpose,
            'other_purpose'=>$req->other_purpose,
            'existing_loan'=>$req->existing_loan,
            'loan_amount'=>$req->existing_loan_amount,
            'loan_amount_requested'=>$req->amount_requested,
            'loan_tenure'=>$req->tenure,
            'poll'=>$req->hear_us,
            'updated_at'=>Carbon::now(),
        ]);
        DB::table('customers')->where('id', $req->id)->update([
            'referral_code'=>$req->referral_code,
        ]);
        $customer = DB::table('customers')->where('id', $req->id)->first();
        $name = $customer->firstname.' '.$customer->middle_name.' '.$customer->surname;
        $message = 'Your loan application has been received. Kindly send the available documents to risk@swisscredit.ng<br>Loan Requirements Include;<ul><li>Recent Passport Photograph</li><li>Clear Picture of work ID Card</li><li>Utility Bill</li><li>Social media handle.</li><li>Letter of Employment /Letter of Confirmation</li><li>Valid means of ID.</li><li>Picture of Office Building ( Picture of Gate and Office Building in one Photo)</li></ul><p>Kindly notify your bank to send your <b>Statement of Account (Six Months till Date)</b> to statements@swisscredit.ng.<br>';
        $subject = 'Loan Request Received';
        $email = $customer->email;
        Mail::to($email)->send(new SendMailerCustomer($name, $message, $subject));
        Mail::to($customer->official_email)->send(new SendMailerCustomer($name, $message, $subject));
        if($agent != null && $agentData != null){
            $message = 'A customer just successfully applied using your referral link.';
            $subject = 'Loan Application';
            Mail::to($agentData->email)->send(new SendMailerCustomer($agentData->name, $message, $subject));
            $req->session()->forget('swiss_club_agent');
        }
        return redirect('/myForm/status/success');
    }

    public function sub2Post(Request $req){
        $token = csrf_token();
        $agent = $req->session()->get('swiss_club_agent', null);
        if(isset($req->id)){
            if ($files = $req->file('passport')) {
                $destinationPath = 'uploads/passports';
                $dimagename = $files->getClientOriginalName();
                $filename = pathinfo($dimagename, PATHINFO_FILENAME);
                $extension = pathinfo($dimagename, PATHINFO_EXTENSION);
                $dfile = uniqid().'_'.time().'_'.mt_rand(000000,999999).'_'.Utils::removeSpaces($filename).'.'.$extension;
                $files->move($destinationPath, $dfile);
                DB::table('customers')->where('id', $req->id)->update(['passport'=>$destinationPath.'/'.$dfile, 'updated_at'=>Carbon::now()]);
            }
            DB::table('customers')->where('id', $req->id)->update([
                'title'=>$req->title,
                'firstname'=>$req->fname,
                'middle_name'=>$req->mname,
                'surname'=>$req->sname,
                'bvn'=>$req->bvn,
                'phone_no'=>$req->phone,
                'email'=>$req->email,
                'address'=>$req->address,
                'landmark'=>$req->landmark,
                'lga_of_residence'=>$req->lga,
                'state'=>$req->state,
                'by_customer'=>'yes',
                'updated_at'=>Carbon::now()
            ]);
            setcookie($token."_renew_form_stop", '', time() - 3600, "/");
            setcookie($token."_renew_form_stop", "one", time() + (86400), "/");
            return redirect('/myForm');
        }else {
            $counts = Utils::getExist($req->bvn);
            if($counts > 0){
                return redirect('/myForm/status/errorswb4');
            }else{
                setcookie($token."_renew_form_id", '', time() - 3600, "/");
                setcookie($token."_renew_form_stop", '', time() - 3600, "/");
                if ($files = $req->file('passport')) {
                    $destinationPath = 'uploads/passports';
                    $dimagename = $files->getClientOriginalName();
                    $filename = pathinfo($dimagename, PATHINFO_FILENAME);
                    $extension = pathinfo($dimagename, PATHINFO_EXTENSION);
                    $dfile = uniqid().'_'.time().'_'.mt_rand(000000,999999).'_'.Utils::removeSpaces($filename).'.'.$extension;
                    $files->move($destinationPath, $dfile);
                    $customer = DB::table('customers')->insertGetId([
                        'passport'=>$destinationPath.'/'.$dfile,
                        'title'=>$req->title,
                        'firstname'=>$req->fname,
                        'middle_name'=>$req->mname,
                        'surname'=>$req->sname,
                        'bvn'=>$req->bvn,
                        'phone_no'=>$req->phone,
                        'email'=>$req->email,
                        'address'=>$req->address,
                        'landmark'=>$req->landmark,
                        'lga_of_residence'=>$req->lga,
                        'state'=>$req->state,
                        'by_customer'=>'yes',
                        'swiss_club_agent'=>$agent,
                        'created_at'=>Carbon::now(),
                        'updated_at'=>Carbon::now(),
                    ]);
                    if($counts < 1){
                        DB::table('customer2s')->insert([
                            'customer_id'=>$customer,
                        ]);
                    }
                    $token = csrf_token();
                    setcookie($token."_renew_form_id", $customer, time() + (86400), "/");
                    setcookie($token."_renew_form_stop", "one", time() + (86400), "/");
                    return redirect('/myForm');
                }
            }
        }
    }

    public function sub2_1Post(Request $req){
        $token = csrf_token();
        setcookie($token."_renew_form_stop", '', time() - 3600, "/");
        DB::table('customers')->where('id', $req->id)->update([
            'emp_status'=>$req->employment_status,
            'current_employer'=>$req->current_employer,
            'current_employer_address'=>$req->current_employer_address,
            'landmark_office'=>$req->current_employer_landmark,
            'lga_office'=>$req->current_employer_lga,
            'state_office'=>$req->current_employer_state,
            'work_email'=>$req->current_employer_work_email,
            'salary'=>$req->salary,
            'updated_at'=>Carbon::now(),
        ]);
        $counts = DB::table('customer2s')->where('customer_id', $req->id)->count();
        if($counts < 1){
            DB::table('customer2s')->insert([
                'customer_id'=>$req->id,
            ]);
        }
        setcookie($token."_renew_form_stop", "two", time() + (86400), "/");
        return redirect('/myForm');
    }

    public function sub2_2Post(Request $req){
        $token = csrf_token();
        $agent = $req->session()->get('swiss_club_agent', null);
        $agentData = DB::connection('mysql2')->table('agents')->where('referral_code', $agent)->first();
        DB::table('customer2s')->where('customer_id', $req->id)->update([
            'purpose'=>$req->purpose,
            'loan_amount'=>$req->existing_loan_amount,
            'loan_amount_requested'=>$req->amount_requested,
            'loan_tenure'=>$req->tenure,
            'updated_at'=>Carbon::now(),
        ]);
        DB::table('customers')->where('id', $req->id)->update([
            'referral_code'=>$req->referral_code,
            'type'=>$req->type,
            'updated_at'=>Carbon::now(),
        ]);
        $customer = DB::table('customers')->where('id', $req->id)->first();
        $name = $customer->firstname.' '.$customer->middle_name.' '.$customer->surname;
        $message = 'Your loan application has been received. Kindly send the available documents to risk@swisscredit.ng<br>Loan Requirements Include;<ul><li>Recent Passport Photograph</li><li>Clear Picture of work ID Card</li><li>Utility Bill</li><li>Social media handle.</li><li>Letter of Employment /Letter of Confirmation</li><li>Valid means of ID.</li><li>Picture of Office Building ( Picture of Gate and Office Building in one Photo)</li></ul><p>Kindly notify your bank to send your <b>Statement of Account (Six Months till Date)</b> to statements@swisscredit.ng.<br>';
        $subject = 'Loan Request Received';
        $email = $customer->email;
        Mail::to($email)->send(new SendMailerCustomer($name, $message, $subject));
        if($agent != null && $agentData != null){
            $message = 'A customer just successfully applied using your referral link.';
            $subject = 'Loan Application';
            Mail::to($agentData->email)->send(new SendMailerCustomer($agentData->name, $message, $subject));
            $req->session()->forget('swiss_club_agent');
        }
        return redirect('/myForm/status/success');
    }

    public function formStatus($status){
        $token = csrf_token();
        if($status == "success"){
            setcookie($token."_new_form_stop", '', time() - 3600, "/");
            setcookie($token."_new_form_id", '', time() - 3600, "/");
            setcookie($token."_bvn", '', time() - 3600, "/");
            setcookie($token."_renew_form_stop", '', time() - 3600, "/");
            setcookie($token."_renew_form_id", '', time() - 3600, "/");
            setcookie($token, '', time() - 3600, "/");
            return view('forms.success');
        }else if($status == "errorswb4"){
            setcookie($token."_new_form_stop", '', time() - 3600, "/");
            setcookie($token."_new_form_id", '', time() - 3600, "/");
            setcookie($token."_bvn", '', time() - 3600, "/");
            setcookie($token."_renew_form_stop", '', time() - 3600, "/");
            setcookie($token."_renew_form_id", '', time() - 3600, "/");
            setcookie($token, '', time() - 3600, "/");
            return view('forms.filled');
        }else {
            return view('forms.status');
        }
    }

    public function offerLetter($code){
        $code = Utils::encrypt_decrypt('decrypt', $code);
        $offer = DB::table('offers')->where('id', $code)->first();
        $form = Utils::getForm($offer->d_form);
        $signatures = DB::table('signatures')->where('offer', $code)->get();
        if(count($signatures) > 0)
            return view('errors.404');
        else
            return view('forms.offerLetter', ['code'=>$code, 'offer'=>$offer, 'customer'=>$form]);
    }

    public function viewOffer($id){
        $offer = DB::table('offers')->where('id', Utils::encrypt_decrypt('decrypt', $id))->first();
        $customer = Utils::getForm($offer->d_form);
        $signature = DB::table('signatures')->where('offer', $offer->id)->first();
        $saveName = 'generated/loan_form/offer_letter_'.uniqid().'_'.time().'_'.mt_rand(000000,999999).'.pdf';
        $pdf = PDF::loadView('offer_letter_form', ['offer'=>$offer, 'customer'=>$customer, 'signature'=>$signature])->save($saveName);
        $header = Utils::previewer($saveName);
        return view('previewer', ['file'=>$header]);
    }

    public function signOfferLetter(Request $req){
        $compare = '<svg xmlns="http://www.w3.org/2000/svg" width="15cm" height="15cm" viewBox="0 0 398 198"><g fill="#fff"><rect x="0" y="0" width="398" height="198"></rect><g fill="none" stroke="#000" stroke-width="2"></g></g></svg>';
        if(Utils::removeSpacesNTabs($req->signature) == $compare || Utils::removeSpacesNTabs($req->signature2) == $compare){
            return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-danger b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Oh Snap!</strong> Please ensure the two signatory sheet provided are signed. </div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
        }else {
            $offer = DB::table('offers')->where('id', $req->id)->first();
            $dform = Utils::getForm($offer->d_form);
            DB::table('customer2s')->where('customer_id', $offer->d_form)->update(['customer'=>'approved', 'updated_at'=>Carbon::now()]);
            DB::table('offers')->where('id', $req->id)->update(['status'=>'approved', 'updated_at'=>Carbon::now()]);
            DB::table('signatures')->where('offer', $req->id)->delete();
            DB::table('signatures')->insert([
                'signature'=>Utils::removeSpacesNTabs($req->signature),
                'signature_two'=>Utils::removeSpacesNTabs($req->signature2),
                'offer'=>$req->id,
                'inuse'=>$req->used,
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now(),
            ]);
            /*$uniqid = uniqid().mt_rand(000000,999999);
            $users = User::get();
            foreach($users as $user){
                Utils::createNotification('<i class="dripicons dripicons-document-edit text-white"></i>', 'Document Signed', 'Congratulations!!! A customer just signed his offer.', $user->email, '/notifications/'.$uniqid.'/'.Utils::encrypt_decrypt('encrypt', '/customers'), 'bg-success', $uniqid);
            }*/
            return redirect('/customer/approved');
        }
    }

    public function approvedOffer(){
        return view('forms.status', ['approved'=>true]);
    }

    public function guarantorForm($code){
        $dguar = DB::table('guarantors')->where('code', $code)->first();
        if($dguar == null)
            return view('errors.404');
        elseif($dguar->firstname != null)
            return view('errors.404');
        else{
            $offer = DB::table('offers')->where('id', $dguar->d_offer)->first();
            $dform = Utils::getForm($offer->d_form);
            return view('forms.guarantorForm', ['dguar'=>$dguar, 'dform'=>$dform]);
        }
    }

    public function formPostGuarantor(Request $req){
        $compare = '<svg xmlns="http://www.w3.org/2000/svg" width="15cm" height="15cm" viewBox="0 0 398 198"><g fill="#fff"><rect x="0" y="0" width="398" height="198"></rect><g fill="none" stroke="#000" stroke-width="2"></g></g></svg>';
        if(Utils::removeSpacesNTabs($req->signature) == $compare || Utils::removeSpacesNTabs($req->signature2) == $compare){
            return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-danger b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Oh Snap!</strong> Please ensure the two signatory sheet provided are signed. </div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
        }else {
            $dguar = DB::table('guarantors')->where('id', $req->guarantor)->first();
            $offer = DB::table('offers')->where('id', $dguar->d_offer)->first();
            $dform = Utils::getForm($offer->d_form);
            if ($files = $req->file('passport')) {
                $destinationPath = 'uploads/passports';
                $dimagename = $files->getClientOriginalName();
                $filename = pathinfo($dimagename, PATHINFO_FILENAME);
                $extension = pathinfo($dimagename, PATHINFO_EXTENSION);
                $dfile = uniqid().'_'.time().'_'.mt_rand(000000,999999).'_'.Utils::removeSpaces($filename).'.'.$extension;
                $files->move($destinationPath, $dfile);
                DB::table('guarantors')->where('id',$req->guarantor)->update([
                    'passport'=>$destinationPath.'/'.$dfile,
                    'surname'=>$req->sname,
                    'firstname'=>$req->fname,
                    'middle_name'=>$req->mname,
                    'title'=>$req->title,
                    'marital_status'=>$req->mstatus,
                    'gender'=>$req->gender,
                    'dob'=>$req->dob,
                    'nationality'=>$req->nation,
                    'employer_name'=>$req->employer,
                    'office_address'=>$req->office_addr,
                    'currently_guarantor'=>$req->already_guarantor,
                    'if_yes_name'=>$req->yes_name,
                    'residential_address'=>$req->residential_addr,
                    'office_no'=>$req->office_no,
                    'mobile_no'=>$req->mobile_no,
                    'home_no'=>$req->home_no,
                    'personal_email'=>$req->personal_email,
                    'official_email'=>$req->official_email,
                    'relationship_with_applicant'=>$req->relationship,
                    'position_held'=>$req->position,
                    'branch_telephone'=>$req->branch_telephone,
                    'annual_income'=>$req->income,
                    'did_provide_cheque'=>$req->cheque,
                    'cheque_count'=>$req->cheque_counts,
                    'other_outstanding_swiss'=>$req->with_swiss_credit,
                    'other_outstanding_other'=>$req->with_other_banks,
                    'updated_at'=>Carbon::now()
                ]);
                DB::table('guarantors')->where('form_id', $req->guarantor)->delete();
                DB::table('guarantors')->insert([
                    'form_id'=>$req->guarantor,
                    'sign_1'=>Utils::removeSpacesNTabs($req->signature),
                    'sign_2'=>Utils::removeSpacesNTabs($req->signature2),
                    'default'=>$req->used,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now()
                ]);
                $uniqid = uniqid().mt_rand(000000,999999);
                Utils::createNotification('<i class="dripicons dripicons-document-edit text-white"></i>', 'Guarantor Submitted', 'A gurantor has been submitted.', $offer->staff, '/notifications/'.$uniqid.'/'.Utils::encrypt_decrypt('encrypt', '/offers/view/guarantors/'.$req->guarantor), 'bg-purple', $uniqid);
                return redirect('/customer/guarantor/approved');
            }
        }
    }

    public function approvedOfferGuarantor(){
        return view('forms.status', ['approved'=>true, 'guarantor'=>true]);
    }

    public function verifyReferral(Request $req){
        $ref = DB::table('referrals')->where('code', $req->code)->first();
        if($ref != null){
            echo ucwords($ref->name).', '.$ref->email.', '.$ref->phone;
        }else {
            echo 'Invalid referral code';
        }
    }
}
