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
                <h4 class="m-b-0 text-white">Add General Expenses Transaction</h4>
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
                                <form id="transaction-form" class="form"action="{{route('generalExpenses')}}" method="post">
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
                                            <option value="normalCash">الخزنة</option>
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
                                            <option value="عملاء"  dir="rtl">عملاء</option>
                                            <option value="انتقالات موظفين الشركه"  dir="rtl">انتقالات موظفين الشركه</option>
                                            <option value="مرتبات و شهريات"  dir="rtl">مرتبات و شهريات</option>
                                            <option value="عمولات"  dir="rtl">عمولات</option>
                                            <option value="شهادات بيانات و ترخيص سيارات"  dir="rtl">شهادات بيانات و ترخيص سيارات</option>
                                            <option value="مصروفات احضار سيارات"  dir="rtl">مصروفات احضار سيارات</option>
                                            <option value="كهرباء"  dir="rtl">كهرباء</option>
                                            <option value="مياه"  dir="rtl">مياه</option>
                                            <option value="فواتير الغاز"  dir="rtl">فواتير الغاز</option>
                                            <option value="تليفونات ارضيه الشركه"  dir="rtl">تليفونات ارضيه الشركه</option>
                                            <option value="رسوم و اعلانات"  dir="rtl">رسوم و اعلانات</option>
                                            <option value="تأمينات اجتماعيه"  dir="rtl">تأمينات اجتماعيه</option>
                                            <option value="انترنت و فواتير موبيلات"  dir="rtl">انترنت و فواتير موبيلات</option>
                                            <option value="ادوات مكتبيه و طباعه"  dir="rtl">ادوات مكتبيه و طباعه</option>
                                            <option value="اكراميات و هدايا"  dir="rtl">اكراميات و هدايا</option>
                                            <option value="بوفيه موظفين"  dir="rtl">بوفيه موظفين</option>
                                            <option value="نظافه الشركه"  dir="rtl">نظافه الشركه</option>
                                            <option value="بوفيه الاداره"  dir="rtl">بوفيه الاداره</option>
                                            <option value="ايداعات بنوك"  dir="rtl">ايداعات بنوك</option>
                                            <option value="ايجار الشقه و المخزن"  dir="rtl">ايجار الشقه و المخزن</option>
                                            <option value="صندوق"  dir="rtl">صندوق</option>
                                            <option value="سلفة"  dir="rtl">سلفة</option>
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
                <h4 class="m-b-0 text-white">General Expenses Transaction</h4>
            </div>
            <div class="card-body" style="width: auto;white-space: nowrap;">
                <div class="table-responsive-sm">
                    <table id="purchaseTransTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]" >
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
                                    <td class="text-center">{{$trans->currentTotal}}</td>
                                    <td style="text-align:center">
                                        <a class="btn btn-danger delete-confirm" style="height:25px;padding: 3px 8px;padding-bottom: 3px;" href="{{route('delGeneralExpenses',['trans_id'=>$trans->id])}}" role="button">Delete</a>
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
$('#purchaseTransTable').DataTable({
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