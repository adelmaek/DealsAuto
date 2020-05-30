@extends('layouts.app')

@section('extraStyling')
<link rel="stylesheet" href="{{ asset('css/arabicText.css') }}">
<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
<script src="{{ asset('js\queryTransactions.js') }}" defer></script>
@endsection



@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md">
            <form id="queryTrans" class="form" action="" method="get">
                <div class="row justify-content-center ">
                    <div class="col  ">
                        <label for="accountNumberInput" class="arabicLabel" style="padding: 10px;">الحساب</label>
                    </div>
                    <div class="col">
                        <label for="accountNumberInput" class="arabicLabel" style="padding: 10px;">من تاريخ</label>
                    </div>
                </div>
                <div class="row ">
                    <div class="col ">                     
                            <select class="browser-default" style="height: 38px;float: right;" id="accountNumberInput" name="accountNumberInput" dir="rtl" required>
                                @if(count($banks)==0)
                                    <option value="" disabled selected dir="rtl">رقم الحساب</option>
                                @endif
                                @if(count($banks)>0)
                                    <option value="all" selected dir="rtl">جميع الحسابات</option>
                                @endif
                                @foreach($banks as $bank)
                                    <option value="{{$bank->accountNumber}}">{{$bank->bankName}}:{{$bank->accountNumber}}</option>
                                @endforeach
                            </select>
                    </div>
                    <div class="col  ">                                
                            <input type="date" id="fromDate" name="fromDate" style="height: 38px;float: right;">
                    </div>
                </div>
                <div class="row ">
                    <div class="col ">
                        <label for="toDate" class="arabicLabel" style="padding: 10px;float: right;">الي تاريخ</label>
                    </div>
                </div>
            <div class="row ">
                <div class="col ">
                    @if(count($banks)>0)
                        <input type="button" class="btn btn-info" value='عرض' id='applyQuery' style="padding-left:20px;padding-right:20px;height: 38px;float: right;">
                    @else
                        <input type="button" class="btn btn-info" value='query' id='applyQuery' style="height: 38px;float: right;" disabled>
                    @endif         
                </div>
                <div class="col ">
                        <input type="date" id="toDate" name="toDate" style="height: 38px;float: right;">
                </div>
            </div>
            </form>
            <br>
            <div class="table-responsive-sm">
                <table class="table table-hover" id="transTable">
                    <thead>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection