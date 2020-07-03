$(document).ready(function(){
    //applyQuery    
    $('#applyQuery').click(function(){

        var bank = $('#accountNumberInput').val();
        if(bank)
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


        if ( $.fn.dataTable.isDataTable( '#transTable' ) ) {
            table = $('#transTable').DataTable();
            table.destroy();
        }
       var table =  $('#transTable').DataTable({
            "displayLength": 25,
            "processing": true,
            "columns": [
                { "data": "accountNumber" },
                { "data": "date" },
                { "data": "type" },
                { "data": "value" },
                { "data": "currentBankBalance" },
                { "data": "currentAllBanksBalance" },
                { "data": "note" }
            ],
            "ajax": "getQueriedTrans/"+ bank + ',' + fromDate + ',' + toDate,

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
