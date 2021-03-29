@php
use App\Http\Controllers\Globals as Utils;
use Illuminate\Support\Collection;
@endphp

@extends('layouts.app')

@section('title', __('Create Transfer Instruction || Swiss Credit Data Management system'))

@section('form', __('active'))
@section('transfer', __('active'))

@section('head')
@endsection

@section('breadcrumb')
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="float-right">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                    <li class="breadcrumb-item active">Create Transfer Instruction</li>
                </ol>
            </div>
            <h4 class="page-title">Create Transfer Instruction</h4>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('generateTransfer') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group col-lg-12">
                        <label>Offer</label> 
                        <select class="form-control" name="offer" required="">
                            <option value="">Please Select</option>
                            @foreach($offers as $offer)
                                @if($offer->d_form != null)
                                    @php
                                    $customer = Utils::getForm($offer->d_form);
                                    @endphp
                                    <option value="{{ $offer->id }}">{{ strtoupper($customer->firstname.' '.$customer->middle_name.' '.$customer->surname) }} -- N{{ number_format($offer->amount_recommended,2) }} -- {{ date('M d, Y', strtotime($offer->created_at)) }}</option>
                                @else
                                    <option value="{{ $offer->id }}">{{ strtoupper($offer->name) }} -- N{{ number_format($offer->amount_recommended,2) }} -- {{ date('M d, Y', strtotime($offer->created_at)) }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-lg-12">
                        <label>Bank</label> 
                        <select class="form-control" name="bank" required="">
                            <option value="">Please Select</option>
                            <option>5620096553</option>
                            <option>5620125440</option>
                            <option>5620126430</option>
                            <option>1015270682</option>
                            <option>1015168815</option>
                            <option>5400321733</option>
                            <option>5400321726</option>
                            <option>1300295334</option>
                        </select>
                    </div>
                    <div class="form-group col-lg-12">
                        <label>Address</label> 
                        <textarea class="form-control" name="address">
                            The Manager,<br>
                            Fidelity Bank PLC,<br>
                            Adetokunbo Ademola Branch,<br>
                            Victoria Island, Lagos.
                        </textarea>
                    </div>
                    <div class="form-group col-lg-12">
                        <label>PV NO</label>
                        <input type="text" name="pv_no" class="form-control">
                    </div> 
                    <button class="btn btn-purple btn-block" type="submit">Generate</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('foot')
<script src="{{ asset('assets/plugins/tinymce/js/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('assets/plugins/tinymce/js/tinymce/jquery.tinymce.min.js') }}"></script>
<script type="text/javascript">
  tinymce.init({
    selector:"textarea",
    themes: "modern",
    skin: "oxide",
    height:300,
    style_formats:[{title:"Bold text",inline:"b"},{title:"Red text",inline:"span",styles:{color:"#ff0000"}},{title:"Red header",block:"h1",styles:{color:"#ff0000"}},{title:"Example 1",inline:"span",classes:"example1"},{title:"Example 2",inline:"span",classes:"example2"},{title:"Table styles"},{title:"Table row 1",selector:"tr",classes:"tablerow1"}]
    });
</script>
@endsection