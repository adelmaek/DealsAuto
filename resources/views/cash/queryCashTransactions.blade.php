@extends('layouts.queryPage')

@section('queryJS')
<script src="{{ asset('js\queryCashTransactions.js') }}" defer></script>
@endsection

@section('queryCardHead')
 Query Cash (الخزينة)  
@endsection

@section('queryCardBody')
<form id="queryTrans" class="form" action="" method="get">
    <div class="row justify-content-center ">
        <div class="col  ">
            <label for="currencyInput" class="arabicLabel" style="padding: 10px;">العملة</label>
        </div>
        <div class="col">
            <label for="currencyInput" class="arabicLabel" style="padding: 10px;">من تاريخ</label>
        </div>
    </div>
    <div class="row ">
        <div class="col ">                     
                <select class="custom-select custom-select-lg" style="height: 45px;float: right;" id="currencyInput" name="currencyInput" dir="rtl" required>
                    @if(count($currencies)==0)
                        <option value="" disabled selected dir="rtl">العملة</option>
                    @endif
                    @if(count($currencies)>0)
                        <option value="all" selected dir="rtl">جميع العملات</option>
                    @endif
                    @foreach($currencies as $currency)
                        <option value="{{$currency->currency}}">{{$currency->currency}}</option>
                    @endforeach
                </select>
        </div>
        <div class="col  ">                                
                <input type="date" class="custom-select custom-select-lg" id="fromDate" name="fromDate" style="height: 38px;float: right;">
        </div>
    </div>
    <div class="row ">
        <div class="col ">
            <label for="toDate" class="arabicLabel" style="padding: 10px;float: right;">الي تاريخ</label>
        </div>
    </div>
    <div class="row ">
        <div class="col ">
            @if(count($currencies)>0)
                <button type="button" class="btn waves-effect waves-light btn-dark" style="height: 38px;float: right;" id='applyQuery'>عرض</button>
            @else
            <button type="button" class="btn waves-effect waves-light btn-dark" style="height: 38px;float: right;" id='applyQuery' disabled>عرض</button>
            @endif         
        </div>
        <div class="col ">
                <input type="date" class="custom-select custom-select-lg" id="toDate" name="toDate" style="height: 38px;float: right;">
        </div>
    </div>
</form>
@endsection


@section('queryResultCardHead')
Cash Transactions
@endsection

@section('queryResultCardBody')
<div class="table-responsive m-t-40">
    <div class="table-responsive-sm">
        <table id="cashTransTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]" >
            <thead>
                <tr>
                    <th scope="col" class="text-left" >نوع المعاملة</th> 
                    <th scope="col" class="text-left">القيمة</th>
                    <th scope="col" class="text-left" >العملة</th>
                    <th scope="col" class="text-left" >التاريخ</th>
                    <th scope="col" class="text-left" >البيان</th>
                    <th scope="col" class="text-left">اجمالي العملة </th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
@endsection