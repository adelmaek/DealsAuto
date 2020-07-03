@extends('layouts.main')




@section('content')
<div class="row justify-content-center">
    <div class="col-md">
        <br>
        <div class="card">
            <div class="card-body printableArea" style="overflow-x: 
            width: auto;
            white-space: nowrap;">
                <h3><b>INVOICE</b> <span class="pull-right">#{{$bill->number}}</span></h3>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <div class="pull-left">
                                <h3> &nbsp;<b class="text-danger">Deals Auto</b></h3>        
                        </div>
                        <div class="pull-right text-right">
                            <address>
                                <h3>To,</h3>
                            <h4 class="font-bold">{{$supplier->name}}</h4>
                            <p class="m-t-30"><b>Invoice Date :</b> <i class="fa fa-calendar"></i> {{$bill->date}}</p>
                            </address>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="table-responsive m-t-40" style="clear: both;">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>item</th>
                                        <th class="text-center">Chassis Number</th>
                                        <th class="text-center">Unit Cost</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($billItems as $item)
                                        <tr>
                                            <td>{{$item->name}}</td>
                                            <td class="text-center">{{$item->chassisNumber}}</td>
                                            <td class="text-center"> {{$item->unitCost}}</td>                                        
                                        </tr>
                                    @endforeach                                  
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="pull-right m-t-30 text-right">
                        <p>Sub - Total Value: {{$bill->value}} egp</p>
                        </div>
                        <hr>
                        <div class="clearfix"></div>
                        <div class="pull-right m-t-30 text-right">
                        <p>القيمة المضافة: %{{$bill->addValueTaxes}}</p>
                        </div>                
                        @if($bill->importedTaxes1)                
                            {{-- <div class="clearfix"></div> --}}
                            <div class="pull-right m-t-30 text-right">
                            <p>القيمة المقبولة: %{{$bill->importedTaxes1}}</p>
                            </div>
                        @endif
                        @if($bill->importedTaxes2)
                            {{-- <div class="clearfix"></div> --}}
                            <div class="pull-right m-t-30 text-right">
                            <p>مصروفات مشتريات: %{{$bill->importedTaxes2}}</p>
                            </div>
                        @endif
                        @if($bill->importedTaxes3)
                            {{-- <div class="clearfix"></div> --}}
                            <div class="pull-right m-t-30 text-right">
                            <p>مصروفات بنكية: %{{$bill->importedTaxes3}}</p>
                            </div>
                        @endif
                        @if($bill->importedTaxes4)
                            {{-- <div class="clearfix"></div> --}}
                            <div class="pull-right m-t-30 text-right">
                            <p>جمارك : %{{$bill->importedTaxes4}}</p>
                            </div>
                        @endif
                        @if($bill->importedTaxes5)
                            {{-- <div class="clearfix"></div> --}}
                            <div class="pull-right m-t-30 text-right">
                            <p>جاري مصلحة الضرائب: %{{$bill->importedTaxes5}}</p>
                            </div>
                        @endif
                        <hr>
                        <div class="clearfix"></div>
                        <div class="pull-right m-t-30 text-right">
                            <p>Total taxes: {{$totalTaxes}} %</p>
                            </div>
                        <div class="clearfix"></div>
                        <hr>
                        <div class="pull-right m-t-30 text-right">
                            <p>Total Value: {{$totalWithTaxes}} egp</p>
                            </div>
                            <div class="clearfix"></div>
                        <hr>
                        <div class="text-right">
                            <button id="print" class="btn btn-default btn-outline" type="button"> <span><i class="fa fa-print"></i> Print</span> </button>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extraJS')
<script src="dist/js/pages/jquery.PrintArea.js" type="text/JavaScript"></script>
<script>
    $(document).ready(function() {
        $("#print").click(function() {
            var mode = 'iframe'; //popup
            var close = mode == "popup";
            var options = {
                mode: mode,
                popClose: close
            };
            $("div.printableArea").printArea(options);
        });
    });
</script>
@endsection