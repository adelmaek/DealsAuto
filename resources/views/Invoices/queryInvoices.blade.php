@extends('layouts.queryPage')

@section('queryJS')
<script src="{{ asset('js\queryInvoices.js') }}" defer></script>
@endsection

@section('queryCardHead')
 Query Invoices   
@endsection

@section('queryCardBody')
<form id="queryTrans" class="form" action="" method="get">
    <div class="row justify-content-center ">
        <div class="col  ">
            <label for="supplierNameInput" class="arabicLabel" style="padding: 10px;">المورد</label>
        </div>
        <div class="col">
            <label for="fromDateInput" class="arabicLabel" style="padding: 10px;">من تاريخ</label>
        </div>
    </div>
    <div class="row ">
        <div class="col ">                     
                <select class="form-control" style="height: 45px;float: right;" id="supplierNameInput" name="supplierNameInput" dir="rtl" required>
                    @if(count($suppliers)==0)
                        <option value="" disabled selected dir="rtl">اسم المورد</option>
                    @endif
                    @if(count($suppliers)>0)
                        <option value="all" selected dir="rtl">جميع الموردين</option>
                    @endif
                    @foreach($suppliers as $supplier)
                        <option value="{{$supplier->name}}">{{$supplier->name}}</option>
                    @endforeach
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
            {{-- @if(count($suppliers)>0) --}}
                <button type="button" class="btn waves-effect waves-light btn-dark" style="height: 38px;float: right;" id='applyQuery'>عرض</button>
            {{-- @else
            <button type="button" class="btn waves-effect waves-light btn-dark" style="height: 38px;float: right;" id='applyQuery' disabled>عرض</button>
            @endif          --}}
        </div>
        <div class="col ">
                <input type="date" class="form-control" id="toDate" name="toDate" style="height: 38px;float: right;">
        </div>
    </div>
</form>
@endsection


@section('queryResultCardHead')
Suppliers Invoices
@endsection

@section('queryResultCardBody')
<div class="table-responsive m-t-40">
    <table id="invoicesTransTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]" >
        <thead>
            <tr>
                <th scope="col" class="text-center" >رقم الفاتورة</th>
                <th scope="col" class="text-center" >المورد</th> 
                <th scope="col" class="text-center">التاريخ</th>
                <th scope="col" class="text-center">عدد السيارات</th>
                <th scope="col" class="text-center">البيان</th>
                <th scope="col" class="text-center">الاجمالي</th>
                {{-- <th scope="col" class="text-center">اجمالي الضرائب</th> --}}
                <th scope="col" class="text-center">اجمالي الفاتورة</th>  
            </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot >
            <tr><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr>
        </tfoot>
    </table>
</div>
@endsection