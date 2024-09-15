<!DOCTYPE html>
<html>
   <head>
      <style>
         table, td, th {
         border: 1px solid;
         text-align: center;
         }
         table {
         width: 95%;
         border-collapse: collapse;
         }
         .container{
         width: 1100px;
         margin:0 auto;
         }
         .director-of-civil {
         text-align: center;
         padding: 15px 20px 25px 0;
         }
         .director-of-civil h1 {
         margin: 0;
         font-size: 40px;
         }
         .director-of-civil h3 {
         margin: 0;
         font-size: 20px;
         line-height: 28px;
         }
         .ahkhjjjj {
         display: flex;
         justify-content: space-between;
         padding: 20px 30px;
         }
         .hgdskk p {
         margin: 0;
         font-size: 18px;
         }
         input#fname {
         border-left: 0;
         border-top: 0;
         border-right: 0;
         border-bottom: 1px solid;
         }
         input#fname:focus {
         outline-width: 0;
         }
         .director-of-civil h5 {
         margin: 0;
         font-size: 23px;
         font-family: sans-serif;
         }
         p.hdgdg span {
         display: block;
         margin-bottom: 30px;
         margin-top: 30px;
         }
         p.headind-pro {
         margin: 0;
         padding: 10px;
         }
         .srno{
            width: 10%;
         }
         .name{
            width: 30%;
         }
         .designation{
            width: 15%;
         }
         .result{
            width: 20%;
         }
      </style>
   </head>
   <body onload="window.print()">
      <div class="container" id="mydiv">
         <table>
            <tr>
               <td colspan="6">
                  <div class="director-of-civil">
                     <h1>DIRECTORATE OF CIVIL AVIATION</h1>
                     <h3>UTTAR PRADESH</h3>
                     <h3>LUCKNOW AIRPORT, LUCKNOW, INDIA</h3>
                     <h3>BA TEST FOR FLYING CREW/MAINTENANCE PERSONNEL ON DATE : <?php echo !empty($date)?$date:'' ?> </h3>
                     <div class="ahkhjjjj">
                        <div class="hgdskk">
                           <p>Alcohol Meter S. No.-<span class="borerhhh"> <input type="text" id="fname" name="fname"></span></p>
                        </div>
                        <div class="hgdskk">
                           <p>Calibration Validity Date-<span class="borerhhh"><input type="text" id="fname" name="fname"></span></p>
                        </div>
                     </div>
                     <h5>CONTROL TEST CARRIED OUT</h5>
                     <div class="ahkhjjjj">
                        <div class="hgdskk">
                           <p>Reading Obtained-<span class="borerhhh"> <input type="text" id="fname" name="fname"></span></p>
                        </div>
                        <div class="hgdskk">
                           <!--<p>Sign & Stamp-<span class="borerhhh"><input type="text" id="fname" name="fname"></span></p>-->
                           <p>Control Test No.-<span class="borerhhh"><input type="text" id="fname" name="fname"></span></p>
                        </div>
                     </div>
                  </div>
               </td>
            </tr> 
            <tr>
               <th class="srno">
                  <p class="headind-pro">Sr. No.</p>
               </th>
               <th class="srno">
                  <p class="headind-pro"> EMP. Code</p>
               </th>
               <th class="name">
                  <p class="headind-pro">Name</p>
               </th>
               <th class="designation">
                  <p class="headind-pro">Designation</p>
               </th>
               <th class="designation">
                  <p class="headind-pro">Test Result</p>
               </th>
               <th class="result">
                  <p class="headind-pro">Remark</p>
               </th>
            </tr>
            <?php 
                $ids = explode(',', $IDs);
                
                $t=30;
                for ($i=1; $i <= $t; $i++) { 
                    $emp_data='';
                    $j=$i;
                    --$j;
                    if(!empty($ids[$j]))
                    {
                        $emp_data =\APP\Models\User::find($ids[$j]);
                    }
                     
            ?>
                    <tr>
                       <td><?php echo $i; ?></td>
                       <td><?php echo (!empty($emp_data))?$emp_data->emp_id:''; ?></td>
                       <td><?php echo (!empty($emp_data))?$emp_data->name:''; ?></td>
                       <td><?php echo !empty($emp_data->designation) ? \App\Models\Master::find($emp_data->designation)->name : '';?></td>
                       <td></td>
                       <td></td>
                    </tr>
            
            <?php } ?>
            <?php  if(0){ ?>
            <?php
               $ids = explode(',', $IDs);
               
               
               print_r($ids);
               $count = count($ids); 
                
               $t=42;
               $Sno='1';
                for ($i='1'; $i <= $t; $i++) { 
                  
                foreach ($ids as $value) { 
                   $emp_data = $this->db->get_where('tbl_staff',['id'=>$value])->row(); 
                ?>
                    <tr>
                       <td><?php echo $Sno; echo '=='.$i;?></td>
                       <td><?php echo ($i>$count)?'':$emp_data->emp_id; ?></td>
                       <td><?php echo ($i>$count)?'':$emp_data->name; ?></td>
                       <td><?php echo ($i>$count)?'':$emp_data->designation; ?></td>
                       <td></td>
                       <td></td>
                    </tr>
                <?php   
                    $i++;
                    $Sno++; 
                    }
                } ?> 
            
            <tr>
               <td colspan="3">
                  <p class="hdgdg">
                     <span><b> Signature Of Medical Person</b></span>
                     <span><input type="text" id="fname" name="fname"></span>
                  </p>
               </td>
               <td colspan="2">
                  <p class="hdgdg">
                     <span><b>Total B.A.T</b></span>
                     <span><input type="text" id="fname" name="fname"></span>
                  </p>
               </td>
               <td colspan="2">
                  <p class="hdgdg">
                     <span><b>Verified By</b></span>
                     <span><input type="text" id="fname" name="fname"></span>
                  </p>
               </td>
            </tr>
            <?php } ?>
         </table>
      </div>
   </body>
