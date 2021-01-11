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
                <h4 class="m-b-0 text-white">Add Purchase Transaction</h4>
            </div>
            <div class="card-body" style="
            width: auto;
            white-space: nowrap;">
                <div class="table-responsive-sm">
                    <table class="table color-bordered-table table-striped full-color-table full-dark-table hover-table">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center" >النوع</th> 
                                <th scope="col" class="text-center">القيمة</th>
                                <th scope="col" class="text-center" >التاريخ</th>
                                <th scope="col" class="text-center" >البيان</th>
                                <th scope="col" class="text-center">اضافة </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <form id="transaction-form" class="form"action="{{route('insertPurchasesTransactions')}}" method="post">
                                    <td>
                                        <select class="form-control" style="height: 42px;" id="typeInput" name="typeInput" required>
                                            <option value="" disabled selected>النوع</option>
                                            <option value="local">محلي</option>
                                            <option value="imported">مستورد</option>
                                            <option value="used">مستعمل</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" step ="0.01" class="form-control" id="valueInput" name="valueInput" placeholder="القيمة" required style="min-width: 100px;" >
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
                <h4 class="m-b-0 text-white">Purchases Transaction</h4>
            </div>
            <div class="card-body" style="width: auto;white-space: nowrap;">
                <div class="table-responsive-sm">
                    <table id="purchaseTransTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]" >
                        <thead>
                            <tr>
                                <th scope="col" class="text-center" >النوع</th> 
                                <th scope="col" class="text-center">القيمة</th>    
                                <th scope="col" class="text-center" >التاريخ</th>
                                <th scope="col" class="text-center" >البيان</th>
                                <th scope="col" class="text-center">الرصيد</th>
                                <th scope="col" class="text-center">الفاتورة الملحقة</th>
                                <th scope="col" class="text-center">مسح</th>   
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purchaseTransactions as $trans)
                                <tr>
                                    @if(!strcmp($trans->type,"local"))
                                        <td scope="row" class="text-center">محلي</td>
                                    @elseif(!strcmp($trans->type,"imported"))
                                        <td scope="row" class="text-center">مستورد</td>
                                    @else
                                        <td scope="row" class="text-center">مستعمل</td>
                                    @endif
                                    <td class="text-center">{{$trans->value}}</td>
                                    <td class="text-center">{{$trans->date}}</td>
                                    <td class="text-center">{{$trans->note}}</td>
                                    <td class="text-center">{{number_format((float)$trans->currentTotal,2)}}</td>
                                    @if ($trans->bill_number != -1)
                                        <td class="text-center">{{$trans->bill_number}}</td>  
                                    @endif
                                    @if ($trans->bill_number == -1)
                                        <td class="text-center">لا يوجد</td>  
                                    @endif
                                    <td style="text-align:center">
                                        <a class="btn btn-danger delete-confirm" style="height:25px;padding: 3px 8px;padding-bottom: 3px;" href="{{route('delPurchaseTransaction',['trans_id'=>$trans->id])}}" role="button">Delete</a>
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
        dom: 'frtip'
    });
    $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass('btn btn-primary mr-1');
</script>
@endsection