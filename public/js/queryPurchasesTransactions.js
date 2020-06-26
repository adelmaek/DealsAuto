$(document).ready(function(){
    //applyQuery    
    $('#applyQuery').click(function(){

        var type = $('#typeInput').val();
        if(type)
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

       
        if ( $.fn.dataTable.isDataTable( '#purchasesTransTable' ) ) {
            table = $('#purchasesTransTable').DataTable();
            table.destroy();
        }
       var table =  $('#purchasesTransTable').DataTable({
            "displayLength": 25,
            "processing": true,
            "columns": [
                { "data": "type" },
                { "data": "date" },
                { "data": "value" },
                { "data": "currentTotal" },
                { "data": "bill_number" },
                { "data": "note" }
            ],
            "ajax": "getQueriedPurchaseTrans/"+ type + ',' + fromDate + ',' + toDate,

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
