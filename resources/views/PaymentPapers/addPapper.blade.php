@extends('layouts.main')

@section('content')
<div class="row justify-content-center">
    <div class="col-md">
        <br>
        <div class="card border-dark">
            <div class="card-header bg-dark">
                <h4 class="m-b-0 text-white">Add Check</h4>
            </div>
            <div class="card-body" style="
            width: auto;
            white-space: nowrap;">
                <div class="table-responsive-sm">
                    <table class="table color-bordered-table table-striped full-color-table full-dark-table hover-table ">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center" >المورد</th>                                
                                <th scope="col" class="text-center" >التاريخ</th>
                                <th scope="col" class="text-center" >تاريخ الصرف</th>
                                <th scope="col" class="text-center" >البنك</th>
                                <th scope="col" class="text-center">القيمة</th>
                                <th scope="col" class="text-center" >البيان</th>
                                <th scope="col" class="text-center">اضافة</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <form id="transaction-form" class="form"action="{{route('PaymentPapers')}}" method="post">
                                    <td class="text-center">
                                        <select class="form-control" style="height: 38px;" id="supplierNameInput" name="supplierNameInput" required>
                                            <option value="" disabled selected>اسم المورد</option>
                                            @foreach($suppliers as $supplier)
                                                <option value="{{$supplier->name}}">{{$supplier->name}}</option>
                                            @endforeach
                                        </select>                                           
                                    </td>
                                    <td class="text-center">
                                        <input type="date" class="form-control" id="dateInput" name="supplierDateInput" style="height: 38px;" required>
                                    </td>
                                    <td class="text-center">
                                        <input type="date" class="form-control" id="dateInput" name="bankDateInput" style="height: 38px;" required>
                                    </td>
                                    <td class="text-center">
                                        <select class="form-control" style="height: 38px;" id="sourceInput" name="sourceInput" required>
                                            <option value="none" selected>لا يوجد</option>
                                            @foreach($banks as $bank)
                                                <option value="{{$bank->accountNumber}}">{{$bank->bankName}}:{{$bank->accountNumber}}</option>
                                            @endforeach
                                        </select>                                           
                                    </td>
                                    <td class="text-center">
                                        <input type="number" step ="0.01" class="form-control" id="valueInput" name="valueInput" placeholder="القيمة" required style="min-width: 100px;" >
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control" id="noteInput" name="noteInput" placeholder="البيان" required style="min-width: 100px;overflow:scroll;text-align: right;direction:RTL;">
                                    </td>
                                    <td class="text-center">
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
<br>
<div class="row">
    <div class="col">
        <div class="card border-dark">
            <div class="card-header bg-info">
                <h4 class="m-b-0 text-white">Suppliers Transactions</h4>
            </div>
            <div class="card-body" style="width: auto;white-space: nowrap;">
                <div class="table-responsive-sm">
                    <table id="transTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]" >
                        <thead>
                            <tr>
                                <th scope="col" class="text-center" >المورد</th> 
                                <th scope="col" class="text-center">التاريخ</th>
                                <th scope="col" class="text-center">المصدر</th>
                                <th scope="col" class="text-center" >تاريخ الصرف</th>
                                <th scope="col" class="text-center">القيمة</th>
                                <th scope="col" class="text-center">البيان</th>
                                <th scope="col" class="text-center">صرف</th> 
                                <th scope="col" class="text-center">مسح</th> 
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $trans)
                                <tr>
                                    <td scope="row" class="text-center">{{$trans->supplierName}}</td>
                                    <td class="text-center">{{$trans->creationDate}}</td>
                                    <td class="text-center">{{$trans->bankAccountNumber}}</td>
                                    <td class="text-center">{{$trans->settleDate}}</td>
                                    <td class="text-center">{{$trans->value}}</td>
                                    <td class="text-center">{{$trans->note}}</td>
                                    <td style="text-align:center">
                                        <a class="btn btn-success " style="height:25px;padding: 3px 8px;padding-bottom: 3px;" href="{{route('settlePaper',['trans_id'=>$trans->id])}}" role="button">Settle</a>
                                    </td>
                                    <td style="text-align:center">
                                        <a class="btn btn-danger delete-confirm" style="height:25px;padding: 3px 8px;padding-bottom: 3px;" href="{{route('delPaper',['trans_id'=>$trans->id])}}" role="button">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                              <td>Total</td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td>{{$totalPending}} egp</td>
                              <td></td>
                              <td></td>
                              <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extraJS')
<script>
$('#transTable').DataTable({
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