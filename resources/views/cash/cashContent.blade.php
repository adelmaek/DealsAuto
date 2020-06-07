@extends('layouts.main')

@section('extraStyling')
<link rel="stylesheet" href="{{ asset('css/arabicText.css') }}">
@endsection


@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <br>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title " style="display: block;text-align: center; font-size: 30px;">محتوى الخزينة </h4>
                <table id="cashContentTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]" >
                    <thead>
                        <tr>
                            <th scope="col" class="text-center">القيمة</th>
                            <th scope="col" class="text-center" >العملة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cashContent as $item)
                        <tr>
                            <th scope="row" class="text-center">{{$item->value}}</th>
                            <td class="text-center">{{$item->currency}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection