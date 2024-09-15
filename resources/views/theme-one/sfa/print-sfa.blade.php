<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style>
            body {
                font-family: "Times New Roman";
            }
            table, th, td {
                border:1px solid black;
                border-collapse: collapse;
            }
            tr th {
                height: 42px;
            }
            .th-head{
                height: 30px;
            }
            .td-body{
                height:17px; font-size: 13px; text-align: center;height:17px; font-size: 13px; text-align: center;
            }

        </style>
    </head>
    <body>

        <!--<header>
            <table style="width:100%; top:0;position: relative;">
              <tr>
                <th>
                  <h4 style="margin: 0; line-height: 17px;">  Special Flying Allowance (SFA)</h4>
                  <h4 style="margin: 0; line-height: 17px;"> Employee : <?php //echo $pilot_name;?></h4>
                  <h4 style="margin: 0; line-height: 17px;">  <?php //echo $from_date; ?> - <?php //echo $to_date; ?></h4>

                </th>
              </tr>
            </table><br>
            <table style="width:100%">
                <tr>
                    <td style="border-left: 1px solid;border-right: 1px solid;border-bottom: 1px solid;border-top: 1px solid;">
                      <p style="display: flex; font-size: 18px;padding-left: 33px;color: #000;margin:0; padding: 5px;"><span>Name : <?php //echo $pilot_name;?></span><span style="margin-left: 300px;">Designation : <?php //echo $designation;?></span></p>
                    </td>
                </tr>
            </table><br>
        </header>-->

        <!--<footer>-->
            <!--<table style="width:100%;position: fixed; bottom: 360px;" class="footer-main">
              <tr>
                <td class="footer" >
                    <?php //echo $certified_that; ?>
                </td>
              </tr>
              <tr>
                <td class="footer" style="padding-top: 5px;padding-left: 5px;">
                  <p style="display:flex;justify-content: space-between;margin: 0;">
                    <span style="height:50px">HOURS CHECKED</span>
                    <span style="height:50px;padding-right: 15px;margin-left: 500px;">HOURS VERIFIED </span>
                  </p>
                  <p style="display:flex;justify-content: space-between;font-size: 17px;color: #000;">
                    <span style="font-size: 19px;color: #000;">Claimant's signature </span>
                    <span style="font-size: 19px;color: #000;padding-right: 15px;margin-left: 500px;">Claimant's signature </span>
                  </p>
                  <p style="font-size: 15px;color: #000;margin: 0px;"><span style="color:red; font-size: 17px;">A/C Type</span> - Aircraft Type , <span style="color:red; font-size: 17px;">Regn.</span> - Aircraft Registration , <span style="color:red; font-size: 17px;">Sr. No.</span> - Serial Number , <span style="color:red; font-size: 17px;">G.O. </span>- Government Order</p>
                </td>
              </tr>

            </table>-->
            <!--<table style="width:100%; border:none; margin-top:340px; <?php //echo $is_first_tbl<$total_page ? 'page-break-after: always;' : '' ; ?>">-->
            <!--  <tr style="border:none;">-->
            <!--    <th style="border:none;">-->
            <!--      <span style="float: right;padding-right: 60px;padding: 20px;"> <a href="#" style="text-decoration: none;"><?php //echo "Page ".$is_first_tbl." of ".$total_page; ?></a> </span>-->
            <!--    </th>-->
            <!--  </tr>-->
            <!--</table>-->
        <!--</footer>-->
        <main >


            <!-- first form -->
            <?php
                if(!empty($all_flying))
                {
                    // $total_time = 0;
                    $total_time = array();
                    $total_amount = 0;
                    $is_first_tbl=1;
                    $page_break=1;
                    $total_page = count($all_flying);
                    $page_break_size = 15;
                    foreach($all_flying as $key=>$v)
                    {
                        if($is_first_tbl > 17){
                            $page_break_size = 15;
                        }
                        ?>

                        <?php
                            if($is_first_tbl==1){
                        ?>
                        <table style="width:100%">
                            <tr>
                                <td style="border-left: 1px solid;border-right: 1px solid;border-bottom: 1px solid;border-top: 1px solid;">
                                  <p style="display: flex; font-size: 15px;padding-left: 33px;color: #000;margin:0; padding: 5px;"><span style="font-weight: 900;">Name : <?php echo $pilot_name;?></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="margin-left: 300px;font-weight: 900;">Designation : <?php echo $designation;?></span></p>
                                </td>
                            </tr>
                        </table><br>
                        <?php
                            }
                        ?>
                        <?php
                            if($page_break==1){
                        ?>
                        <?php //echo $is_first_tbl<$total_page ? 'page-break-after: always;' : '' ; ?>
                    <table style="width:100%; "><!--page-break-after: always;-->
                        <?php
                            //$mpdf->AddPage();
                        ?>
                        <?php
                        }

                        if($is_first_tbl==1){
                            $page_break=0;
                        ?>

                        <tr>
                            <th style="height: px;">S.No</th>
                            <th class="th-head">Date</th>
                            <th class="th-head">Aircraft</th>
                            <th class="th-head">A/C Type</th>
                            <th class="th-head">From</th>
                            <th class="th-head">To</th>
                            <th class="th-head">Block Time</th>
                            <th class="th-head">Flying in<br>Capacity<br>of A/C</th>
                            <th class="th-head">Rate/Hours<br>as in<br>G.O. <span style="font-family: DejaVu Sans; sans-serif;">(₹)</span></th>
                            <th class="th-head">Amount <span style="font-family: DejaVu Sans; sans-serif;">(₹)</span></th>
                            <!--<th class="th-head">Remarks</th>-->
                        </tr>
                    <?php
                        }
                        if($page_break==1)
                        {
                    ?>
                      <tr>
                        <td style="height:17px; font-size: 13px; text-align: center;"></td>
                        <td class="td-body"></td>
                        <td class="td-body"></td>
                        <td class="td-body"></td>
                        <td class="td-body"></td>
                        <td class="td-body"></td>

                        <td class="td-body" style="font-weight: bold;"><?=AddPlayTime($total_time)?></td>
                        <td class="td-body"></td>
                        <td class="td-body"></td>
                        <td class="td-body" style="font-weight: bold;"> <?=number_format($total_amount,2)?></td>
                        <!--<td class="td-body"></td>-->
                      </tr>
                    <?php
                        }
                        $total_amount += round($v[9],2);

                        $total_time[] =$v[6];// explode(":", $v[6]);

                    ?>
                      <tr>
                        <td class="td-body"><?=$v[0]?></td>
                        <td class="td-body"><?=$v[1]?></td>
                        <td class="td-body"><?=$v[2]?></td>
                        <td class="td-body"><?=$v[3]?></td>
                        <td class="td-body"><?=$v[4]?></td>
                        <td class="td-body"><?=$v[5]?></td>
                        <td class="td-body"><?=$v[6]?></td>
                        <td class="td-body"><?=$v[7]?></td>
                        <td class="td-body"><?=$v[8]?></td>
                        <td class="td-body"><?= number_format($v[9], 2)?></td>
                        <!--<td class="td-body"><?=$v[10]?></td>-->
                      </tr>
                    <?php

                        if($is_first_tbl % $page_break_size==0 || $is_first_tbl == $total_page){
                            $page_break=1;
                    ?>
                      <tr>
                        <td style="height:17px; font-size: 13px; text-align: center;"></td>
                        <td class="td-body"></td>
                        <td class="td-body"></td>
                        <td class="td-body"></td>
                        <td class="td-body"></td>
                        <td class="td-body" style="font-weight: bold;">Total</td>

                        <td class="td-body" style="font-weight: bold;"><?=AddPlayTime($total_time)?></td>
                        <td class="td-body"></td>
                        <td class="td-body"></td>
                        <td class="td-body" style="font-weight: bold;"> <?=number_format(round($total_amount),2)?></td>
                        <!--<td class="td-body"></td>-->
                      </tr>
                       <!-- <p style="<?php //echo $is_first_tbl<$total_page ? 'page-break-after: always;' : '' ; ?>"></p>-->
                    </table>
                    <?php echo $is_first_tbl<$total_page ? '<pagebreak />' : '' ; ?>
                    <?php
                        }else{
                            $page_break=0;
                        }
                    ?>
                    <!-- end -->
                    <?php

                        $is_first_tbl++;
                    }
            }
        ?>
        </main>
  </body>
</html>
