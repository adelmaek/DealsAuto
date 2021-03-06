@extends('layouts.queryPage')



@section('queryCardHead')
 Query Cash (الخزينة)  
@endsection

@section('queryCardBody')
<form id="queryTrans" class="form" action="" method="get">
    <div class="row justify-content-center ">
        <div class="col  ">
            <label for="cashNameInput" class="arabicLabel" style="padding: 10px;">الحساب</label>
        </div>
        <div class="col">
            <label for="currencyInput" class="arabicLabel" style="padding: 10px;">من تاريخ</label>
        </div>
    </div>
    <div class="row ">
        <div class="col ">                     
            <select class="form-control" style="height: 45px;float: right;" id="cashNameInput" name="cashNameInput" dir="rtl" required>
                <option value="all" selected dir="rtl">جميع الخزن</option>
                <option value="normalCash">الخزنة</option>
                <option value="custodyCash">خزنة العهدة</option>
            </select>
        </div>
        <div class="col  ">                                
            <input type="date" class="form-control" id="fromDate" name="fromDate" style="width:665px;height: 38px;float: right;">
        </div>
    </div>
    <div class="row ">
        <div class="col ">
            <label for="toDate" class="arabicLabel" style="padding: 10px;float: right;">الي تاريخ</label>
        </div>
    </div>
    <div class="row ">
        <div class="col ">            
            <button type="button" class="btn waves-effect waves-light btn-dark" style="height: 38px;float: right;" id='applyCashQuery'>عرض</button>
        </div>
        <div class="col ">
            <input type="date" class="form-control" id="toDate" name="toDate" style="width:665px;height: 38px;float: right;">
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
                    <th scope="col" class="text-center" >نوع المعاملة</th>
                    <th scope="col" class="text-center" >اسم الخزنة</th>
                    <th scope="col" class="text-center">قيمة الايداع</th>
                    <th scope="col" class="text-center">قيمة السحب</th>
                    <th scope="col" class="text-center" >التاريخ</th>
                    <th scope="col" class="text-center" >البيان</th>
                    <th scope="col" class="text-center">رصيد الخزنة</th>
                    <th scope="col" class="text-center">رصيد الخزن</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('queryJS')
<script >
$(document).ready(function(){
    //applyQuery    
    $('#applyCashQuery').click(function(){

        
        var fromDate = $('#fromDate').val();
        var toDate = $('#toDate').val();
        var cashName = $('#cashNameInput').val();
        if(!fromDate)
        {
            fromDate ='empty';
        }
        if(!toDate)
        {
            toDate ='empty';
        }
        
        if ( $.fn.dataTable.isDataTable( '#cashTransTable' ) ) {
            table = $('#cashTransTable').DataTable();
            table.destroy();
        }
       var table =  $('#cashTransTable').DataTable({
           
            "displayLength": 25,
            "processing": true,
            "columns": [
                { "data": "type" },
                {"data" : "name"},
                { "data": "value_add" },
                { "data": "value_sub" },
                { "data": "date" },
                { "data": "note" },
                { "data": "currentCashNameTotal" },
                { "data": "currentAllCashTotal" }
            ],
            "ajax": "getCashQueriedTrans/"+ cashName+ ',' + fromDate + ',' + toDate,
            dom: 'Bfrtip',
            buttons: [
                 {
                    extend: 'excel',
                    title: 'Deals-Auto',
                    footer: true,
                }
            ]   
        });
    });
});
</script>
@endsection