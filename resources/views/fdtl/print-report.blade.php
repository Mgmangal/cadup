<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Pilot's FDTL Record</title>
    <style>
        @media print {
            @page {
                /*size: landscape;*/
            }
        
           .table{
                color: black;
                position: relative;
                /*bottom: 10px!important;*/
            }
            .footer {
                position: relative;
                bottom: 0px!important;
                width: 100%;
                text-align: center;
            }
            .footer .col-md-12{
                width: 80%;
            }
        }


    </style>
    <style>
        
        /*headerTable {*/
        /*    position: absolute;*/
        /*    top: 0;*/
        /*}*/
        
        /*@media print {*/
        /*    .headerTable {*/
        /*        position: fixed;*/
        /*    }*/
        /*}*/
    </style>
</head>

<body onload="window.print();">
<!--<body>-->
    <!--<header>-->
    <table class="table table-bordered text-center w-100 ">
        <tr>
            <th colspan="2">
               <h4> Pilot's FDTL Record</h4>
            </th>
        </tr>
        <tr>
            <th style="width:50%;">
               <h5> Crew : {{$user->salutation}} {{$user->name}} </h5>
            </th>
            <th style="width:50%;">
               <h5> License:</h5>
            </th>
        </tr>
        <tr>
            <th colspan="2">
              <h5>  Time : UTC, Period :  {{date('M d, Y',strtotime($from))}} to {{date('M d, Y',strtotime($to))}}</h5>
            </th>
        </tr>
    </table>
    <!--</header>-->
    <table class="table table-bordered text-center w-100">
        <thead>
            <!--<tr>-->
            <!--    <th colspan="22">-->
            <!--       <h4> Pilot's FDTL Record</h4>-->
            <!--    </th>-->
            <!--</tr>-->
            <!--<tr>-->
            <!--    <th colspan="11">-->
            <!--       <h5> Crew : {{$user->salutation}} {{$user->name}} </h5>-->
            <!--    </th>-->
            <!--    <th colspan="11">-->
            <!--       <h5> License:</h5>-->
            <!--    </th>-->
            <!--</tr>-->
            <!--<tr>-->
            <!--    <th colspan="22">-->
            <!--      <h5>  Time : UTC, Period :  {{date('M d, Y',strtotime($from))}} to {{date('M d, Y',strtotime($to))}}</h5>-->
            <!--    </th>-->
            <!--</tr>-->
            <!--<tr>-->
            <!--    <th colspan="22" style="border:none;"></th>-->
            <!--</tr>-->
            <tr>
                <th rowspan="2">Date</th>
                <th rowspan="2">Rest</th>
                <th rowspan="2">TT</th>
                <!-- Duty on(Do)/ Travel Time (TT) 30 min before -->
                <!-- duty on 11.50 - 15 50 -->
                <!-- week start after 36 hr rest -->
                <th rowspan="2">STD</th>
                <th rowspan="2">ATD</th>
                <th rowspan="2">STA</th>
                <th rowspan="2">ATA</th>
                <th rowspan="2">On Duty</th>
                <th rowspan="2">Off Duty</th>
                <th colspan="2">DP</th>
                <th colspan="2">FDP</th>
                <th colspan="5">FT</th>
                <th colspan="2">Landings</th>
                <th rowspan="2">Break</th>
                <th rowspan="2">Ext. FDP</th>
            </tr>
            <tr>
                <th>Sector</th>
                <th>24 Hours</th>
                <th>Sector</th>
                <th>24 Hours</th>
                <th>Sector</th>
                <th>24 Hours</th>
                <th>7 Days</th>
                <th>30 Days</th>
                <th>365 Days</th>
                <th>Sector</th>
                <th>24 Hours</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $key => $value)
            <tr>
                <td>{{date('M d, Y',strtotime($value->date))}}</td>
                <td>{{get_Rest($user_id,$value->date)}}</td>
                <td>{{get_do_tt($user_id,$value->date)}}</td>
                <td colspan="4" class="p-0 m-0">{!!get_STD_ATD_STA_ATA($user_id,$value->date)!!}</td>
                <td>{{get_on_duty($user_id,$value->date)}}</td>
                <td>{{get_off_duty($user_id,$value->date)}}</td>
                <td>{{get_DP_Sector($user_id,$value->date)}}</td>
                <td>{{get_DP_24Hours($user_id,$value->date)}}</td>
                <td>{{get_FDP_Sector($user_id,$value->date)}}</td>
                <td>{{get_FDP_24Hours($user_id,$value->date)}}</td>
                <td>{{get_FT_Sector($user_id,$value->date)}}</td>
                <td>{{get_FT_24Hours($user_id,$value->date)}}</td>
                <td>{{get_FT_IN_Days($user_id,$value->date,7)}}</td>
                <td>{{get_FT_IN_Days($user_id,$value->date,30)}}</td>
                <td>{{get_FT_IN_Days($user_id,$value->date,365)}}</td>
                <td>{{get_Landings_Sector($user_id,$value->date)}}</td>
                <td>{{get_Landings_24Hours($user_id,$value->date)}}</td>
                <td>{{get_Break($user_id,$value->date)}}</td>
                <td></td>
            </tr>
            @endforeach
        </tbody>
        <!--<tfoot>-->
        <!--    <tr>-->
        <!--        <td colspan="22">-->
        <!--             Legends: Rest - Rest Before the Flight, TT -Travel Time, STD - Schedule Time of Departure, ATD - Actual Time of Departure, DP - Duty Period, FDP - Flight Duty Period, FT- Flight Time, Ext. FDP- Extended Flight Duty Period, Ext. FT - Extended Flight Time -->
        <!--        </td>-->
        <!--    </tr>-->
        <!--</tfoot>-->
    </table>

    <div class="footer row text-center d-flex justify-content-center">
        <div class="col-md-12">
            <label class="text-danger">Legends:</label> Rest - Rest Before the Flight, TT -Travel Time, STD - Schedule Time of Departure, ATD - Actual Time of Departure, DP - Duty Period, FDP - Flight Duty Period, FT- Flight Time, Ext. FDP- Extended Flight Duty Period, Ext. FT - Extended Flight Time    
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</body>

</html>