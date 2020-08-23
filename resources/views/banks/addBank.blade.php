@extends('layouts.main')

@section('extraStyling')
<link rel="stylesheet" href="{{ asset('css/arabicText.css') }}">
@endsection



@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <br>
            <div class="card border-dark">
                <div class="card-header bg-dark">
                    <h4 class="m-b-0 text-white">Add Bank</h4>
                </div>
                <div class="card-body">
                    <form id="Bank-form" class="form"action="{{route('insertBank')}}" method="post" >
                        <div class="form-group">
                        <label for="accountNumberInput" class="arabicLabel" >رقم الحساب</label>
                        <input type="text" class="form-control" id="accountNumberInput" name="accountNumberInput" placeholder="ادخل رقم الحساب" required>
                        {{-- <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> --}}
                        </div>
                        <div class="form-group">
                        <label for="bankNameInput" class="arabicLabel">اسم البنك</label>
                        <input type="text" class="form-control" id="bankNameInput" name="bankNameInput" placeholder="ادخل اسم البنك" required>
                        </div>
                        <div class="form-group">
                            <label for="currencyInput" class="arabicLabel">العملة</label>
                            {{-- <input type="text" class="form-control" id="currencyInput" name="currencyInput" placeholder="ادخل العملة" required> --}}
                            <select class="custom-select custom-select-lg"  id="currencyInput" name="currencyInput" required>
                                    <option value="egp" selected >egp</option>
                                @foreach($currencies as $currency)
                                    <option value="{{$currency->name}}">{{$currency->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="balanceInput" class="arabicLabel">الحساب الحالي</label>
                            <input type="number" class="form-control" id="balanceInput" name="balanceInput" placeholder="ادخل الحساب الحالي" required>
                        </div>
                        <input type="submit" name="submit" class="btn btn-info btn-md" value="اضف الحساب">
                        <input type="hidden" name="_token" value="{{Session::token()}}">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card border-dark">
                <div class="card-header bg-info">
                    <h4 class="m-b-0 text-white">Banks</h4>
                </div>
                <div class="card-body">
                    @if(count($banks)>0 )
                        <div class="table-responsive-sm">
                            <table class="table color-bordered-table table-striped full-color-table full-info-table hover-table">
                                <thead>
                                    <tr>
                                    <th scope="col">رقم الحساب</th>
                                    <th scope="col">اسم البنك</th>
                                    <th scope="col">الحساب الحالي</th>
                                    <th scope="col">العملة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($banks as $bank)
                                    <tr>
                                        <td scope="row">{{$bank->accountNumber}}</td>
                                        <td>{{$bank->bankName}}</td>
                                        <td>{{$bank->currentBalance}}</td>
                                        <td>{{$bank->currency}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                  <td>Total Balances</td>
                                  <td></td>
                                  <td>{{$totalBalances}} egp</td>
                                </tr>
                              </tfoot>
                            </table>
                        </div>
                    @else
                        <p>لا يوجد حسابات بنكية</p>
                    @endif
                </div>
            </div>        
        </div>
    </div>
</div>
@endsection 
