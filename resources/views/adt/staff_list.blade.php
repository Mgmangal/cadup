<!DOCTYPE html>
<html>
   <head>
      <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
      <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
   </head>
   <body onload="window.print()">
      <div class="container">
         <div class="panel-body" id="mydiv">
            <table class="table table-bordered">
               <thead class="thead-dark">
                  <tr>
                     <th scope="col">
                        <p class="headind-pro">Sr. No.</p>
                     </th>
                     <th scope="col">
                        <p class="headind-pro">Date</p>
                     </th>
                     <th scope="col">
                        <p class="headind-pro">Emp No</p>
                     </th>
                     <th scope="col">
                        <p class="headind-pro">Employee Name</p>
                     </th>
                     <th scope="col">
                        <p class="headind-pro">Email</p>
                     </th>
                     <th scope="col">
                        <p class="headind-pro">Mobile</p>
                     </th>
                     <th scope="col">
                        <p class="headind-pro">Designation</p>
                     </th>
                  </tr>
               </thead>
               <tbody>
                  <?php
                     $ids = explode(',', $IDs); 
                     $i='1';
                     foreach ($ids as $value) { 
                        $emp_data =\APP\Models\User::find($value);
                     ?>
                  <tr>
                     <td><?php echo $i; ?></td>
                     <td>Date</td>
                     <td><?php echo $emp_data->emp_id; ?></td>
                     <td><?php echo $emp_data->name; ?></td>
                     <td><?php echo $emp_data->email; ?></td>
                     <td><?php echo $emp_data->phone; ?></td>
                     <td><?php echo !empty($emp_data->designation) ? \App\Models\Master::find($emp_data->designation)->name : ''; ?></td>
                  </tr>
                  <?php $i++; } ?>
               </tbody>
            </table>
         </div>
         <div class="row">
            <div class="col-12 text-center">
               <button type="button" class="btn btn-sm btn-danger" id="btn"><i class="fa fa-print" aria-hidden="true"></i> Print</button>
            </div>
         </div>
      </div>
   </body>
</html>
<script src="//code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
<script type="text/javascript">
//   <?php if (!empty($flag)) { ?>
//      printDiv(); 
//   <?php } ?>

//   function printDiv() {
//     var divToPrint = document.getElementById('mydiv');
//     var htmlToPrint = '' +
//       '<style>'+
//          'table, td, th {'+
//          'border: 1px solid;'+
//          'text-align: center;'+
//          '}'+
//          'table {'+
//          'width: 100%;'+
//          'border-collapse: collapse;'+
//          '}'+
//          '.container{'+
//          'width: 1100px;'+
//          'margin:0 auto;'+
//          '}'+
//          '.director-of-civil {'+
//          'text-align: center;'+
//          'padding: 15px 20px 25px 0;'+
//          '}'+
//          '.director-of-civil h1 {'+
//          'margin: 0;'+
//          'font-size: 40px;'+
//          '}'+
//          '.director-of-civil h3 {'+
//          'margin: 0;'+
//          'font-size: 30px;'+
//          'line-height: 28px;'+
//          '}'+
//          '.ahkhjjjj {'+
//          'display: flex;'+
//          'justify-content: space-between;'+
//          'padding: 20px 30px;'+
//          '}'+
//          '.hgdskk p {'+
//          'margin: 0;'+
//          'font-size: 18px;'+
//          '}'+
//          'input#fname {'+
//          'border-left: 0;'+
//          'border-top: 0;'+
//          'border-right: 0;'+
//          'border-bottom: 1px solid;'+
//          '}'+
//          'input#fname:focus {'+
//          'outline-width: 0;'+
//          '}'+
//          '.director-of-civil h5 {'+
//          'margin: 0;'+
//          'font-size: 23px;'+
//          'font-family: sans-serif;'+
//          '}'+
//          'p.hdgdg span {'+
//          'display: block;'+
//          'margin-bottom: 30px;'+
//          '}'+
//          'p.headind-pro {'+
//          'margin: 0;'+
//          'padding: 10px;'+
//          '}'+
//         '</style>';
//     htmlToPrint += divToPrint.outerHTML;
//     newWin = window.open("");
//     newWin.document.write(htmlToPrint);
//     newWin.print();
//     newWin.close();
// }


//   function printData()
//   {
//       var divToPrint=document.getElementById("mydiv");
//       newWin= window.open("");
//       newWin.document.write(divToPrint.outerHTML);
//       newWin.print();
//       newWin.close();
//   }
//   printDiv(); 
//   $('#btn').on('click',function(){
//       printDiv();
//   })
</script>