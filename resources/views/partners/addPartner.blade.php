@extends('layouts.main')

@section('content')
<div class="row justify-content-center">
    <div class="col-md">
        <br>
        <div class="card border-dark">
            <div class="card-header bg-dark">
                <h4 class="m-b-0 text-white">Add Partner</h4>
            </div>
            <div class="card-body" style="
            width: auto;
            white-space: nowrap;">
                <div class="table-responsive-sm">
                    <table class="table color-bordered-table table-striped full-color-table full-dark-table hover-table ">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center" >اسم الشريك</th> 
                                <th scope="col" class="text-center">الحساب المبدأي</th>
                                <th scope="col" class="text-center">اضافة</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <form id="transaction-form" class="form"action="{{route('addPartner')}}" method="post">
                                    <td style="text-align:center">
                                        <input type="text" class="form-control" id="nameInput" name="nameInput" placeholder="اسم الشريك" required style="min-width: 100px;text-align: center;" >
                                    </td>
                                    <td style="text-align:center">
                                        <input type="number" step="0.01" class="form-control" id="intialBalance" name="intialBalance" required placeholder="الحساب المبدأي" style="min-width: 100px;text-align: center;" >
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
                <h4 class="m-b-0 text-white">Partners</h4>
            </div>
            <div class="card-body" style="width: auto;white-space: nowrap;">
                <div class="table-responsive-sm">
                    <table id="partnersTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]" >
                        <thead>
                            <tr>
                                <th scope="col" class="text-center" >اسم الشريك</th> 
                                <th scope="col" class="text-center">الحساب الحالي</th>
                                <th scope="col" class="text-center">مسح</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($partners as $partner)
                                <tr>
                                    <td scope="row" class="text-center">{{$partner->name}}</td>
                                    <td class="text-center">{{$partner->currentBalance}}</td>
                                    <td style="text-align:center">
                                        <a class="btn btn-danger delete-confirm" style="height:25px;padding: 3px 8px;padding-bottom: 3px;" href="{{route('delPartner',['partner_id'=>$partner->id])}}" role="button">Delete</a>
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
$('#partnersTable').DataTable({
        "displayLength": 25,
        "processing": true,
        dom: 'frtip'
});

</script>
@endsection