@extends('layouts.main')

@section('content')


<div class="row justify-content-center">
    <div class="col-md">
        <br>
        <div class="row">
            <div class="col-md-12">
                <div class="card border-dark">
                    <div class="card-header bg-dark">
                        <h4 class="m-b-0 text-white">Add Invoice</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">  
                            <form name="add_name" id="add_name" action="{{route('addNewInvoice')}}">
                                <div class="container">
                                    <div class ="row align-items-start" style="padding-top:10px;">
                                    <div class="col">
                                        <input type="number" name="invoiceNumberInput"  style="height: 38px;width: 200px; margin-left: 17px;" placeholder="Enter invoice number" class="form-control item_cost_list" required /> 
                                    </div>
                                    <div class="col">
                                            <select class="browser-default form-control" style="height: 38px;width: 200px; margin-left: 0px;" id="supplierInput" name="supplierInput" required>
                                                <option value="-1" disabled selected >اسم المورد</option>
                                                @foreach($suppliers as $supplier)
                                                    <option value="{{$supplier->name}}">{{$supplier->name}}</option>
                                                @endforeach
                                            </select>
                                    </div>
                                    <div class="col">
                                        <select class="browser-default form-control" style="height: 38px;width: 200px; margin-left: 0px;" id="typeInput" name="typeInput" required>
                                            <option value="-1" disabled selected >النوع</option>                                            
                                                <option value="local">محلي</option>
                                                <option value="imported">مستورد</option>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <input class ="form-control" type="date" id="dateInput" name="dateInput" style="height: 38px;" required>
                                    </div>
                                    </div>
                                    <div class="row align-items-start" style="padding-top:10px;">
                                        <div class="col">
                                            <input type="text" name="noteInput"  style="height: 38px;width: 500px; margin-left: 17px;" placeholder="Enter invoice note" class="form-control item_cost_list" required />
                                        </div>
                                    </div>
                                    <div class="row align-items-start">
                                        <div class="col">
                                            <div class="table-responsive">  
                                                <table class="table table-borderless " id="dynamic_field" name="dynamic_field">  
                                                    <tr>  
                                                        <td><input type="text"  name="item_name[]" placeholder="Enter item name" class="form-control item_name_list" required /></td> 
                                                        <td><input type="number" name="item_quantity[]" placeholder="Enter item quantity" class="form-control item_quantity_list" required /></td> 
                                                        <td><input type="number" name="item_cost[]" placeholder="Enter item cost" class="form-control item_cost_list" required /></td> 
                                                        <td><button type="button" name="add" id="add" class="btn btn-success" >+</button></td>  
                                                    </tr>  
                                                </table> 
                                                <input type="submit" name="submit" style="margin-left:17px;" id="submit" class="btn btn-info" value="Submit" /> 
                                            <input type="hidden" name="_token" value="{{Session::token()}}">
                                            </div>         
                                        </div>
                                    </div>
                                </div>                   
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md">
        <br>
        <div class="row">
            <div class="col-md-12">
                <div class="card border-dark">
                    <div class="card-header bg-info">
                        <h4 class="m-b-0 text-white">Invoices</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive-sm">
                            <table id="invoiceTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]" >
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center" >Invoice Number</th> 
                                        <th scope="col" class="text-center">show</th>
                                        <th scope="col" class="text-center">delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bills as $bill)
                                        <tr>
                                            <th scope="row" class="text-center">{{$bill->number}}</th>
                                            <td style="text-align:center">
                                                <a class="btn btn-info" href="{{route('showInvoice',['bill_number'=>$bill->number])}}" role="button">show</a>
                                            </td>
                                            <td style="text-align:center">
                                                <a class="btn btn-danger" href="{{route('delInvoice',['bill_number'=>$bill->number])}}" role="button">Delete</a>
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
    </div>
</div>
            
@endsection

@section('extraJS')
<script>  
    $(document).ready(function(){  
         var i=1;  
         $('#add').click(function(){  
              i++;  
              $('#dynamic_field').append('<tr id="row'+i+'"><td><input type="text" name="item_name[]" placeholder="Enter your item name" class="form-control item_name_list" /></td><td><input type="text" name="item_quantity[]" placeholder="Enter item quantity" class="form-control item_quantity_list" /></td> <td><input type="number" name="item_cost[]" placeholder="Enter item cost" class="form-control item_cost_list" required /></td> <td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
         });  
         $(document).on('click', '.btn_remove', function(){  
              var button_id = $(this).attr("id");   
              $('#row'+button_id+'').remove();  
         });  
    });  
</script>
<script>
    $('#invoiceTable').DataTable({
            "displayLength": 25,
            "processing": true,
            dom: 'frtip'
        });
</script>
@endsection