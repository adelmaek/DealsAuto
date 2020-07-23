const $tableID = $('#table');
 const $BTN = $('#export-btn');
 const $EXPORT = $('#export');

 const newTr = `
<tr >
  <td class="pt-3-half" contenteditable="false"><input type="text"  class="form-control"   style="border-width:0px;border:none;box-shadow: none; text-align: center; " id="nameInput[]" name="nameInput[]" placeholder="Currency" required></td>
  <td class="pt-3-half" contenteditable="false"><input type="number" step="0.01" class="form-control"  style="border-width:0px;border:none;box-shadow: none; text-align: center; " id="rateInput[]" name="rateInput[]" placeholder="Rate"   required></td>
  <td class="pt-3-half">
    <span class="table-up"><a href="#!" class="indigo-text"><i class="fas fa-long-arrow-alt-up" aria-hidden="true"></i></a></span>
    <span class="table-down"><a href="#!" class="indigo-text"><i class="fas fa-long-arrow-alt-down" aria-hidden="true"></i></a></span>
  </td>
</tr>`;

 $('.table-add').on('click', 'i', () => {


   if ($tableID.find('tbody tr').length === 0) {

     $('tbody').append(newTr);
   }
   else
    $tableID.find('table').append(newTr);
 });

 $tableID.on('click', '.table-remove', function () {

   $(this).parents('tr').detach();
 });

 $tableID.on('click', '.table-up', function () {

   const $row = $(this).parents('tr');

   if ($row.index() === 0) {
     return;
   }

   $row.prev().before($row.get(0));
 });

 $tableID.on('click', '.table-down', function () {

   const $row = $(this).parents('tr');
   $row.next().after($row.get(0));
 });

 // A few jQuery helpers for exporting only
 jQuery.fn.pop = [].pop;
 jQuery.fn.shift = [].shift;

 $BTN.on('click', () => {

   const $rows = $tableID.find('tr:not(:hidden)');
   const headers = [];
   const data = [];

   // Get the headers (add special header logic here)
   $($rows.shift()).find('th:not(:empty)').each(function () {

     headers.push($(this).text().toLowerCase());
   });

   // Turn all existing rows into a loopable array
   $rows.each(function () {
     const $td = $(this).find('td');
     const h = {};

     // Use the headers from earlier to name our hash keys
     headers.forEach((header, i) => {

       h[header] = $td.eq(i).text();
     });

     data.push(h);
   });

   // Output the result
   $EXPORT.text(JSON.stringify(data));
 });