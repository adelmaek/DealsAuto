@extends('layouts.queryPage')

@section('queryCardHead')
Query 
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
            <select class="form-control" style="height: 45px;float: right;" id="specialNoteInput" name="specialNoteInput" dir="rtl" required>
                <option value="all" selected dir="rtl">الجميع</option>
                <option value="اثاث و اجهزه"  dir="rtl">اثاث و اجهزه</option>
                <option value="صيانه"  dir="rtl">صيانه</option>
                <option value="بنزين سياره الشركه"  dir="rtl">بنزين سياره الشركه</option>
                <option value="بنزين سيارات"  dir="rtl">بنزين سيارات</option>
                
                {{-- @foreach($specialNotes as $specialNote)
                    <option value="{{$specialNote->specialNote}}"  dir="rtl">{{$specialNote->specialNote}}</option>
                @endforeach --}}
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
Query Result
@endsection

@section('queryResultCardBody')
<div class="table-responsive m-t-40">
    <div class="table-responsive-sm">
        <table id="opTransTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]" >
            <thead>
                <tr>
                    <th scope="col" class="text-center" >النوع</th> 
                    <th scope="col" class="text-center">قيمة الايداع</th>
                    <th scope="col" class="text-center">قيمة السحب</th>   
                    <th scope="col" class="text-center" >التاريخ</th>
                    <th scope="col" class="text-center" >البيان</th>
                    <th scope="col" class="text-center">الرصيد</th>
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
        var note = $('#specialNoteInput').val();
        console.log(note);
        if(!fromDate)
        {
            fromDate ='empty';
        }
        if(!toDate)
        {
            toDate ='empty';
        }
        
        if ( $.fn.dataTable.isDataTable( '#opTransTable' ) ) {
            table = $('#opTransTable').DataTable();
            table.destroy();
        }
       var table =  $('#opTransTable').DataTable({
           
            "displayLength": 25,
            "processing": true,
            "columns": [
                { "data": "type" },
                { "data": "value_add" },
                { "data": "value_sub" },
                { "data": "date" },
                { "data": "note" },
                { "data": "currentTotal" }
            ],
            "ajax": "getQueriedOperatingExpenses/"+ note+ ',' + fromDate + ',' + toDate,
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