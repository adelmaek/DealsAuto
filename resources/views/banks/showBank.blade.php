@extends('layouts.main')

@section('content')
<br>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Bank Transactions</h4>
                <div class="table-responsive m-t-40">
                    <table id="showBankTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]" >
                        <thead>
                            <tr>
                                <th scope='col' class='text-center' >رقم الحساب</th>
                                <th scope='col' class='text-center' >التاريخ</th>
                                <th scope='col' class='text-center' >نوع المعاملة</th>
                                <th scope='col' class='text-center' >القيمة</th>
                                <th scope='col' class='text-center' >البيان</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $transaction)
                            <tr>
                                <td style="text-align:center">{{$transaction->accountNumber}}</td>
                                <td style="text-align:center">{{$transaction->date}}</td>                                                    
                                <td style="text-align:center">{{$transaction->type}}</td>
                                <td style="text-align:center">{{$transaction->value}}</td>
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
            },
            {
                extend: 'print',
                title: 'Deals-Auto',
                footer: true,
            }
        ]   
    });
    $(' .buttons-print,.buttons-excel').addClass('btn btn-primary mr-1');
</script>
@endsection