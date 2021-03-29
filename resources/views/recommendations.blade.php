@php
use App\Http\Controllers\Globals as Utils;
@endphp
<!DOCTYPE html>
<html>
<style>
    body {
        font-family: sans-serif;
        font-size: 0.9em;
    }
    .inline {
        display: inline-block;
        vertical-align: top;
    }
    .h4 {
        font-size: 1.5em;
    }
    .hn {
        font-size: 1.2em;
    }
    .border-bottom {
        border-bottom: 1px solid #000;
    }
    .left {
        float: left;
    }
    .right {
        float:right;
    }
    table {
        border-collapse: collapse;
        border: 1px solid #222;
        width: 100%;
    }
    table th {
        border: 1px solid #222;
        border-collapse: collapse;
        padding: 10px 5px;
    }
    table td {
        border: 1px solid #222;
        border-collapse: collapse;
        padding: 10px 5px;
    }
    .around {
        border: 1px solid #000;
        padding: 5px;
    }
    ul li {
        margin-bottom: 30px;
    }
</style>
    <body>
        <div>
            <div class="inline h4 border-bottom">RECOMMENDATION AND APPROVAL SHEET</div>
            <div class="inline h4 right">Date: <span class="border-bottom">{{ date('d M, Y', strtotime($customer->updated_at)) }}</span></div>
            <div style="margin-top: 20px;" class="hn">NAME OF CUSTOMER: <span style="margin-left: 10px;padding-bottom: 10px; padding-right: 50px;" class="border-bottom">{{ strtoupper($customer->firstname.' '.$customer->surname.' '.$customer->middle_name) }}</span></div>
            <ul>
                <li>
                    <span style="font-weight: bolder;"> CREDIT RISK OFFICER</span><br>
                    <div style="margin-top: 10px">
                        <table>
                            <tr>
                                <th>CHECKLIST</th>
                                <th>CHECKBOX</th>
                            </tr>
                            @foreach($creditChecks as $checklist)
                            <tr>
                                <td>{{ $checklist->name }}</td>
                                <td>
                                    @if(Utils::getChecklist($checklist->id,$customer->id))
                                    <center><input type="checkbox" checked="checked"></center>
                                    @elseif(Utils::getChecklistNo($checklist->id,$customer->id))
                                    <span class="around">x</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </li>
                <li>
                    <span style="font-weight: bolder;"> UNDERWRITER</span><br>
                    <div style="margin-top: 10px">
                        <table>
                            <tr>
                                <th>CHECKLIST</th>
                                <th>CHECKBOX</th>
                            </tr>
                            @foreach($underwriterChecks as $checklist)
                            <tr>
                                <td>{{ $checklist->name }}</td>
                                <td>
                                    @if(Utils::getChecklist($checklist->id,$customer->id))
                                    <center><input type="checkbox" checked="checked"></center>
                                    @elseif(Utils::getChecklistNo($checklist->id,$customer->id))
                                    <span class="around">x</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </li>
                <li>
                    <span style="font-weight: bolder;"> HEAD OF RISK</span><br>
                    <div style="margin-top: 10px">
                        <table>
                            <tr>
                                <th>CHECKLIST</th>
                                <th>CHECKBOX</th>
                            </tr>
                            @foreach($riskChecks as $checklist)
                            <tr>
                                <td>{{ $checklist->name }}</td>
                                <td>
                                    @if(Utils::getChecklist($checklist->id,$customer->id))
                                    <center><input type="checkbox" checked="checked"></center>
                                    @elseif(Utils::getChecklistNo($checklist->id,$customer->id))
                                    <span class="around">x</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </li>
                <li>
                    <span style="font-weight: bolder;"> OPERATIONS</span><br>
                    <div style="margin-top: 10px">
                        <table>
                            <tr>
                                <th>CHECKLIST</th>
                                <th>CHECKBOX</th>
                            </tr>
                            @foreach($operationChecks as $checklist)
                            <tr>
                                <td>{{ $checklist->name }}</td>
                                <td>
                                    @if(Utils::getChecklist($checklist->id,$customer->id))
                                    <center><input type="checkbox" checked="checked"></center>
                                    @elseif(Utils::getChecklistNo($checklist->id,$customer->id))
                                    <span class="around">x</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </li>
                <li>
                    <span style="font-weight: bolder;"> FINANCE</span><br>
                    <div style="margin-top: 10px">
                        <table>
                            <tr>
                                <th>CHECKLIST</th>
                                <th>CHECKBOX</th>
                            </tr>
                            @foreach($financeChecks as $checklist)
                            <tr>
                                <td>{{ $checklist->name }}</td>
                                <td>
                                    @if(Utils::getChecklist($checklist->id,$customer->id))
                                    <center><input type="checkbox" checked="checked"></center>
                                    @elseif(Utils::getChecklistNo($checklist->id,$customer->id))
                                    <span class="around">x</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </li>
                <li>
                    <span style="font-weight: bolder;"> INTERNAL CONTROL</span><br>
                    <div style="margin-top: 10px">
                        <table>
                            <tr>
                                <th>CHECKLIST</th>
                                <th>CHECKBOX</th>
                            </tr>
                            @foreach($internalcontrolChecks as $checklist)
                            <tr>
                                <td>{{ $checklist->name }}</td>
                                <td>
                                    @if(Utils::getChecklist($checklist->id,$customer->id))
                                    <center><input type="checkbox" checked="checked"></center>
                                    @elseif(Utils::getChecklistNo($checklist->id,$customer->id))
                                    <span class="around">x</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </li>
                <li>
                    <span style="font-weight: bolder;"> DIRECTOR</span><br>
                    <div style="margin-top: 10px">
                        <table>
                            <tr>
                                <th>CHECKLIST</th>
                                <th>CHECKBOX</th>
                            </tr>
                            @foreach($directorChecks as $checklist)
                            <tr>
                                <td>{{ $checklist->name }}</td>
                                <td>
                                    @if(Utils::getChecklist($checklist->id,$customer->id))
                                    <center><input type="checkbox" checked="checked"></center>
                                    @elseif(Utils::getChecklistNo($checklist->id,$customer->id))
                                    <span class="around">x</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </li>
            </ul>
        </div>
    </body>
</html>