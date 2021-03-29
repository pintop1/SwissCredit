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
$deduct = $management + $processing + $moratorium;
$todisburse = $offer->amount_recommended - $deduct;
$totalRepay = $montlyRepay + $interest_monthly;
@endphp
<!DOCTYPE html>
<html>
<style>
    body {
        font-family: sans-serif;
        padding: 60px 30px 30px;
        font-size: 1em;
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
    }
    table.summ tr td {
        border: 1px solid #666;
        text-align: left;
        padding: 5px 5px;
        font-weight: lighter;
    }
    .underlined {
        text-decoration: underline;
    }
    .inline {
        display: inline-block;
        margin-right: 20px;
    }
    ol.alpha {
        list-style: lower-roman;
    }
    ol.number {
        padding-top: 10px;
        padding-bottom: 10px;
    }
    ol.number .title {
        font-weight: bolder;
        padding-bottom: 10px;
    }
    table.break {
        border-collapse: collapse;
        border:1px solid #666;
        width: 100%
    }
    table.break tr th {
        border: 1px solid #666;
        text-align: left;
        padding: 10px 5px;
        font-weight: bolder;
        text-align: right;
    }
    table.break tr td {
        border: 1px solid #666;
        text-align: left;
        padding: 10px 5px;
        text-align: right;
    }
    table.break tr th:first-child {
        width: 5%;
        text-align: center;
    }
    table.break tr td:first-child {
        width: 5%;
        text-align: center;
    }
    .ddotted {
        border-bottom: 2px dotted #222;
        padding-left: 10px;
        padding-right: 10px;
    }
</style>
    <body>
        <div>
            {{ date('F d, Y') }}.<br><br>
            {!! $req->address !!}
            @php
            $converted = Utils::numtowords($todisburse);
            @endphp
            Dear Sir/Madam,<br>
            <h4>INSTANT FUND TRANSFER INSTRUCTION</h4>
            Kindly take this as an instruction to debit our current account no: <b>{{ $req->bank }}</b> with the sum of N{{ number_format($todisburse,2) }} ({{ ucwords($converted) }} only) and credit the following customer:<br><br>
            <table class="summ">
                <tr>
                    <th>S/NO</th>
                    <th>NAME OF CUSTOMER</th>
                    <th>BANK</th>
                    <th>ACCOUNT NUMBER</th>
                    <th>AMOUNT</th>
                    <th>PV NO</th>
                </tr>
                <tr>
                    <td>1</td>
                    <td>{{ strtoupper($customer->firstname.' '.$customer->middle_name.' '.$customer->surname) }}</td>
                    <td>{{ strtoupper(explode(' ', $offer->disbursement_account)[1]) }}</td>
                    <td>{{ explode(' ', $offer->disbursement_account)[0] }}</td>
                    <td>{{ number_format($todisburse,2) }}</td>
                    <td>{{ $req->pv_no }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td><b>TOTAL</b></td>
                    <td></td>
                    <td></td>
                    <td><b>{{ number_format($todisburse,2) }}</b></td>
                    <td></td>
                </tr>
            </table><br><br><br><br><br><br><br><br>
            <div class="inline">------------------------------------<br>AUTHORIZED SIGNATORY</div><div class="inline" style="float: right;">-------------------------------------<br>AUTHORIZED SIGNATORY</div>
        </div>
    </body>
</html>