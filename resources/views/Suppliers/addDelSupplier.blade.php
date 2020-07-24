@extends('layouts.main')

@section('content')
<div class="row justify-content-center">
    <div class="col-md">
        <br>
        <div class="card border-dark">
            <div class="card-header bg-dark">
                <h4 class="m-b-0 text-white">Add Supplier</h4>
            </div>
            <div class="card-body" style="
            width: auto;
            white-space: nowrap;">
                <div class="table-responsive-sm">
                    <table class="table ">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center" >اسم المورد</th> 
                                <th scope="col" class="text-center">قيمة التعاملات الحالية</th>
                                <th scope="col" class="text-center">اضافة</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <form id="transaction-form" class="form"action="{{route('insertSupplier')}}" method="post">
                                    <td style="text-align:center">
                                        <input type="text" class="form-control" id="nameInput" name="nameInput" placeholder="اسم المورد" required style="min-width: 100px;text-align: center;" >
                                    </td>
                                    <td style="text-align:center">
                                        <input type="number" class="form-control" id="totalTransInput" name="totalTransInput" required placeholder="قيمة التعاملات الحالية" style="min-width: 100px;text-align: center;" >
                                    </td>
                                    <td style="text-align:center">
                                        <input type="submit" name="submit" class="btn btn-info btn-md" value="اضف">
                                        <input type="hidden" name="_token" value="{{Session::token()}}">
                                    </td>
                                </form>
                            </tr>
                        </tbody>   
                    </table>
                </div>
            </div>
        </div>
        <br>
    </div>
</div>
<br>
<div class="row">
    <div class="col">
        <div class="card border-dark">
            <div class="card-header bg-info">
                <h4 class="m-b-0 text-white">Suppliers</h4>
            </div>
            <div class="card-body" style="width: auto;white-space: nowrap;">
                <div class="table-responsive-sm">
                    <table id="suppliersTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]" >
                        <thead>
                            <tr>
                                <th scope="col" class="text-center" >اسم المورد</th> 
                                <th scope="col" class="text-center">قيمة التعاملات الحالية</th>
                                <th scope="col" class="text-center">مسح</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($suppliers as $supplier)
                                <tr>
                                    <th scope="row" class="text-center">{{$supplier->name}}</th>
                                    <td class="text-center">{{$supplier->currentBalance}}</td>
                                    <td style="text-align:center">
                                        <a class="btn btn-danger delete-confirm" href="{{route('delSupplier',['supplier_id'=>$supplier->id])}}" role="button">Delete</a>
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
        dom: 'frtip'
});

</script>
@endsection