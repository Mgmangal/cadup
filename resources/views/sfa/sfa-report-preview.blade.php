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
