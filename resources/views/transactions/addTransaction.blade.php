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
                <h4 class="m-b-0 text-white">Add Bank Transaction</h4>
            </div>
            <div class="card-body" style="
            width: auto;
            white-space: nowrap;">
                <div class="table-responsive-sm">
                    <table class="table ">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center" >رقم الحساب</th>
                                <th scope="col" class="text-center" >التاريخ</th>
                                <th scope="col" class="text-center" >value Date</th>
                                <th scope="col" class="text-center" >نوع المعاملة</th>
                                <th scope="col" class="text-center">القيمة</th>
                                <th scope="col" class="text-center" >البيان</th>
                                <th scope="col" class="text-center">اضافة\تعديل</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <form id="transaction-form" class="form"action="{{route('insertTransaction')}}" method="post">
                                    <td>
                                        <select class="custom-select custom-select-lg" style="height: 43px;" id="accountNumberInput" name="accountNumberInput" required>
                                            <option value="" disabled selected>رقم الحساب</option>
                                            @foreach($banks as $bank)
                                                <option value="{{$bank->accountNumber}}">{{$bank->bankName}}:{{$bank->accountNumber}}</option>
                                            @endforeach
                                        </select>
                                                                                
                                    </td>
                                    <td>
                                        <input type="date" class= "custom-select custom-select-sm" id="dateInput" name="dateInput" style="height: 40px;" required>
                                    </td>
                                    <td>
                                        <input type="date" class= "custom-select custom-select-sm" id="valueDateInput" name="valueDateInput" style="height: 40px;" required>
                                    </td>
                                    <td>
                                        <select class="custom-select custom-select-lg" style="height: 45px;width: 150px;" id="typeInput" name="typeInput" required>
                                            <option value="" disabled selected>نوع المعاملة</option>
                                            <option value="add">ايداع</option>
                                            <option value="sub">سحب</option>
                                            <option value="addCash">ايداع كاش</option>
                                            <option value="subToNormalCash">تمويل الخزنة</option>
                                            <option value="subToCustodyCash">تمويل العهدة</option>
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
                    <table class="table ">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center" >رقم الحساب</th>
                                <th scope="col" class="text-center" >التاريخ</th>
                                <th scope="col" class="text-center" >value Date</th>
                                <th scope="col" class="text-center" >نوع المعاملة</th>
                                <th scope="col" class="text-center">القيمة</th>
                                <th scope="col" class="text-center" >البيان</th>
                                <th scope="col" class="text-center" >رصيد الحساب</th>
                                <th scope="col" class="text-center" >رصيد البنوك</th>
                                <th scope="col" class="text-center">مسح</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $transaction)
                            <tr>
                                <td style="text-align:center">{{$transaction->accountNumber}}</td>
                                <td style="text-align:center">{{$transaction->date}}</td>
                                <td style="text-align:center">{{$transaction->valueDate}}</td>                                                    
                                <td style="text-align:center">{{$transaction->type}}</td>
                                <td style="text-align:center">{{$transaction->value}}</td>
                                <td style="text-align:center">{{$transaction->note}}</td>
                                <td style="text-align:center">{{$transaction->currentBankBalance}}</td>
                                <td style="text-align:center">{{$transaction->currentAllBanksBalance}}</td>
                                <td style="text-align:center">
                                    <a class="btn btn-danger" href="{{route('delTransaction',['transaction_id'=>$transaction->id, 'accNumber'=>$transaction->accountNumber])}}" role="button">Delete</a>
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