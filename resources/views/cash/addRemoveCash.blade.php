@extends('layouts.main')

@section('extraStyling')
<link rel="stylesheet" href="{{ asset('css/arabicText.css') }}">
@endsection



@section('content')
<div class="row justify-content-center">
    <div class="col-md">
        <br>
        <div class="card">
            <div class="card-body" style="overflow-x: scroll;
            width: auto;
            white-space: nowrap;">
                <div class="table-responsive-sm">
                    <table class="table ">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center" >نوع المعاملة</th> 
                                <th scope="col" class="text-center">القيمة</th>
                                <th scope="col" class="text-center" >العملة</th>
                                <th scope="col" class="text-center" >التاريخ</th>
                                <th scope="col" class="text-center" >البيان</th>
                                <th scope="col" class="text-center">اجمالي العملة </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <form id="transaction-form" class="form"action="{{route('addRemoveCash')}}" method="post">
                                    <td>
                                        <select class="browser-default" style="height: 38px;" id="typeInput" name="typeInput" required>
                                            <option value="" disabled selected>نوع المعاملة</option>
                                            <option value="add">ايداع</option>
                                            <option value="sub">سحب</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" id="valueInput" name="valueInput" placeholder="القيمة" required style="min-width: 100px;" >
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" id="currencyInput" name="currencyInput" placeholder="العملة" required style="min-width: 100px;" >
                                    </td>
                                    <td>
                                        <input type="date" id="dateInput" name="dateInput" style="height: 38px;" required>     
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
        <div class="card">
            <div class="card-body" style="overflow-x: scroll;width: auto;white-space: nowrap;">
                <div class="table-responsive-sm">
                    <table id="cashTransTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]" >
                        <thead>
                            <tr>
                                <th scope="col" class="text-center" >نوع المعاملة</th> 
                                <th scope="col" class="text-center">القيمة</th>
                                <th scope="col" class="text-center" >العملة</th>
                                <th scope="col" class="text-center" >التاريخ</th>
                                <th scope="col" class="text-center" >البيان</th>
                                <th scope="col" class="text-center">اجمالي العملة </th>
                                <th scope="col" class="text-center">مسح</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cashTransactions as $cashTransaction)
                                <tr>
                                    <th scope="row" class="text-center">{{$cashTransaction->type}}</th>
                                    <td class="text-center">{{$cashTransaction->value}}</td>
                                    <td class="text-center">{{$cashTransaction->currency}}</td>
                                    <td class="text-center">{{$cashTransaction->date}}</td>
                                    <td class="text-center">{{$cashTransaction->note}}</td>
                                    <td class="text-center">{{$cashTransaction->currentTotal}}</td>
                                    <td style="text-align:center">
                                        <a class="btn btn-danger" href="{{route('delCashTransaction',['cashTransaction_id'=>$cashTransaction->id])}}" role="button">Delete</a>
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