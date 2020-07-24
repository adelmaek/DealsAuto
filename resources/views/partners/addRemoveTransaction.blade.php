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
                <h4 class="m-b-0 text-white">Add Partner Transaction</h4>
            </div>
            <div class="card-body" style="
            width: auto;
            white-space: nowrap;">
                <div class="table-responsive-sm">
                    <table class="table ">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center" >اسم الشريك</th>
                                <th scope="col" class="text-center" >المصدر\الوجهة</th>
                                <th scope="col" class="text-center" >التاريخ</th>
                                <th scope="col" class="text-center" >نوع المعاملة</th>
                                <th scope="col" class="text-center">القيمة</th>
                                <th scope="col" class="text-center" >البيان</th>
                                <th scope="col" class="text-center">اضافة</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <form id="transaction-form" class="form"action="{{route('addPartnerTrans')}}" method="post">
                                    <td>
                                        <select class="custom-select custom-select-lg" style="height: 43px;" id="partnerInput" name="partnerInput" required>
                                            <option value="" disabled selected>اسم الشريك</option>
                                            @foreach($partners as $partner)
                                                <option value="{{$partner->name}}">{{$partner->name}}</option>
                                            @endforeach
                                        </select>
                                                                                
                                    </td>
                                    <td>
                                        <select class="custom-select custom-select-lg" style="height: 43px;" id="sourceInput" name="sourceInput" required>
                                            <option value="none" selected>لا يوجد</option>
                                            <option value="normalCash">الخزنة</option>
                                            <option value="custodyCash">خزنة العهدة</option>
                                            @foreach($banks as $bank)
                                                <option value="{{$bank->accountNumber}}">{{$bank->bankName}}:{{$bank->accountNumber}}</option>
                                            @endforeach
                                        </select>
                                                                                
                                    </td>
                                    <td>
                                        <input type="date" class= "custom-select custom-select-sm" id="dateInput" name="dateInput" style="height: 40px;" required>
                                    </td>
                                    <td>
                                        <select class="custom-select custom-select-lg" style="height: 45px;width: 150px;" id="typeInput" name="typeInput" required>
                                            <option value="" disabled selected>نوع المعاملة</option>
                                            <option value="add">ايداع</option>
                                            <option value="sub">سحب</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" id="valueInput" name="valueInput" placeholder="القيمة" required style="min-width: 100px;" >
                                    </td>
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
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-md">
        <br>
        <div class="card border-dark">
            <div class="card-header bg-info">
                <h4 class="m-b-0 text-white">Banks Transactions</h4>
            </div>
            <div class="card-body" style="
            width: auto;
            white-space: nowrap;">
                <div class="table-responsive-sm">
                    <table id="partnerTransTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]" >
                        <thead>
                            <tr>
                                <th scope="col" class="text-center" >اسم الشريك</th>
                                <th scope="col" class="text-center" >التاريخ</th>
                                <th scope="col" class="text-center" >نوع المعاملة</th>
                                <th scope="col" class="text-center">قيمة الايداع</th>
                                <th scope="col" class="text-center">قيمة السحب</th>
                                <th scope="col" class="text-center" >البيان</th>
                                <th scope="col" class="text-center" >رصيد الشريك</th>
                                <th scope="col" class="text-center" >رصيد الشركاء</th>
                                <th scope="col" class="text-center">مسح</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $transaction)
                            <tr>
                                <td style="text-align:center">{{$transaction->partnerName}}</td>
                                <td style="text-align:center">{{$transaction->date}}</td>                                                 
                                <td style="text-align:center">{{$transaction->type}}</td>
                                <td style="text-align:center">{{$transaction->value_add}}</td>
                                <td style="text-align:center">{{$transaction->value_sub}}</td>
                                <td style="text-align:center">{{$transaction->note}}</td>
                                <td style="text-align:center">{{$transaction->currentPartnerTotal}}</td>
                                <td style="text-align:center">{{$transaction->currentAllPartnersTotal}}</td>
                                <td style="text-align:center">
                                    <a class="btn btn-danger delete-confirm" style="height:25px;padding: 3px 8px;padding-bottom: 3px;" href="{{route('delPartnerTrans',['trans_id'=>$transaction->id])}}" role="button">Delete</a>
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
$('#partnerTransTable').DataTable({
        "displayLength": 25,
        "processing": true,
        dom: 'frtip'
    });
    $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass('btn btn-primary mr-1');
</script>
@endsection