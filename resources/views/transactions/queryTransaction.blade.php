@extends('layouts.main')

@section('extraStyling')
<link rel="stylesheet" href="{{ asset('css/arabicText.css') }}">
<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
<script src="{{ asset('js\queryTransactions.js') }}" defer></script>
@endsection



@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <br>
        <div class="card">
            <div class="card-body">
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
                                <select class="custom-select custom-select-lg" style="height: 45px;float: right;" id="accountNumberInput" name="accountNumberInput" dir="rtl" required>
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
                            @if(count($banks)>0)
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
            </div>
        </div>
    </div>
</div>
 <br>
 <div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Banks Transactions</h4>
                <div class="table-responsive m-t-40">
                    <table id="transTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]" >
                        <thead>
                            <tr>
                                <th scope='col' class='text-center' >رقم الحساب</th>
                                <th scope='col' class='text-center' >التاريخ</th>
                                <th scope='col' class='text-center' >نوع المعاملة</th>
                                <th scope='col' class='text-center' >القيمة</th>
                                <th scope='col' class='text-center' >البيان</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
