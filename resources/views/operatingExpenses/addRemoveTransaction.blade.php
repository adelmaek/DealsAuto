@extends('layouts.main')

@section('extraStyling')
<link rel="stylesheet" href="{{ asset('css/arabicText.css') }}">
@endsection



@section('content')
<div class="row justify-content-center">
    <div class="col-md">
        <br>
        <div class="card border-dark">
            <div class="card-header bg-dark">
                <h4 class="m-b-0 text-white">Add Operating Expenses Transaction</h4>
            </div>
            <div class="card-body" style="
            width: auto;
            white-space: nowrap;">
                <div class="table-responsive-sm">
                    <table class="table color-bordered-table table-striped full-color-table full-dark-table hover-table ">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center" >النوع</th> 
                                <th scope="col" class="text-center" >المصدر</th> 
                                <th scope="col" class="text-center">القيمة</th>
                                <th scope="col" class="text-center" >التاريخ</th>
                                <th scope="col" class="text-center" >البيان</th>
                                <th scope="col" class="text-center">اضافة </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <form id="transaction-form" class="form"action="{{route('operatingExpenses')}}" method="post">
                                    <td>
                                        <select class="form-control" style="height: 42px;" id="typeInput" name="typeInput" required>
                                            <option value="" disabled selected>النوع</option>
                                            <option value="add">ايداع</option>
                                            <option value="sub">سحب</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control" style="height: 42px;" id="sourceInput" name="sourceInput" required>
                                            <option value="" disabled selected>المصدر</option>
                                            <option value="none">لا يوجد</option>
                                            <option value="custodyCash">خزنة العهدة</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" class="form-control" id="valueInput" name="valueInput" placeholder="القيمة" required style="min-width: 100px;" >
                                    </td>
                                    <td>
                                        <input type="date" class="form-control" id="dateInput" name="dateInput" style="height: 42px;" required>     
                                    <td>
                                        {{-- <input type="text" class="form-control" id="noteInput" name="noteInput" placeholder="البيان" required style="min-width: 100px;overflow:scroll;text-align: right;direction:RTL;"> --}}
                                        <input list="noteInput-list" class="form-control" id="noteInput" name="noteInput" required style="min-width: 100px;overflow:scroll;text-align: right;direction:RTL;">
                                        <datalist id="noteInput-list">
                                            <option value="اثاث و اجهزه"  dir="rtl">اثاث و اجهزه</option>
                                            <option value="صيانه"  dir="rtl">صيانه</option>
                                            <option value="بنزين سياره الشركه"  dir="rtl">بنزين سياره الشركه</option>
                                            <option value="بنزين سيارات"  dir="rtl">بنزين سيارات</option>
                                                                    </datalist>
                                    </td>
                                    <td>
                                        <input type="submit" name="submit" class="btn btn-info btn-md" value="اضف المعاملة">
                                        <input type="hidden" name="_token" value="{{Session::token()}}">
                                    </td>
                                </form>
                            </tr>
                        </tbody>   
                    </table>
                </div>
            </div>
        </div>
        <br>
    </div>
</div>
<div class="row">
    <div class="col">
        <div class="card border-dark">
            <div class="card-header bg-dark">
                <h4 class="m-b-0 text-white">Operating Expenses Transaction</h4>
            </div>
            <div class="card-body" style="width: auto;white-space: nowrap;">
                <div class="table-responsive-sm">
                    <table id="operatingExpensesTransTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]" >
                        <thead>
                            <tr>
                                <th scope="col" class="text-center" >النوع</th> 
                                <th scope="col" class="text-center">قيمة الايداع</th>
                                <th scope="col" class="text-center">قيمة السحب</th>   
                                <th scope="col" class="text-center" >التاريخ</th>
                                <th scope="col" class="text-center" >البيان</th>
                                <th scope="col" class="text-center">الرصيد</th>
                                <th scope="col" class="text-center">مسح</th>   
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $trans)
                                <tr>
                                    <td scope="row" class="text-center">{{$trans->type}}</td>
                                    <td class="text-center">{{$trans->value_add}}</td>
                                    <td class="text-center">{{$trans->value_sub}}</td>
                                    <td class="text-center">{{$trans->date}}</td>
                                    <td class="text-center">{{$trans->note}}</td>
                                    <td class="text-center">{{number_format((float)$trans->currentTotal,2)}}</td>
                                    <td style="text-align:center">
                                        <a class="btn btn-danger delete-confirm" style="height:25px;padding: 3px 8px;padding-bottom: 3px;" href="{{route('delOperatingExpenses',['trans_id'=>$trans->id])}}" role="button">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('extraJS')
<script>
$('#operatingExpensesTransTable').DataTable({
        "displayLength": 25,
        "processing": true,
        dom: 'Bfrtip',
        buttons: [
                {
                extend: 'excel',
                title: 'Deals-Auto',
                footer: true,
            }
            ]   
    });
    $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass('btn btn-primary mr-1');
</script>
@endsection