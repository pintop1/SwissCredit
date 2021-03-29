@php
use App\Http\Controllers\Globals as Utils;
use Illuminate\Support\Facades\DB;
$range = explode(' - ', urldecode($_GET['daterange']));
$date1 = explode('/', $range[0]);
$date2 = explode('/', $range[1]);
$ddate1 = $date1[2].'-'.$date1[0].'-'.$date1[1];
$ddate2 = $date2[2].'-'.$date2[0].'-'.$date2[1];
$files = DB::table('customers')->whereBetween('created_at', [$ddate1, $ddate2])->orderBy('id', 'asc')->get();
$darray = array();
$i = 1;
foreach($files as $file){
    $offer = DB::table('offers')->where('d_form', $file->id)->first();
    $dform = Utils::getForm($file->id);
    $dsecond = DB::table('customer2s')->where('customer_id', $file->id)->first();
    $approved = '';

    $credit_risk = '';
    $credit_riskt = '';

    $underwriters = '';
    $underwriterst = '';

    $risks = '';
    $riskst = '';

    $operations = '';
    $operationst = '';

    $finance = '';
    $financet = '';

    $internal_control = '';
    $internal_controlt = '';

    $director = '';
    $directort = '';

    if($offer != null){
        $approved = $offer->amount_recommended;
    }
    if($dform->credit_risk == 'approved'){
        $dapproval = DB::table('approvals')->where(['file'=>$dform->id, 'role'=>'credit risk'])->first();
        if($dapproval != null){
            $dauser = Utils::getUserByEmail($dapproval->staff);
            if($dauser != null){
                $credit_risk = ucwords($dauser->name);
                $credit_riskt = date('M d, Y H:i A', strtotime($dapproval->created_at));
            }
        }
    }
    if($dform->underwriters == 'approved'){
        $dapproval = DB::table('approvals')->where(['file'=>$dform->id, 'role'=>'underwriter'])->first();
        if($dapproval != null){
            $dauser = Utils::getUserByEmail($dapproval->staff);
            if($dauser != null){
                $underwriters = ucwords($dauser->name);
                $underwriterst = date('M d, Y H:i A', strtotime($dapproval->created_at));
            }
        }
    }
    if($dform->risk == 'approved'){
        $dapproval = DB::table('approvals')->where(['file'=>$dform->id, 'role'=>'risk'])->first();
        if($dapproval != null){
            $dauser = Utils::getUserByEmail($dapproval->staff);
            if($dauser != null){
                $risks = ucwords($dauser->name);
                $riskst = date('M d, Y H:i A', strtotime($dapproval->created_at));
            }
        }
    }
    if($dform->operations == 'approved'){
        $dapproval = DB::table('approvals')->where(['file'=>$dform->id, 'role'=>'operations'])->first();
        if($dapproval != null){
            $dauser = Utils::getUserByEmail($dapproval->staff);
            if($dauser != null){
                $operations = ucwords($dauser->name);
                $operationst = date('M d, Y H:i A', strtotime($dapproval->created_at));
            }
        }
    }
    if($dform->finance == 'approved'){
        $dapproval = DB::table('approvals')->where(['file'=>$dform->id, 'role'=>'finance'])->first();
        if($dapproval != null){
            $dauser = Utils::getUserByEmail($dapproval->staff);
            if($dauser != null){
                $finance = ucwords($dauser->name);
                $financet = date('M d, Y H:i A', strtotime($dapproval->created_at));
            }
        }
    }
    if($dform->internal_control == 'approved'){
        $dapproval = DB::table('approvals')->where(['file'=>$dform->id, 'role'=>'internal control'])->first();
        if($dapproval != null){
            $dauser = Utils::getUserByEmail($dapproval->staff);
            if($dauser != null){
                $internal_control = ucwords($dauser->name);
                $internal_controlt = date('M d, Y H:i A', strtotime($dapproval->created_at));
            }
        }
    }
    if($dform->status == 'approved'){
        $dapproval = DB::table('approvals')->where(['file'=>$dform->id, 'role'=>'director'])->first();
        if($dapproval != null){
            $dauser = Utils::getUserByEmail($dapproval->staff);
            if($dauser != null){
                $director = ucwords($dauser->name);
                $directort = date('M d, Y H:i A', strtotime($dapproval->created_at));
            }
        }
    }
    $status = '';
    $statust = '';
    $reason = '';
    if($file->status != null){
        $status = $file->status;
        $statust = date('M d, Y - H:m A', strtotime($file->created_at));
        
    }
    if($file->status == 'declined'){
        $reason = $dform->reason;
    }
    $datetime1 = new DateTime(date('Y-m-d h:m:i A', strtotime($dform->created_at)));
    $datetime2 = new DateTime(date('Y-m-d h:m:i A', strtotime($dform->updated_at)));
    $interval = $datetime1->diff($datetime2);
    $turn = $interval->format('%h')." Hours ".$interval->format('%m')." Minutes";
    $narray = array("id"=>$dform->id, "name_of_customer"=>$dform->firstname.' '.$dform->middle_name.' '.$dform->surname, "bvn"=>$dform->bvn ?? "-", "phone"=>$dform->phone_no ?? "-" .','.$dform->mobile_no  ?? "-", "address"=>$dform->current_employer_address ?? "-", "company"=>$dform->current_employer, "residential_address"=>$dform->address, "dob"=>$dform->date_of_birth, "email"=>$dform->official_email ?? "-", "loan_amount_approved"=>$approved ?? "-", "credit_risk_officers"=>array("staff_name"=>$credit_risk ?? "-", "date_approved"=>$credit_riskt ?? "-"), "underwriters"=>array("staff_name"=>$underwriters ?? "-", "date_approved"=>$underwriterst ?? "-"), "risk_officers"=>array("staff_name"=>$risks, "date_approved"=>$riskst), "operation_officer"=>array("staff_name"=>$operations, "date_approved"=>$operationst), "finance_officer"=>array("staff_name"=>$finance, "date_approved"=>$financet), "internal_control"=>array("staff_name"=>$internal_control, "date_approved"=>$internal_controlt), "director"=>array("staff_name"=>$director, "date_approved"=>$directort),"status_of_loan"=>array("status"=>$status, "declined_by"=>$dform->declined_by, "date_updated"=>$statust, "reason"=>$reason), "turn_around"=>$turn, "poll"=>$dform->poll, 'referral'=>ucwords(Utils::getRef($dform->referral_code)->name ?? '-'), 'type'=>ucwords($dform->type));
    array_push($darray, $narray);
}
@endphp
<table>
    <thead>
        <tr>
            <th>SN</th>
            <th>NAME OF CUSTOMER</th>
            <th>BVN</th>
            <th>PLACE OF WORK</th>
            <th>EMPLOYER NAME</th>
            <th>RESIDENTIAL ADDRESS</th>
            <th>DATE OF BIRTH</th>
            <th>PHONE NUMBER</th>
            <th>EMAIL ADDRESS</th>
            <th>LOAN AMOUNT APPROVED</th>
            <th colspan="2">CREDIT RISK</th>
            <th colspan="2">UNDERWRITERS</th>
            <th colspan="2">RISK</th>
            <th colspan="2">OPERATIONS</th>
            <th colspan="2">FINANCE</th>
            <th colspan="2">INTERNAL CONTROL</th>
            <th colspan="2">DIRECTOR</th>
            <th colspan="4">STATUS OF LOAN</th>
            <th></th>
            <th>HOW I HEARD ABOUT SWISS CREDIT</th>
            <th>REFERRAL</th>
            <th>TYPE</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>STAFF NAME</td>
            <td>DATE APPROVED</td>
            <td>STAFF NAME</td>
            <td>DATE APPROVED</td>
            <td>STAFF NAME</td>
            <td>DATE APPROVED</td>
            <td>STAFF NAME</td>
            <td>DATE APPROVED</td>
            <td>STAFF NAME</td>
            <td>DATE APPROVED</td>
            <td>STAFF NAME</td>
            <td>DATE APPROVED</td>
            <td>STAFF NAME</td>
            <td>DATE APPROVED</td>
            <td>STATUS</td>
            <td>DATE APPROVED</td>
            <td>REASON FOR DECLINING</td>
            <td>DECLINED BY</td>
            <td>TURN AROUND TIME</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @foreach($darray as $arr)
        <tr>
            <td>SC/{{ strtoupper(Utils::getFirstLetterCode($arr['name_of_customer'])) }}/{{ str_pad($arr['id'], 5, '0', STR_PAD_LEFT) }}</td>
            <td>{{ ucwords($arr['name_of_customer'] ?? "-") }}</td>
            <td>{{ $arr['bvn'] ?? "-" }}</td>
            <td>{{ $arr['address'] ?? "-" }}</td>
            
            <td>{{ $arr['company'] ?? "-" }}</td>
            <td>{{ $arr['residential_address'] ?? "-" }}</td>
            <td>{{ $arr['dob'] ?? "-" }}</td>
            
            <td>{{ $arr['phone'] ?? "-" }}</td>
            <td>{{ $arr['email'] ?? "-" }}</td>
            <td>{{ ($arr['loan_amount_approved'] != '') ? 'N'.number_format($arr['loan_amount_approved'],2) : '-' }}</td>
            <td>{{ ucwords($arr['credit_risk_officers']['staff_name'] ?? "-") }}</td>
            <td>{{ $arr['credit_risk_officers']['date_approved'] ?? "-" }}</td>
            <td>{{ ucwords($arr['underwriters']['staff_name'] ?? "-") }}</td>
            <td>{{ $arr['underwriters']['date_approved'] ?? "-" }}</td>
            <td>{{ ucwords($arr['risk_officers']['staff_name'] ?? "-") }}</td>
            <td>{{ $arr['risk_officers']['date_approved'] ?? "-" }}</td>
            <td>{{ ucwords($arr['operation_officer']['staff_name'] ?? "-") }}</td>
            <td>{{ $arr['operation_officer']['date_approved'] ?? "-" }}</td>
            <td>{{ ucwords($arr['finance_officer']['staff_name'] ?? "-") }}</td>
            <td>{{ $arr['finance_officer']['date_approved'] ?? "-" }}</td>
            <td>{{ ucwords($arr['internal_control']['staff_name']) }}</td>
            <td>{{ $arr['internal_control']['date_approved'] }}</td>
            <td>{{ ucwords($arr['director']['staff_name']) }}</td>
            <td>{{ $arr['director']['date_approved'] }}</td>
            <td>{{ ucwords($arr['status_of_loan']['status']) }}</td>
            <td>{{ $arr['status_of_loan']['date_updated'] }}</td>
            <td>{{ $arr['status_of_loan']['reason'] }}</td>
            <td>{{ ucwords(Utils::getUserByEmail($arr['status_of_loan']['declined_by'])->name ?? "-") }}</td>
            <td>{{ round($arr['turn_around'] ?? "-") }}hours</td>
            <td>{{ $arr['poll'] ?? "-" }}</td>
            <td>{{ $arr['referral'] ?? "-" }}</td>
            <td>{{ $arr['type'] ?? "-" }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
