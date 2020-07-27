@extends('layouts.main')

@section('extraStyling')
<link rel="stylesheet" href="{{ asset('css/arabicText.css') }}">
<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
<script src="{{ asset('js\queryPurchasesTransactions.js') }}" defer></script>
@endsection



@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <br>
        <div class="card border-dark">
            <div class="card-header bg-dark">
                <h4 class="m-b-0 text-white">Query Purchases Transactions</h4>
            </div>
            <div class="card-body">
                <form id="queryTrans" class="form" action="" method="get">
                    <div class="row justify-content-center ">
                        <div class="col  ">
                            <label for="typeInput" class="arabicLabel" style="padding: 10px;">المورد</label>
                        </div>
                        <div class="col">
                            <label for="fromDateInput" class="arabicLabel" style="padding: 10px;">من تاريخ</label>
                        </div>
                    </div>
                    <div class="row ">
                        <div class="col ">                     
                                <select class="form-control" style="height: 45px;float: right;" id="typeInput" name="typeInput" dir="rtl" required>
                                        <option value="all" selected dir="rtl">جميع المشتريات</option>
                                        <option value="local">محلي</option>
                                        <option value="imported">مستورد</option>
                                </select>
                        </div>
                        <div class="col  ">                                
                                <input type="date" class="form-control" id="fromDate" name="fromDate" style="height: 38px;float: right;">
                        </div>
                    </div>
                    <div class="row ">
                        <div class="col ">
                            <label for="toDate" class="arabicLabel" style="padding: 10px;float: right;">الي تاريخ</label>
                        </div>
                    </div>
                    <div class="row ">
                        <div class="col ">
                         
                            <button type="button" class="btn waves-effect waves-light btn-dark" style="height: 38px;float: right;" id='applyQuery'>عرض</button>        
                        </div>
                        <div class="col ">
                                <input type="date" class="form-control" id="toDate" name="toDate" style="height: 38px;float: right;">
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
        <div class="card border-dark">
            <div class="card-header bg-info">
                <h4 class="m-b-0 text-white">Purchases Transactions</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive m-t-40">
                    <table id="purchasesTransTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]" >
                        <thead>
                            <tr>
                                <th scope="col" class="text-left" >النوع</th> 
                                <th scope="col" class="text-left">التاريخ</th>
                                <th scope="col" class="text-left">القيمة</th>
                                <th scope="col" class="text-left">الرصيد</th>
                                <th scope="col" class="text-left">الفاتورة الملحقة</th>
                                <th scope="col" class="text-left">البيان</th>
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
