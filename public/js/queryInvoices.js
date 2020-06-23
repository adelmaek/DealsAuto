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
            "displayLength": 25,
            "processing": true,
            "columns": [
                { "data": "number" },
                { "data": "supplier_name" },
                { "data": "date" },
                { "data": "total_items_number" },
                { "data": "value" },
                { "data": "note" }
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
