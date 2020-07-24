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

       
        if ( $.fn.dataTable.isDataTable( '#invoicesTaxesTable' ) ) {
            table = $('#invoicesTaxesTable').DataTable();
            table.destroy();
        }
       var table =  $('#invoicesTaxesTable').DataTable({
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
            var totalValue3 = api
                .column( 3)
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            var totalValue4 = api
                .column( 4)
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            var totalValue5 = api
                .column( 5 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
                
            var totalValue6 = api
                    .column( 6 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
            var totalValue7 = api
                .column( 7 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );    
                var totalValue8 = api
                .column( 8)
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );                     
                    
                // Update footer by showing the total with the reference of the column index 
            $( api.column( 0 ).footer() ).html('Total');
            $( api.column( 3 ).footer() ).html(totalValue3);
            $( api.column( 4 ).footer() ).html(totalValue4);
            $( api.column( 5 ).footer() ).html(totalValue5);
            $( api.column( 6 ).footer() ).html(totalValue6);
            $( api.column( 7 ).footer() ).html(totalValue7);
            $( api.column( 8).footer() ).html(totalValue8);
        },
            "displayLength": 25,
            "processing": true,
            "serverSide": true,
            "columns": [
                { "data": "number" },
                { "data": "supplier_name" },
                { "data": "date" },
                { "data": "addValueTaxes" },
                { "data": "importedTaxes1" },
                { "data": "importedTaxes2" },
                { "data": "importedTaxes3" },
                { "data": "importedTaxes4" },
                { "data": "importedTaxes5" }
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
