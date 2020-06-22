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

       
        if ( $.fn.dataTable.isDataTable( '#supplierTransTable' ) ) {
            table = $('#supplierTransTable').DataTable();
            table.destroy();
        }
       var table =  $('#supplierTransTable').DataTable({
            "displayLength": 25,
            "processing": true,
            "columns": [
                { "data": "supplier_name" },
                { "data": "date" },
                { "data": "value" },
                { "data": "currentSupplierTotal" },
                { "data": "note" }
            ],
            "ajax": "getQueriedSupplierTrans/"+ supplier + ',' + fromDate + ',' + toDate,

            dom: 'Bfrtip',
            buttons: [
                 {
                    extend: 'excel',
                    title: 'Deals-Auto',
                    footer: true,
                }
            ]           

        });
    });
});
