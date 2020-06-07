$(document).ready(function(){
    //applyQuery    
    $('#applyQuery').click(function(){

        var currency = $('#currencyInput').val();
        if(currency)
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
        if ( $.fn.dataTable.isDataTable( '#cashTransTable' ) ) {
            table = $('#cashTransTable').DataTable();
            table.destroy();
        }
       var table =  $('#cashTransTable').DataTable({
           
            "displayLength": 25,
            "processing": true,
            "columns": [
                { "data": "type" },
                { "data": "value" },
                { "data": "currency" },
                { "data": "date" },
                { "data": "note" },
                { "data": "currentTotal" }
            ],
            "ajax": "getCashQueriedTrans/"+ currency + ',' + fromDate + ',' + toDate,
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