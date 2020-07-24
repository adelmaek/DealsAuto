@extends('layouts.queryPage')




@section('queryCardHead')
 Query Partners Transactions 
@endsection

@section('queryCardBody')
<form id="queryTrans" class="form" action="" method="get">
    <div class="row justify-content-center ">
        <div class="col  ">
            <label for="partnerNameInput" class="arabicLabel" style="padding: 10px;">الشريك</label>
        </div>
        <div class="col">
            <label for="fromDateInput" class="arabicLabel" style="padding: 10px;">من تاريخ</label>
        </div>
    </div>
    <div class="row ">
        <div class="col ">                     
                <select class="custom-select custom-select-lg" style="height: 45px;float: right;" id="partnerNameInput" name="partnerNameInput" dir="rtl" required>
                    @if(count($partners)==0)
                        <option value="" disabled selected dir="rtl">اسم الشريك</option>
                    @endif
                    @if(count($partners)>0)
                        <option value="all" selected dir="rtl">جميع الشركاء</option>
                    @endif
                    @foreach($partners as $partner)
                        <option value="{{$partner->name}}">{{$partner->name}}</option>
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
                <button type="button" class="btn waves-effect waves-light btn-dark" style="height: 38px;float: right;" id='applyQuery'>عرض</button>        
        </div>
        <div class="col ">
                <input type="date" class="custom-select custom-select-lg" id="toDate" name="toDate" style="height: 38px;float: right;">
        </div>
    </div>
</form>
@endsection


@section('queryResultCardHead')
Partners Transactions
@endsection

@section('queryResultCardBody')
<div class="table-responsive m-t-40">
    <div class="table-responsive-sm">
        <table id="partnersTransTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]" >
            <thead>
                <tr>
                    <th scope="col" class="text-left" >نوع المعاملة</th>
                    <th scope="col" class="text-left" >اسم الشريك</th>
                    <th scope="col" class="text-left">قيمة الايداع</th>
                    <th scope="col" class="text-left">قيمة السحب</th>
                    <th scope="col" class="text-left" >التاريخ</th>
                    <th scope="col" class="text-left" >البيان</th>
                    <th scope="col" class="text-left">رصيد الشريك</th>
                    <th scope="col" class="text-left">رصيد الشركاء</th>
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
    $('#applyQuery').click(function(){
        
        var partner = $('#partnerNameInput').val();
        if(partner)
        {
            var fromDate = $('#fromDate').val();
            var toDate = $('#toDate').val();
            if(!fromDate)
            {
                fromDate ='empty';
            }
            if(!toDate)
            {
                toDate ='empty';
            }
        }
        
        if ( $.fn.dataTable.isDataTable( '#partnersTransTable' ) ) {
            table = $('#partnersTransTable').DataTable();
            table.destroy();
        }
       var table =  $('#partnersTransTable').DataTable({
           
            "displayLength": 25,
            "processing": true,
            "columns": [
                { "data": "type" },
                {"data" : "partnerName"},
                { "data": "value_add" },
                { "data": "value_sub" },
                { "data": "date" },
                { "data": "note" },
                { "data": "currentPartnerTotal" },
                { "data": "currentAllPartnersTotal" }
            ],
            "ajax": "getQueriedPartnersTrans/" + partner + ',' + fromDate + ',' + toDate,
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