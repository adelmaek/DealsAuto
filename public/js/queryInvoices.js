$(document).ready(function(){
    //applyQuery    
    $('#applyQuery').click(function(){

        var supplier = $('#supplierNameInput').val();
        if(supplier)
        {
            var fromDate = $('#fromDate').val();
            var toDate = $('#toDate').val();
            if(!fromDate)
            {
                fromDate ='empty';
            }
            if(!toDate)
            {
                toDate ='empty';
            }
        }

       
        if ( $.fn.dataTable.isDataTable( '#invoicesTransTable' ) ) {
            table = $('#invoicesTransTable').DataTable();
            table.destroy();
        }
       var table =  $('#invoicesTransTable').DataTable({
        "footerCallback": function ( row, data, start, end, display ) 
        {
            var api = this.api(), data;
    
            // converting to interger to find total
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
    
            // computing column Total of the complete result 
            var totalValue = api
                .column( 5 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
                
            // var totalTaxes = api
            //         .column( 6 )
            //         .data()
            //         .reduce( function (a, b) {
            //             return intVal(a) + intVal(b);
            //         }, 0 );
            var totalValueWithTaxes = api
                .column( 6 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );                     
                    
                // Update footer by showing the total with the reference of the column index 
            $( api.column( 0 ).footer() ).html('Total');
            $( api.column( 5 ).footer() ).html(totalValue);
            // $( api.column( 6 ).footer() ).html(totalTaxes);
            $( api.column( 6 ).footer() ).html(totalValueWithTaxes);
        },
            "displayLength": 25,
            "processing": true,
            "serverSide": true,
            "columns": [
                { "data": "number" },
                { "data": "supplier_name" },
                { "data": "date" },
                { "data": "total_items_number" },
                { "data": "note" },
                { "data": "value" },
                // { "data": "totalTaxesValue" },
                { "data": "totalValueWithTaxes" }
            ],
            "ajax": "queiredInvoices/"+ supplier + ',' + fromDate + ',' + toDate,

            dom: 'Bfrtip',
            buttons: [
                 {
                    extend: 'excel',
                    title: 'Deals-Auto',
                    footer: true,
                }
            ]           

        });
        $(' .buttons-print,.buttons-excel').addClass('btn btn-primary mr-1');
        
    });
    
});
