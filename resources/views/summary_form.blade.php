@php
use App\Http\Controllers\Globals as Utils;
$management = $offer->amount_recommended  * ($offer->management_fee/100);
$processing = $offer->amount_recommended * ($offer->processing_fee/100);
$interest_monthly = $offer->amount_recommended * ($offer->interest/100);
$moratorium = 0;
$montlyRepay = $offer->amount_recommended / $offer->tenor;
$principal_repay = $offer->amount_recommended / $offer->tenor;
if($offer->prorated > 0){
    $moratorium = ($offer->prorated/30)*$interest_monthly;
    $mdivi = $moratorium/$offer->tenor;
    $montlyRepay += $mdivi;;
}
$deduct = $management + $processing + $offer->outstanding_bal;
$todisburse = $offer->amount_recommended - $deduct;
$totalRepay = $montlyRepay + $interest_monthly;
@endphp
<!DOCTYPE html>
<html>
<style>
    body {
        margin-top: 0px;
        font-family: sans-serif;
        padding: 15px;
        font-size: 0.6em;
    }
    table.summ {
        border-collapse: collapse;
        border:1px solid #666;
        width: 100%
    }
    table.summ tr th {
        border: 1px solid #666;
        text-align: left;
        padding: 5px 5px;
        font-weight: lighter;
    }
    table.summ tr td {
        border: 1px solid #666;
        text-align: left;
        padding: 5px 5px;
    }
    .big {
        font-size: 1.2em;
        font-weight: bolder;
    }