</html>
<script src="//code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script type="text/javascript">
//   printDiv();
   
   function printDiv() {
      var divToPrint = document.getElementById('mydiv');
      var htmlToPrint = '' +
      '<style>'+
         'table, td, th {'+
         'border: 1px solid;'+
         'text-align: center;'+
         '}'+
         'table {'+
         'width: 95%;'+
         'border-collapse: collapse;'+
         '}'+
         '.srno{'+
         'width: 10%;'+
         '}'+
         '.name{'+
         'width: 30%;'+
         '}'+
         '.designation{'+
         'width: 15%;'+
         '}'+
         '.result;{'+
         'width: 20%;'+
         '}'+
         '.container{'+
         'width: 1100px;'+
         'margin:0 auto;'+
         '}'+ 
         '.director-of-civil {'+
         'text-align: center;'+
         'padding: 15px 20px 25px 0;'+
         '}'+
         '.director-of-civil h1 {'+
         'margin: 0;'+
         'font-size: 40px;'+
         '}'+
         '.director-of-civil h3 {'+
         'margin: 0;'+
         'font-size: 20px;'+
         'line-height: 28px;'+
         '}'+
         '.ahkhjjjj {'+
         'display: flex;'+
         'justify-content: space-between;'+
         'padding: 20px 30px;'+
         '}'+
         '.hgdskk p {'+
         'margin: 0;'+
         'font-size: 18px;'+
         '}'+
         'input#fname {'+
         'border-left: 0;'+
         'border-top: 0;'+
         'border-right: 0;'+
         'border-bottom: 1px solid;'+
         '}'+
         'input#fname:focus {'+
         'outline-width: 0;'+
         '}'+
         '.director-of-civil h5 {'+
         'margin: 0;'+
         'font-size: 23px;'+
         'font-family: sans-serif;'+
         '}'+
         'p.hdgdg span {'+
         'display: block;'+
         'margin-bottom: 30px;'+
         'margin-top: 30px;'+
         '}'+
         'p.headind-pro {'+
         'margin: 0;'+
         'padding: 10px;'+
         '}'+
        '</style>';
      htmlToPrint += divToPrint.outerHTML;
      newWin = window.open("");
      newWin.document.write(htmlToPrint);
      newWin.print();
      newWin.close();
   }
   
 
 
</script>