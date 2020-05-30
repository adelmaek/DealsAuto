@extends('layouts.app')

@section('extraStyling')
<link rel="stylesheet" href="{{ asset('css/arabicText.css') }}">
@endsection



@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md">
            <div class="table-responsive-sm">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center" >رقم الحساب</th>
                            <th scope="col" class="text-center" >التاريخ</th>
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
                                    <select class="browser-default" style="height: 38px;" id="accountNumberInput" name="accountNumberInput" required>
                                        <option value="" disabled selected>رقم الحساب</option>
                                        @foreach($banks as $bank)
                                            <option value="{{$bank->accountNumber}}">{{$bank->bankName}}:{{$bank->accountNumber}}</option>
                                        @endforeach
                                    </select>
                                                                            
                                </td>
                                <td>
                                    <input type="date" id="dateInput" name="dateInput" style="height: 38px;" required>
                                </td>
                                <td>
                                    {{-- <input type="number" class="form-control" id="accountNumberInput" name="accountNumberInput" placeholder="نوع المعملة" required style="min-width: 100px;"> --}}
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
                                    <input type="text" class="form-control" id="noteInput" name="noteInput" placeholder="البيان" required style="min-width: 100px;overflow:scroll;text-align: right;direction:RTL;">
                                </td>
                                <td>
                                    <input type="submit" name="submit" class="btn btn-info btn-md" value="اضف المعاملة">
                                     <input type="hidden" name="_token" value="{{Session::token()}}">
                                </td>
                                @if(count($transactions)>0 )
                                    @foreach ($transactions as $transaction)
                                        <tr>
                                                <td style="text-align:center">{{$transaction->accountNumber}}</td>
                                                <td style="text-align:center">{{$transaction->date}}</td>
                                                @if(!strcmp($transaction->type,"add"))
                                                    <td style="text-align:center">ايداع</td>
                                                @else
                                                <td style="text-align:center">سحب</td>
                                                @endif
                                                <td style="text-align:center">{{$transaction->value}}</td>
                                                <td style="text-align:center">{{$transaction->note}}</td>
                                                <td style="text-align:center">
                                                    <a class="btn btn-danger" href="{{route('delTransaction',['transaction_id'=>$transaction->id, 'accNumber'=>$transaction->accountNumber])}}" role="button">Delete</a>
                                                </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </form>
                        </tr>
                    </tbody>   
                </table>
            </div>
        </div>
    </div>
</div>
@endsection