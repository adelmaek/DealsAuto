@extends('layouts.main')

@section('content')
<br>
<div class="row">
    <div class="col-12">
        <div class="card border-dark">
            <div class="card-header bg-info">
                <h4 class="m-b-0 text-white">Bank Transactions</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive m-t-40">
                    <table id="showBankTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]" >
                        <thead>
                            <tr>
                                <th scope='col' class='text-center' >رقم الحساب</th>
                                <th scope='col' class='text-center' >التاريخ</th>
                                <th scope='col' class='text-center' >Value Date</th>
                                <th scope='col' class='text-center' >قيمة الايداع</th>
                                <th scope='col' class='text-center' >قيمة السحب</th>
                                <th scope='col' class='text-center' >نوع المعاملة</th>
                                <th scope='col' class='text-center' >رصيد الحساب</th>
                                <th scope='col' class='text-center' >رصيد البنوك</th>
                                <th scope='col' class='text-center' >البيان</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $transaction)
                            <tr>
                                <td style="text-align:center">{{$transaction->accountNumber}}</td>
                                <td style="text-align:center">{{$transaction->date}}</td>
                                <td style="text-align:center">{{$transaction->valueDate}}</td>
                                <td style="text-align:center">{{$transaction->value_add}}</td>
                                <td style="text-align:center">{{$transaction->value_sub}}</td>                                                    
                                <td style="text-align:center">{{$transaction->type}}</td>
                                {{-- <td style="text-align:center">{{$transaction->value}}</td> --}}
                                <td style="text-align:center">{{$transaction->currentBankBalance}}</td>
                                <td style="text-align:center">{{$transaction->currentAllBanksBalance}} egp</td>
                                <td style="text-align:center">{{$transaction->note}}</td>
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
$('#showBankTable').DataTable({
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
    $('.buttons-excel').addClass('btn btn-primary mr-1');
</script>
@endsection