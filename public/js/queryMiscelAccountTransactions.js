$(document).ready(function(){
    //applyQuery    
    $('#applyQuery').click(function(){

        var account = $('#accountNameInput').val();
        if(account)
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

       
        if ( $.fn.dataTable.isDataTable( '#accountsTransTable' ) ) {
            table = $('#accountsTransTable').DataTable();
            table.destroy();
        }
       var table =  $('#accountsTransTable').DataTable({
            "displayLength": 25,
            "processing": true,
            "columns": [
                { "data": "account_name" },
                { "data": "date" },
                { "data": "value_add" },
                { "data": "value_sub" },
                { "data": "currentAccountotal" },
                { "data": "note" }
            ],
            "ajax": "getQueriedMiscelTrans/"+ account + ',' + fromDate + ',' + toDate,

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
