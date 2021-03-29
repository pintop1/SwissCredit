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
$deduct = $management + $processing;
$todisburse = $offer->amount_recommended - $deduct;
$totalRepay = $montlyRepay + $interest_monthly;
$ddifm = '';
$dpro = $offer->prorated*1;
$dten = $offer->tenor*1;
$deductMonth = 0;
if($offer->pay_starts == "this")
    $deductMonth = 1;
if($dpro > 0){
    $datetime = new DateTime(Utils::getTenorLastDay($offer->tenor-$deductMonth, $offer->updated_at));
    $datetime->modify('+'.$dpro.' day');
    $ddifm = $datetime->format('Y-m-d H:i:s');
}
//dd($ddifm);
@endphp
<!DOCTYPE html>
<html>
<style>
    body {
        border: 2px solid #000;
        font-family: sans-serif;
        padding: 60px 30px 30px;
        font-size: 0.9em;
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
            {{ strtoupper($offer->file_code) }}<br><br>
            {{ strtoupper($offer->name) }}<br><br>
            {{ strtoupper($offer->address) }}<br><br><br>
            Sir,<br>
            OFFER OF N{{ number_format($offer->amount_recommended,2) }} LOAN FACILITY<br><br>
            The management of SWISS CREDIT LIMITED (the <q>Company</q>) has approved an offer of a loan facility to you {{ strtoupper($offer->name) }} (<q>The Borrower</q>) under the following terms and conditions:<br><br>
            SUMMARY OF TERMS AND CONDITIONS<br><br>
            <img src="{{ env('APP_URL') }}/assets/images/logo_offer.png" style="position: absolute;float: right; width: 200px;top: 0;">
            <table class="summ">
                <tr>
                    <th>LENDER</th>
                    <th>SWISS CREDIT LIMITED</th>
                </tr>
                <tr>
                    <td>BORROWER</td>
                    <td>{{ strtoupper($offer->name) }}</td>
                </tr>
                <tr>
                    <td>FACILITY</td>
                    <td>{{ ucwords($offer->purpose) }}</td>
                </tr>
                <tr>
                    <td>LOAN AMOUNT</td>
                    <td>N{{ number_format($offer->amount_recommended,2) }}</td>
                </tr>
                <tr>
                    <td>TENURE</td>
                    <td>{{ $offer->tenor }} Months</td>
                </tr>
                <tr>
                    <td>INTEREST RATE</td>
                    <td>{{ $offer->interest }}%</td>
                </tr>
                <tr>
                    <td>FEES (Upfront fees)</td>
                    <td>{{ $offer->processing_fee+$offer->management_fee }}% (Deductible Upfront)</td>
                </tr>
                <tr>
                    <td>DEFAULT FEE</td>
                    <td>{{ $offer->default_charge }}%</td>
                </tr>
                @if($dpro > 0 && $dten < 2)
                <tr>
                    <td>PERIOD</td>
                    <td>{{ date('F d, Y', strtotime($offer->updated_at)) }} - {{ date('F d, Y', strtotime($ddifm)) }}</td>
                </tr>
                @else
                <tr>
                    <td>PERIOD</td>
                    <td>{{ date('F d, Y', strtotime($offer->updated_at)) }} - {{ date('F', strtotime(Utils::getTenorLastDay($offer->tenor-$deductMonth, $offer->updated_at))) }} {{ Utils::getRepayDate($offer->pay_date, Utils::getTenorLastDay($offer->tenor-$deductMonth, $offer->updated_at)) }}, {{ date('Y', strtotime(Utils::getTenorLastDay($offer->tenor-$deductMonth, $offer->updated_at))) }}</td>
                </tr>
                @endif
                <tr>
                    <td style="font-weight: bold;">FIRST REPAYMENT DATE</td>
                    @if($dpro > 0 && $dten < 2)
                    <td style="font-weight: bold;">{{ date('F d, Y', strtotime($ddifm)) }}</td>
                    @else
                    <td style="font-weight: bold;">{{ date('F', strtotime(Utils::getTenorLastDay(1-$deductMonth, $offer->updated_at))) }} {{ Utils::getRepayDate($offer->pay_date, Utils::getTenorLastDay(1-$deductMonth, $offer->updated_at)) }}, {{ date('Y', strtotime(Utils::getTenorLastDay(1-$deductMonth, $offer->updated_at))) }}</td>
                    @endif
                </tr>
                <tr>
                    <td>FINAL REPAYMENT DATE</td>
                    @if($dpro > 0 && $dten < 2)
                    <td>{{ date('F d, Y', strtotime($ddifm)) }}</td>
                    @else
                    <td>{{ date('F', strtotime(Utils::getTenorLastDay($offer->tenor-$deductMonth, $offer->updated_at))) }} {{ Utils::getRepayDate($offer->pay_date, Utils::getTenorLastDay($offer->tenor-$deductMonth, $offer->updated_at)) }}, {{ date('Y', strtotime(Utils::getTenorLastDay($offer->tenor-$deductMonth, $offer->updated_at))) }}</td>
                    @endif
                </tr>
                <tr>
                    <td>MONTHLY INSTALLMENT</td>
                    <td>N{{ number_format($totalRepay, 2) }}</td>
                </tr>
                <tr>
                    <td>EXPECTED DISBURSEMENT</td>
                    <td>N{{ number_format($todisburse, 2) }}</td>
                </tr>
            </table><br><br><br><br>
            <div class="underlined">MEMORANDUM OF ACCEPTANCE</div><br>
            @if(isset($signature) && $signature->inuse != null)
            I <b><span class="ddotted"> {{ strtoupper($offer->name) }}</span></b> hereby, unconditionally as i have read and understood the terms with total clarity.<br><br><br>
            @if($signature->inuse == '1')
            <div class="inline">Sign <img src="data:image/svg+xml;base64,{!! base64_encode($signature->signature) !!}" style="position: absolute;" width="320" height="140"></div>
            @else
            <div class="inline">Sign <img src="data:image/svg+xml;base64,{!! base64_encode($signature->signature_two) !!}" style="position: absolute;" width="320" height="140"></div>
            @endif
            <div class="inline" style="float: right;">Date <span class="ddotted">{{ date('d/m/Y', strtotime($signature->created_at)) }}</span></div>
            @else
            I ............................................... hereby, unconditionally as i have read and understood the terms with total clarity.<br><br><br>
            <div class="inline">Sign ...........................................</div>
            <div class="inline" style="float: right;">Date ...........................................</div>
            @endif
            <h4 class="underlined">LOAN TERMS AND CONDITIONS</h4>
            <div>
                <b>Conditions Precedent to Draw-Down</b><br>
                Following your decision to obtain Credit Facility from Swiss Credit Nigeria Limited, here are the conditions in the agreement, that are precedent to this;
                <ol class="alpha">
                    <li>Receipt of Borrower's completed Application Letter for the Loan facility and other supporting documents.</li>
                    <li>Acceptance of the terms and conditions of the offer as contained herein.</li>
                </ol>
                <b>Other Conditions</b>
                <ol class="number">
                    <li><div class="title"> Advance and Repayment</div>
                        <ol class="alpha">
                            <li>Swiss Credit agrees to advance the Credit facility, and the Borrower agrees to take the Credit Facility to the Terms and Conditions as set out in the Application Form and also herein in this Terms and Conditions Document.</li>
                            <li>The borrower agrees to repay Swiss Credit Facility (principal plus accrued interest) in accordance with the Terms and Conditions of this Document.
                            </li>
                            <li>The Borrower hereby covenants to promptly supply all materials information and such documents, including but not limited to the company bank statement, which the Company may require from time-to-time to access the continued worthiness of the Borrower.</li>
                            <li>The debt obligation created as a result of this facility shall not be subordinated to any outstanding or future obligations of the Borrower.</li>
                            <li>If the Borrower intends to liquidate the facility before the end of the loan period, the borrower is to pay up the outstanding principal, the full interest for the current month, and a pre-liquidation charge of 2.5% on the outstanding total principal outstanding.</li>
                            <li>Where the date for the repayment installment falls on a non-business day, the payment becomes due on the proceeding business day</li>
                        </ol>
                    </li>
                    <li><div class="title">Representation and Warranties</div><div>The borrower represents and warrants to the Company that:
                        <ol class="alpha">
                            <li>There has to be no material adverse change in the Borrower's financial position as represented to the Company prior to the signing of this offer letter.</li>
                            <li>There is no litigation or administrative proceeding pending against the Borrower that would substantially affect the financial position of the Borrower or their ability to carry on her normal his/her normal business.</li>
                            <li>The Borrower is not in default under any obligation in respect of any borrowed money and that the execution and performance of this loan obligation will not be or result in a breach of or default under any provision of any agreement to which the Borrower is a party</li> 
                        </ol>
                    </div>
                    @if(isset($signature) && $signature->inuse != null)
                    I <b><span class="ddotted"> {{ strtoupper($offer->name) }}</span></b> hereby, unconditionally as i have read and understood the terms with total clarity.<br><br><br>
                    @if($signature->inuse == '1')
                    <div class="inline">Sign <img src="data:image/svg+xml;base64,{!! base64_encode($signature->signature) !!}" style="position: absolute;" width="320" height="140"></div>
                    @else
                    <div class="inline">Sign <img src="data:image/svg+xml;base64,{!! base64_encode($signature->signature_two) !!}" style="position: absolute;" width="320" height="140"></div>
                    @endif
                    <div class="inline" style="float: right;">Date <span class="ddotted">{{ date('d/m/Y', strtotime($signature->created_at)) }}</span></div>
                    @else
                    I ............................................... hereby, unconditionally as i have read and understood the terms with total clarity.<br><br><br>
                    <div class="inline">Sign ...........................................</div>
                    <div class="inline" style="float: right;">Date ...........................................</div>
                    @endif
                    <br><br><br><br><br></li>
                    <li><div class="title">Events of defaults</div>
                        The occurence of any of the following shall cause all outstanding amounts this facility become immediately due and payable
                        <ol class="alpha">
                            <li>The Borrower fails to make a repayment or payment of principal, interest or other amount in respect of the Credit Facility on the date it was due to be paid. </li>
                            <li>If the Borrower defaults in the performance or the observance of any term, condition or covenant herein and such breach or default continues un-remedied after ten days’ notice has been given.</li>
                            <li>If the Borrower’s creditors obtain a court order or injunction against the Borrower’s accounts</li>
                            <li>Default on the interest payment upon falling due would attract interest of {{ $offer->default_charge }}% flat monthly on the unpaid monthly rental.</li>
                            <li>Where the obligor fails to pay a rental by the due date, Swiss Credit Limited reserves the right to recall the facility.</li>
                            <li>The Borrower hereby undertakes to bear all costs and expenses incurred by the Company for the recovery of the loan sum in the event of default.</li>
                            <li>Swiss Credit Ltd at any time within the tenure of the facility or until the facility is liquidated can write, involve a third party with the sole purpose of recovering its money.</li>
                            <li>Swiss Credit reserves the right to reach out to any of your family, friends, colleagues, acquaintances and relatives (contacts), notifying them about your obligations, if you are defaulting in your loan repayment and not responsive and/or abiding to the terms of this agreement.</li>
                            <li><b>Swiss Credit reserves the right to use every legal means possible to recover this loan, which include, visitation to your home and office, seizing/confiscating of your property and also the use of third party.</b></li>
                        </ol>
                    </li>
                    <li><div class="title">Failure or Delay not waived</div>
                        <ol class="alpha">
                            <li>No failure to excercise no delay in exercising on the part of the Company, any right, power or priviledge herein shall operate as a waiver thereof, nor shall any single or partial excercise of the same preclude any other or further exercise therefof or the exercise of any right, power or priviledge or the right or remedies of the company provided by law.</li>
                        </ol>
                    </li>
                    <li><div class="title">Assignment</div>
                        <ol class="alpha">
                            <li>This Offer Letter shall be binding upon the Borrower as well as his respective heirs and assign such that the Borrower shall not assign or transfer its rights or obligations hereunder.<br>Kindly signify your acceptance of this offer by signing and returning the counterpart copy of this letter to the Company. This offer is open for acceptance within 2days of the date of the offer after which it shall lapse</li>
                        </ol>
                    </li>
                </ol>
                <div class="inline">Yours Faithfully,<br>For: SWISS CREDIT Limited<br><br>------------------------------------<br>AUTHORIZED SIGNATORY</div><div class="inline" style="float: right;"><br><br>
                    <br>
                    -------------------------------------<br>AUTHORIZED SIGNATORY</div>
            </div><br><br>
            <div><br><br><br>
                <ol type="a">
                    <li>Upon completion of loan tenure customers are respected to request for their letter of Non-indebtedness and for their repayment channels deactivated with their cheques returned where applicable.</li>
                    <li>Swiss credit would no longer be held liable for safekeeping of cheques one month after the loan has been fully repaid.</li>
                </ol><br><br>
                @if(isset($signature) && $signature->inuse != null)
                I <b><span class="ddotted"> {{ strtoupper($offer->name) }}</span></b> hereby, unconditionally as i have read and understood the terms with total clarity.<br><br><br>
                @if($signature->inuse == '1')
                <div class="inline">Sign <img src="data:image/svg+xml;base64,{!! base64_encode($signature->signature) !!}" style="position: absolute;" width="320" height="140"></div>
                @else
                <div class="inline">Sign <img src="data:image/svg+xml;base64,{!! base64_encode($signature->signature_two) !!}" style="position: absolute;" width="320" height="140"></div>
                @endif
                <div class="inline" style="float: right;">Date <span class="ddotted">{{ date('d/m/Y', strtotime($signature->created_at)) }}</span></div>
                @else
                I ............................................... hereby, unconditionally as i have read and understood the terms with total clarity.<br><br><br>
                <div class="inline">Sign ...........................................</div>
                <div class="inline" style="float: right;">Date ...........................................</div>
                @endif
            </div><br><br>
            <div><br>LETTER OF ACKNOWEDGEMENT<br><br>
                @if(isset($signature) && $signature->inuse != null)
                I <b><span class="ddotted"> {{ strtoupper($offer->name) }}</span></b> a staff of <b><span class="ddotted"> {{ strtoupper($offer->employer) }}</span></b> Hereby undertake to pay the monthly rental as captured beow until i fully liquidate my indebtedness, failing which I authorize Swiss Credit Limited to inform my employers and the payment of the debt should be deducted from my salary forthwith.<br><br>
                @else
                I ____________________ a staff of _____________________ Hereby undertake to pay the monthly rental as captured beow until i fully liquidate my indebtedness, failing which I authorize Swiss Credit Limited to inform my employers and the payment of the debt should be deducted from my salary forthwith.<br><br>
                @endif
                <table class="break">
                    <tr>
                        <th></th>
                        <th>Repayment Date</th>
                        <th>Monthly Outstanding Capital</th>
                        <th>Monthy Interest</th>
                        @if($dpro > 0)
                        <th>Moratorium Interest</th>
                        @endif
                        <th>Monthly Principal Repayment</th>
                        <th>Total Monthy Installment</th>
                    </tr>
                    @for($i=0;$i<$offer->tenor;$i++)
                    <tr>
                        <td>{{$i+1}}</td>
                        <td>
                            {{ Utils::getRepayDate($offer->pay_date, Utils::getTenorLastDay($i-$deductMonth, $offer->updated_at)) }}-{{ ucwords(date('M', strtotime(Utils::getTenorLastDay($i+1-$deductMonth, $offer->updated_at)))) }}-@if($i > 0){{ date('Y', strtotime(Utils::getTenorLastDay($i, $offer->updated_at))) }}@else{{ date('Y') }}@endif
                        </td>
                        <td>
                            {{ number_format($offer->amount_recommended - ($i*($principal_repay)),2) }}
                        </td>
                        <td>{{ number_format($interest_monthly,2) }}</td>
                        @if($dpro > 0)
                        <td>{{ number_format($moratorium / $dten, 2) }}</td>
                        @endif
                        <td>{{ number_format($principal_repay,2) }}</td>
                        <td>{{ number_format($totalRepay,2) }}</td>
                    </tr>
                    @endfor
                </table>
            </div><br><br><br>
            <div>
                @if(isset($signature) && $signature->inuse != null)
                I <b><span class="ddotted"> {{ strtoupper($offer->name) }}</span></b> hereby, unconditionally as i have read and understood the terms with total clarity.<br><br><br>
                @if($signature->inuse == '1')
                <div class="inline">Sign <img src="data:image/svg+xml;base64,{!! base64_encode($signature->signature) !!}" style="position: absolute;" width="320" height="140"></div>
                @else
                <div class="inline">Sign <img src="data:image/svg+xml;base64,{!! base64_encode($signature->signature_two) !!}" style="position: absolute;" width="320" height="140"></div>
                @endif
                <div class="inline" style="float: right;">Date <span class="ddotted">{{ date('d/m/Y', strtotime($signature->created_at)) }}</span></div>
                @else
                I ............................................... hereby, unconditionally as i have read and understood the terms with total clarity.<br><br><br>
                <div class="inline">Sign ...........................................</div>
                <div class="inline" style="float: right;">Date ...........................................</div>
                @endif
            </div><br><br>
        </div>
    </body>
</html>