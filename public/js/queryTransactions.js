$(document).ready(function(){
    //applyQuery    
    $('#applyQuery').click(function(){
        var bank = $('#accountNumberInput').val();
        if(bank)
        {
            var fromDate = $('#fromDate').val();
            var toDate = $('#toDate').val();
            getTransactions(bank, fromDate, toDate);
        }
        
    });
});

 function getTransactions(bank, fromDate, toDate)
{
    if(!fromDate)
    {
        fromDate ='empty';
    }
    if(!toDate)
    {
        toDate ='empty';
    }
    $.ajax({
        url: 'getQueriedTrans/' + bank + ',' + fromDate + ',' + toDate,
        type: 'get',
        dataType: 'json',
        success: function(response){
            // alert("success");
            // alert(JSON.stringify(response));
            $('#transTable thead').empty();
            var th_str=     "<tr>"+
                "<th scope='col' class='text-center' >رقم الحساب</th>"+
                "<th scope='col' class='text-center' >التاريخ</th>"+
                "<th scope='col' class='text-center' >نوع المعاملة</th>"+
                "<th scope='col' class='text-center' >القيمة</th>"+
                "<th scope='col' class='text-center' >البيان</th>"+
                "</tr>";
            $("#transTable thead").append(th_str);

                    
            var len = 0;
            $('#transTable tbody').empty();
            if(response != null)
            {
                len = response.length;
                
            }
            if(len > 0)
            {
                for(var i=0; i<len; i++)
                {
                  var accountNumber = response[i].accountNumber;
                  var date = response[i].date;
                  var type = response[i].type;
                  if(type =="add")
                  {
                      type = 'ايداع';
                  }
                  else
                  {
                      type = 'صرف';
                  }
                  var value = response[i].value;
                  var note = response[i].note;
                  var tr_str = "<tr>" +
                   "<td align='center'>" + accountNumber + "</td>" +
                   "<td align='center'>" + date + "</td>" +
                   "<td align='center'>" + type + "</td>" +
                   "<td align='center'>" + value + "</td>" +
                   "<td align='center'>" + note + "</td>" +
                    "</tr>";
                    $("#transTable tbody").append(tr_str);
                }
            }else{
                // alert(len);
                var tr_str = "<tr>" +
                "<td align='center' colspan='5'>لا يوجد معاملات</td>" +
                "</tr>";
                // alert(tr_str);
                 $("#transTable tbody").append(tr_str);
             }
        },
        error: function(xhr, status, error) {
            alert(error);
        },
        fail: function(xhr, textStatus, errorThrown){
            alert('request failed');
        }
    });
}