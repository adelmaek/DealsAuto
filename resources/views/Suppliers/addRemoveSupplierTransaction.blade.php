@extends('layouts.main')

@section('content')
<div class="row justify-content-center">
    <div class="col-md">
        <br>
        <div class="card">
            <div class="card-body" style="overflow-x: scroll;
            width: auto;
            white-space: nowrap;">
                <div class="table-responsive-sm">
                    <table class="table ">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center" >المورد</th>
                                <th scope="col" class="text-center" >التاريخ</th>
                                <th scope="col" class="text-center">القيمة</th>
                                <th scope="col" class="text-center" >البيان</th>
                                <th scope="col" class="text-center">اضافة</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <form id="transaction-form" class="form"action="{{route('insertSupplierTrans')}}" method="post">
                                    <td class="text-center">
                                        <select class="browser-default" style="height: 38px;" id="supplierNameInput" name="supplierNameInput" required>
                                            <option value="" disabled selected>اسم المورد</option>
                                            @foreach($suppliers as $supplier)
                                                <option value="{{$supplier->name}}">{{$supplier->name}}</option>
                                            @endforeach
                                        </select>
                                                                                
                                    </td>
                                    <td class="text-center">
                                        <input type="date" id="dateInput" name="dateInput" style="height: 38px;" required>
                                    </td>
                                    <td class="text-center">
                                        <input type="number" class="form-control" id="valueInput" name="valueInput" placeholder="القيمة" required style="min-width: 100px;" >
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
        <div class="card">
            <div class="card-body" style="overflow-x: scroll;width: auto;white-space: nowrap;">
                <div class="table-responsive-sm">
                    <table id="suppliersTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]" >
                        <thead>
                            <tr>
                                <th scope="col" class="text-center" >المورد</th> 
                                <th scope="col" class="text-center">التاريخ</th>
                                <th scope="col" class="text-center">القيمة</th>
                                <th scope="col" class="text-center">الاجمالي الحالي للمورد</th>
                                <th scope="col" class="text-center">البيان</th>
                                <th scope="col" class="text-center">مسح</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($suppliersTransactions as $trans)
                                <tr>
                                    <th scope="row" class="text-center">{{\App\Supplier::where('id',$trans->supplier_id)->first()->name}}</th>
                                    <td class="text-center">{{$trans->date}}</td>
                                    <td class="text-center">{{$trans->value}}</td>
                                    <td class="text-center">{{$trans->currentSupplierTotal}}</td>
                                    <td class="text-center">{{$trans->note}}</td>
                                    <td style="text-align:center">
                                        <a class="btn btn-danger" href="{{route('delSupplierTrans',['transaction_id'=>$trans->id])}}" role="button">Delete</a>
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
$('#suppliersTable').DataTable({
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