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
                <h4 class="m-b-0 text-white">Settled Checks</h4>
            </div>
            <div class="card-body" style="width: auto;white-space: nowrap;">
                <div class="table-responsive-sm">
                    <table id="papersTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]" >
                        <thead>
                            <tr>
                                <th scope="col" class="text-center" >المورد</th> 
                                <th scope="col" class="text-center">التاريخ</th>
                                <th scope="col" class="text-center">المصدر</th>
                                <th scope="col" class="text-center" >تاريخ الصرف</th>
                                <th scope="col" class="text-center">القيمة</th>
                                <th scope="col" class="text-center">البيان</th>
                                <th scope="col" class="text-center">مسح</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($papers as $trans)
                                <tr>
                                    <td scope="row" class="text-center">{{$trans->supplierName}}</td>
                                    <td class="text-center">{{$trans->creationDate}}</td>
                                    <td class="text-center">{{$trans->bankAccountNumber}}</td>
                                    <td class="text-center">{{$trans->settleDate}}</td>
                                    <td class="text-center">{{number_format((float)$trans->value,2)}}</td>
                                    <td class="text-center">{{$trans->note}}</td>
                                    <td style="text-align:center">
                                        <a class="btn btn-danger delete-confirm" style="height:25px;padding: 3px 8px;padding-bottom: 3px;" href="{{route('delPaper',['trans_id'=>$trans->id])}}" role="button">Delete</a>
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
$('#papersTable').DataTable({
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