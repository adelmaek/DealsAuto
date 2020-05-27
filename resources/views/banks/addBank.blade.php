@extends('layouts.app')

@section('extraStyling')
<link rel="stylesheet" href="{{ asset('css/arabicText.css') }}">
@endsection



@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form id="Bank-form" class="form"action="{{route('insertBank')}}" method="post" >
                <div class="form-group">
                  <label for="accountNumberInput" class="arabicLabel">رقم الحساب</label>
                  <input type="number" class="form-control" id="accountNumberInput" name="accountNumberInput" placeholder="ادخل رقم الحساب" required>
                  {{-- <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> --}}
                </div>
                <div class="form-group">
                  <label for="bankNameInput" class="arabicLabel">اسم البنك</label>
                  <input type="text" class="form-control" id="bankNameInput" name="bankNameInput" placeholder="ادخل اسم البنك" required>
                </div>
                <div class="form-group">
                    <label for="currencyInput" class="arabicLabel">العملة</label>
                    <input type="text" class="form-control" id="currencyInput" name="currencyInput" placeholder="ادخل العملة" required>
                </div>
                <div class="form-group">
                    <label for="balanceInput" class="arabicLabel">الحساب الحالي</label>
                    <input type="number" class="form-control" id="balanceInput" name="balanceInput" placeholder="ادخل الحساب الحالي" required>
                </div>
                <input type="submit" name="submit" class="btn btn-info btn-md" value="submit">
                <input type="hidden" name="_token" value="{{Session::token()}}">
              </form>
              
        </div>
    </div>
</div>
@endsection 
