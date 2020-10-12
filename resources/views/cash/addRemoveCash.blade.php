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
                <h4 class="m-b-0 text-white">Add Cash Transaction</h4>
            </div>
            <div class="card-body" style="
            width: auto;
            white-space: nowrap;">
                <div class="table-responsive-sm">
                    <table class="table color-bordered-table table-striped full-color-table full-dark-table hover-table ">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center" >نوع المعاملة</th>
                                <th scope="col" class="text-center" >اسم الخزنة</th>  
                                <th scope="col" class="text-center">القيمة</th>
                                {{-- <th scope="col" class="text-center" >العملة</th> --}}
                                <th scope="col" class="text-center" >التاريخ</th>
                                <th scope="col" class="text-center" >البيان</th>
                                <th scope="col" class="text-center">اضافة </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <form id="transaction-form" class="form"action="{{route('addRemoveCash')}}" method="post">
                                    <td>
                                        <select class="form-control" style="height: 42px;" id="typeInput" name="typeInput" required>
                                            <option value="" disabled selected>نوع المعاملة</option>
                                            <option value="add">ايداع</option>
                                            <option value="sub">سحب</option>
                                            <option value="fromNormalCashToCustodyCash">تمويل العهدة</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control" style="height: 42px;" id="nameInput" name="nameInput" required>
                                            <option value="" disabled selected>اسم الخزنة</option>
                                            <option value="normalCash">الخزنة</option>
                                            <option value="custodyCash">خزنة العهدة</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" class="form-control" id="valueInput" name="valueInput" placeholder="القيمة" required style="min-width: 100px;" >
                                    </td>
                                    <td>
                                        <input type="date" class="form-control" id="dateInput" name="dateInput" style="height: 42px;" required>     
                                    <td>
                                        <input type="text" class="form-control" id="noteInput" name="noteInput" placeholder="البيان" required style="min-width: 100px;overflow:scroll;text-align: right;direction:RTL;">
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
                <h4 class="m-b-0 text-white">Cash Transaction</h4>
            </div>
            <div class="card-body" style="width: auto;white-space: nowrap;">
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
                                <th scope="col" class="text-center">رصيد الخزينة</th>
                                <th scope="col" class="text-center">رصيد الخزن</th>
                                <th scope="col" class="text-center">مسح</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($CashTransactions as $cashTransaction)
                                <tr>
                                    <td  class="text-center">{{$cashTransaction->type}}</td>
                                    @if(!strcmp('normalCash', $cashTransaction->name))
                                        <td class="text-center">الخزنة</td>
                                    @else
                                        <td  class="text-center">خزنة العهدة</td>
                                    @endif
                                    <td class="text-center">{{$cashTransaction->value_add}}</td>
                                    <td class="text-center">{{$cashTransaction->value_sub}}</td>
                                    <td class="text-center">{{$cashTransaction->date}}</td>
                                    <td class="text-center">{{$cashTransaction->note}}</td>
                                    <td class="text-center">{{$cashTransaction->currentCashNameTotal}}</td>
                                    <td class="text-center">{{$cashTransaction->currentAllCashTotal}}</td>
                                    <td style="text-align:center">
                                        <a class="btn btn-danger delete-confirm" href="{{route('delCashTransaction',['cashTransaction_id'=>$cashTransaction->id])}}" style="height:25px;padding: 3px 8px;padding-bottom: 3px;" role="button">Delete</a>
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
$('#cashTransTable').DataTable({
        "displayLength": 25,
        "processing": true,
        dom: 'frtip'
    });
    $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass('btn btn-primary mr-1');
</script>
@endsection