</style>
    <body>
        <div>
            <div style="text-decoration: underline;font-weight: bolder; font-size: 1.5em">TRANSACTION SUMMARY/APPROVAL SHEET</div>
            PLEASE SEE THE SUMMARY OF THIS TRANSACTION<br><br>
            <table class="summ">
                <tr>
                    <th rowspan="9">CUSTOMER DETAILS</th>
                    <th>APPLICANT'S NAMES</th>
                    <th>{{ strtoupper($customer->firstname.' '.$customer->middle_name.' '.$customer->surname) }}</th>
                </tr>
                <tr>
                    <td>BVN</td>
                    <td>{{ $customer->bvn }}</td>
                </tr>
                <tr>
                    <td>RESIDENTIAL ADDRESS</td>
                    <td>{{ strtoupper($customer->address) }},{{ strtoupper($customer->landmark.', '.$customer->state) }}</td>
                </tr>
                <tr>
                    <td>PHONE NUMBER</td>
                    <td>{{ $customer->phone_no }}</td>
                </tr>
                <tr>
                    <td>CUSTOMERS MAIL</td>
                    <td>{{ strtoupper($customer->email) }}</td>
                </tr>
                <tr>
                    <td>EMPLOYER</td>
                    <td>{{ strtoupper($offer->employer) }}</td>
                </tr>
                <tr>
                    <td>CLIENT’S OFFICE ADDRESS</td>
                    <td>{{ strtoupper($offer->office_address) }}</td>
                </tr>
                <tr>
                    <td>DATE EMPLOYED</td>
                    <td>{{ $offer->date_employed }}</td>
                </tr>
                <tr>
                    <td>POSITION HELD</td>
                    <td>{{ strtoupper($offer->position) }}</td>
                </tr>
                
                <tr>
                    <td rowspan="20">LOAN DETAILS</td>
                    <td>LOAN PURPOSE</td>
                    <td>{{ strtoupper($customer->purpose) }}</td>
                </tr>
                <tr>
                    <td>AMOUNT REQUESTED</td>
                    <td>N{{ number_format($customer->loan_amount_requested) }}</td>
                </tr>
                <tr>
                    <td>AMOUNT APPROVED</td>
                    <td>N{{ number_format($offer->amount_recommended) }}</td>
                </tr>
                <tr>
                    <td>LOAN TENOR</td>
                    <td>{{ strtoupper($offer->tenor) }} MONTHS</td>
                </tr>
                <tr>
                    <td>REQUEST STATUS</td>
                    <td>{{ strtoupper($offer->request_status) }}</td>
                </tr>
                <tr>
                    <td>LAST REQUEST</td>
                    <td>{{ strtoupper($offer->last_request) }}</td>
                </tr>
                <tr>
                    <td>PERFORMANCE OF LAST REQUEST</td>
                    <td>{{ strtoupper($offer->performance_last_request) }}</td>
                </tr> 
                <tr>
                    <td>REPAYMENT STRUCTURE</td>
                    <td>{{ strtoupper($offer->repayment_structure) }}</td>
                </tr>
                <tr>
                    <td>REPAYMENT SOURCE</td>
                    <td>{{ strtoupper($offer->repayment_source) }}</td>
                </tr>
                <tr>
                    <td>INTEREST RATE MONTHLY</td>
                    <td>{{ $offer->interest }}%</td>
                </tr>
                <tr>
                    <td>PROCESSING FEE</td>
                    <td>{{ $offer->processing_fee }}%</td>
                </tr>
                <tr>
                    <td>MANAGEMENT FEE</td>
                    <td>{{ $offer->management_fee }}%</td>
                </tr>
                <tr>
                    <td>OUTSTANDING BALANCE</td>
                    <td>N{{ number_format($offer->outstanding_bal) }}</td>
                </tr>
                <tr>
                    <td>AMOUNT TO BE DISBURSED</td>
                    <td>N{{ number_format($todisburse) }}</td>
                </tr>
                <tr>
                    <td>MONTHLY SALARY</td>
                    <td>N{{ number_format($offer->salary) }}</td>
                </tr>
                <tr>
                    <td>LOANS/OTHER OBLIGATIONS</td>
                    <td>{{ strtoupper($offer->other_obligations) }}</td>
                </tr>
                <tr>
                    <td>DEBT SERVICE RATIO</td>
                    <td>{{ round(($totalRepay/$offer->salary)*100) }}%</td>
                </tr>
                <tr>
                    <td>MONTHLY RENTAL</td>
                    <td>N{{ number_format($totalRepay,2) }}</td>
                </tr>
                <tr>
                    <td>CREDIT CHECK REPORT</td>
                    <td>{{ strtoupper($offer->credit_check) }}</td>
                </tr>
                <tr>
                    <td>DISBURSEMENT ACCOUNT DETAILS</td>
                    <td>{{ strtoupper($offer->disbursement_account) }}</td>
                </tr>


                <tr>
                    <td rowspan="4">COLLATERAL DETAILS</td>
                    <td>COLLATERAL / SECURITY</td>
                    <td>{{ strtoupper($offer->collateral) }}</td>
                </tr>
                <tr>
                    <td>GUARANTORS NAME</td>
                    @if($dguar != null)
                    <td>{{ strtoupper($dguar->firstname.' '.$dguar->middle_name.' '.$dguar->surname) }}</td>
                    @else
                    <td>NIL</td>
                    @endif
                </tr>
                <tr>
                    <td>GUARANTORS PHONE NUMBER</td>
                    @if($dguar != null)
                    <td>{{ strtoupper($dguar->mobile_no) }}</td>
                    @else
                    <td>NIL</td>
                    @endif
                </tr>
                <tr>
                    <td>GUARANTORS MAIL</td>
                    @if($dguar != null)
                    <td>{{ strtoupper($dguar->personal_email) }}</td>
                    @else
                    <td>NIL</td>
                    @endif
                </tr>
                <tr>
                    <td rowspan="5">AUTHORIZED STAFF DETAILS</td>
                    <td>ACCOUNT OFFICER</td>
                    <td>{{ strtoupper($offer->account_officer) }}</td>
                </tr>
                <tr>
                    <td>COLLECTION OFF`S COMMENTS</td>
                    <td></td>
                </tr>
                <tr>
                    <td>VERIFICATION OFF`S COMMENTS</td>
                    <td></td>
                </tr>
                <tr>
                    <td>FINANCE OFFICER`S COMMENTS</td>
                    <td></td>
                </tr>
                <tr>
                    <td>INTERNAL CONTROL`S COMMENT</td>
                    <td></td>
                </tr>
            </table>
        </div>
    </body>
</html>