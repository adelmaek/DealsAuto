@extends('layouts.main')

@section('extraStyling')
<link rel="stylesheet" href="{{ asset('css/currencyTable.css') }}">
<script src="{{ asset('js\currencyTable.js') }}" defer></script>
@endsection

@section('content')
<br>
<div class="row justify-content-center">
    <div class="card border-dark col-md">
        <div class="card-header bg-dark">
            <h4 class="m-b-0 text-white">Bank Currencies Exchange Rates</h4>
        </div>
        <div class="card-body" style="width: 100%;white-space: nowrap;">
            <!-- Editable table -->
            <form id="currency-form" class="form" action="{{route('homeSubmit')}}" method="post" >
                <div id="table" class="table-editable">
                    <span class="table-add float-right mb-3 mr-2"><a href="#!" class="text-success"><i
                        class="fas fa-plus fa-2x" aria-hidden="true"></i></a>
                    </span>
                    <table class="table table-bordered table-responsive-md table-striped text-center">
                    <thead>
                        <tr>
                        <th class="text-center">Currency Name</th>
                        <th class="text-center">Rate</th>
                        <th class="text-center">Sort</th>
                        {{-- <th class="text-center">Remove</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($currencies as $currency)
                        <tr>                        
                        <td class="pt-3-half" contenteditable="false"><input type="text" class="form-control" readonly value="{{$currency->name}}" style="border-width:0px;border:none;box-shadow: none; text-align: center; " id="nameInput[]" name="nameInput[]" required></td>
                        <td class="pt-3-half" contenteditable="false"><input type="number" class="form-control"  value="{{$currency->rate}}" step="0.01" style="border-width:0px;border:none;box-shadow: none; text-align: center; " id="rateInput[]" name="rateInput[]" required></td>
                        <td class="pt-3-half">
                            <span class="table-up"><a href="#!" class="indigo-text"><i class="fas fa-long-arrow-alt-up"
                                aria-hidden="true"></i></a></span>
                            <span class="table-down"><a href="#!" class="indigo-text"><i class="fas fa-long-arrow-alt-down"
                                aria-hidden="true"></i></a></span>
                        </td>
                        </tr>
                        @endforeach
                    </tbody>
                    </table>
                </div>
                <!-- Editable table -->
                <button type="submit"name="submit" class="btn btn-primary">Submit</button>
                <input type="hidden" name="_token" value="{{Session::token()}}">
            </form>
        </div>
    </div>
</div>
@endsection 

