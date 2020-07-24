@extends('layouts.main')

@section('extraStyling')
<link rel="stylesheet" href="{{ asset('css/arabicText.css') }}">
@endsection



@section('content')
<Br>
<div class="row">
    <div class="col">
        <div class="card border-dark">
            <div class="card-header bg-dark">
                <h4 class="m-b-0 text-white">Taxes Transactions</h4>
            </div>
            <div class="card-body" style="width: auto;white-space: nowrap;">
                <div class="table-responsive-sm">
                    <table id="taxesTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]" >
                        <thead>
                            <tr>
                                <th scope="col" class="text-center" >نوع المعاملة</th>
                                <th scope="col" class="text-center" >الضريبة</th>
                                <th scope="col" class="text-center">قيمة الايداع</th>
                                <th scope="col" class="text-center">قيمة السحب</th>
                                <th scope="col" class="text-center" >التاريخ</th>
                                <th scope="col" class="text-center" >البيان</th>
                                <th scope="col" class="text-center">الرصيد</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($taxAuthTrans as $TaxesTransaction)
                                <tr>
                                    <td scope="row" class="text-center">{{$TaxesTransaction->type}}</td>
                                    <td class="text-center">{{$TaxesTransaction->taxType}}</td>
                                    <td class="text-center">{{$TaxesTransaction->value_add}}</td>
                                    <td class="text-center">{{$TaxesTransaction->value_sub}}</td>
                                    <td class="text-center">{{$TaxesTransaction->date}}</td>
                                    <td class="text-center">{{$TaxesTransaction->note}}</td>
                                    <td class="text-center">{{$TaxesTransaction->currentBalance}}</td>
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
$('#taxesTable').DataTable({
        "displayLength": 25,
        "processing": true,
        dom: 'Bfrtip',
        buttons: [
                {
                extend: 'excel',
                title: 'Deals-Auto',
                footer: true,
            }
            ]   
    });
    $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass('btn btn-primary mr-1');
</script>
@endsection