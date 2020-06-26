$(document).ready(function(){
    //applyQuery    
    $('#applyQuery').click(function(){

        
        var fromDate = $('#fromDate').val();
        var toDate = $('#toDate').val();
            fromDate = 'empty';
        if(!fromDate)
        {
            fromDate ='empty';
        }
        if(!toDate)
        {
            toDate ='empty';
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
                { "data": "date" },
                { "data": "note" },
                { "data": "currentTotal" }
            ],
            "ajax": "getCashQueriedTrans/" + fromDate + ',' + toDate,
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