<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Pilot Flying Currency</title>
    <style>
        @media print {
            @page {
                /*size: landscape*/
            }
        }
        .table {
            color: black;
        }
    </style>
</head>

<body onload="window.print();">
    <table class="table table-bordered text-center w-100 ">
        @php
            $title1 = 'Monthly Report - Pilot Flying Currency';
            $title2 = $aircraft . ' Pilot';
            $title3 = $report_type == '1' ? 'IR, PPC, Route Check & Medical Details' : 'FRTOL, RTR & English Proficiency Details';
        @endphp
        <tr rowspan="4">
            <th colspan="2">{{ $title1 }}<br>{{ $title2 }}<br>{{ $title3 }}<br>{{ date('F-Y', strtotime($date)) }}<br></th>
        </tr>
    </table>
    <!--</header>-->
    <table class="table= table-bordered= text-center w-100" border="1px">
        <thead>
            @php $aircraftType = $aircraft == 'Fixed Wing' ? 'Aircraft' : 'Helicopter'; @endphp
            @if ($report_type == '1')
                <tr>
                    <th>S.No</th>
                    <th>Name</th>
                    <th>Licence No.</th>
                    <th>{{ $aircraftType }}</th>
                    <th>IR</th>
                    <th>PPC</th>
                    <th>RC</th>
                    <th>Medical</th>
                    <th>Remarks</th>
                </tr>
            @else
                <tr>
                    <th>S.No</th>
                    <th>Name</th>
                    <th>Licence No.</th>
                    <th>{{ $aircraftType }}</th>
                    <th>FRTOL</th>
                    <th>RTR</th>
                    <th>English Language Test</th>
                    <th>Remarks</th>
                </tr>
            @endif
        </thead>
        <tbody>
            @foreach ($pilots as $key => $value)
                <!--Rotor Wing-->
                @if($aircraftType=='Helicopter')
                    
                    @if ($report_type == '1')
                        <!--IR, PPC, Route Check & Medical Details-->
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td class="text-left">{{ $value->fullName() }}</td>
                            <td>
                                @php 
                                $licence = \App\Models\PilotLicense::where(function($q) use ($value) {
                                    $q->where('license_id', 635)
                                      ->orWhere('license_id', 643)
                                      ->orWhere('license_id', 636);
                                })
                                ->where('is_applicable', 'yes')
                                ->where('user_id', $value->id)
                                ->orderBy('id', 'DESC')
                                ->first();
                                if(!empty($licence))
                                {
                                        echo getMasterName($licence->license_id).' '.$licence->number;
                                }
                                @endphp
                            </td>
                            <td>
                                @php 
                                $error=[];
                                $ac = \App\Models\AirCraft::groupBy('aircraft_type')->whereJsonContains('pilots',"$value->id")->pluck('aircraft_type')->toArray();
                                foreach($ac as $k=> $a)
                                { 
                                    if($k>0)
                                    {
                                     echo '<span style="display: block;border-top: 1px solid green;">';
                                    }
                                    echo getMasterName($a);
                                    
                                    if($k>0)
                                    {
                                       echo '</span>';
                                    }
                                }
                                @endphp
                            </td>
                            <td> 
                                <!--IR-->
                                @php  
                                $ac = \App\Models\AirCraft::groupBy('aircraft_type')->whereJsonContains('pilots',"$value->id")->pluck('aircraft_type')->toArray();
                                foreach($ac as $k=> $a)
                                {   
                                    if($k>0)
                                    {
                                     echo '<span style="display: block;border-top: 1px solid green;">';
                                    }
                                    
                                    if($aircraftType=='Helicopter')
                                    {
                                        $t1=checkTrainingValidityByType($value->id, $a,'1390'); // Bell 412
                                        $t2= checkTrainingValidityByType($value->id, $a,'15'); //A-109S
                                        $error[$a]['ir']=$t1!='N/A'?$t1:$t2;
                                        echo $t1!='N/A'?$t1:$t2;
                                    }else{
                                        $error[$a]['ir']=checkTrainingValidity($value->id, '1398');
                                        echo checkTrainingValidity($value->id, '1398');
                                    }
                                    
                                    if($k>0)
                                    {
                                       echo '</span>';
                                    }
                                }
                                @endphp
                            </td>
                            <td>
                                <!--PPC-->
                                @php 
                                $ac = \App\Models\AirCraft::groupBy('aircraft_type')->whereJsonContains('pilots',"$value->id")->pluck('aircraft_type')->toArray();
                                foreach($ac as $k=> $a)
                                {   
                                    if($k>0)
                                    {
                                     echo '<span style="display: block;border-top: 1px solid green;">';
                                    }
                                    
                                    if($aircraftType=='Helicopter')
                                    {
                                        $t1=checkTrainingValidityByType($value->id, $a,'647');// Bell 412
                                        $t2= checkTrainingValidityByType($value->id, $a,'13');//A-109S
                                        $error[$a]['ppc']=$t1!='N/A'?$t1:$t2;
                                        echo $t1!='N/A'?$t1:$t2;
                                    }else{
                                        $error[$a]['ppc']=checkTrainingValidity($value->id, '1385');
                                        echo checkTrainingValidity($value->id, '1385');
                                    }
                                    
                                    if($k>0)
                                    {
                                       echo '</span>';
                                    }
                                }
                                @endphp
                            </td>
                            <td>
                                <!--RC-->
                                @php 
                                $ac = \App\Models\AirCraft::groupBy('aircraft_type')->whereJsonContains('pilots',"$value->id")->pluck('aircraft_type')->toArray();
                                foreach($ac as $k=> $a)
                                {   
                                    if($k>0)
                                    {
                                     echo '<span style="display: block;border-top: 1px solid green;">';
                                    }
                                    
                                    if($aircraftType=='Helicopter')
                                    {
                                        $t1=checkTrainingValidityByType($value->id, $a,'648');// Bell 412
                                        $t2= checkTrainingValidityByType($value->id, $a,'1391');//A-109S
                                        $error[$a]['rc']=$t1!='N/A'?$t1:$t2;
                                        echo $t1!='N/A'?$t1:$t2;
                                    }else{
                                        $error[$a]['rc']=checkTrainingValidity($value->id, '1397');
                                        echo checkTrainingValidity($value->id, '1397');
                                    }
                                    if($k>0)
                                    {
                                       echo '</span>';
                                    }
                                }
                                @endphp
                            </td>
                            <td>
                                <!--Medical-->
                                {{ checkMedicalValidity($value->id, '24') }}
                            </td>
                            <td>
                                <!--Remark-->
                                @php 
                                $ac = \App\Models\AirCraft::groupBy('aircraft_type')->whereJsonContains('pilots',"$value->id")->pluck('aircraft_type')->toArray();
                                foreach($ac as $k=> $a)
                                {   $t='VALID';
                                    if($k>0)
                                    {
                                     echo '<span style="display: block;border-top: 1px solid green;">';
                                    }
                                    foreach($error[$a] as $val)
                                    {   
                                        if($val=='N/A')
                                        {
                                            $t='LAPSED';
                                        }
                                        if($val!='N/A'&&strtotime(date('d-m-Y'))>strtotime($val))
                                        {
                                            $t='LAPSED';
                                        }
                                    }
                                    if(checkMedicalValidity($value->id, '24')=='N/A')
                                    {
                                        $t='LAPSED';
                                    }
                                    if(checkMedicalValidity($value->id, '24')!='N/A'&&strtotime(date('d-m-Y'))>strtotime(checkMedicalValidity($value->id, '24')))
                                    {
                                        $t='LAPSED';
                                    }
                                    echo $t;
                                    if($k>0)
                                    {
                                       echo '</span>';
                                    }
                                }
                                @endphp
                            </td>    
                        </tr>
                    @else
                        <!--FRTOL, RTR & English Proficiency Details-->
                        <tr rowspan="2">
                            <td>{{ ++$key }}</td>
                            <td class="text-left">{{ $value->fullName() }}</td>
                            <td>
                                @php 
                                $status='VALID';
                                 $licence = \App\Models\PilotLicense::where(function($q) use ($value) {
                                    $q->where('license_id', 635)
                                      ->orWhere('license_id', 643)
                                      ->orWhere('license_id', 636);
                                })
                                ->where('is_applicable', 'yes')
                                ->where('user_id', $value->id)
                                ->orderBy('id', 'DESC')
                                ->first();
                                if(!empty($licence))
                                {
                                    echo getMasterName($licence->license_id).' '.$licence->number;
                                }
                                @endphp
                            </td>
                            <td>
                                @php 
                                $ac = \App\Models\AirCraft::groupBy('aircraft_type')->whereJsonContains('pilots',"$value->id")->pluck('aircraft_type')->toArray();
                                foreach($ac as $a)
                                { 
                                    echo getMasterName($a);
                                }
                                @endphp
                            </td>
                            <td>
                                <!--FRTOL-->
                                @php 
                                    $FRTOL=checkLicense($value->id, '634');
                                    if(!empty($FRTOL))
                                    {
                                        echo $FRTOL->number;
                                        echo '<br>';
                                        $date= $FRTOL->next_due;
                                        if(strtotime($date) < strtotime(date('Y-m-d')))
                                        {
                                             $status='LAPSED';
                                        }
                                        echo $date;
                                    }else{
                                         echo 'N/A';
                                         $status='LAPSED';
                                    }
                                @endphp
                            </td>
                            <td>
                                <!--RTR-->
                                @php 
                                    $RTR=checkLicense($value->id, '25');
                                    if(!empty($RTR))
                                    {
                                        echo $RTR->number;
                                        echo '<br>';
                                        $date= $RTR->next_due;
                                        if(strtotime($date) < strtotime(date('Y-m-d')))
                                        {
                                             $status='LAPSED';
                                        }
                                        echo $date;
                                        
                                    }else{
                                        echo 'N/A';
                                        $status='LAPSED';
                                    }
                                @endphp
                            </td>
                            <td>
                                <!--Language -->
                                @php 
                                    $Language=checkLicense($value->id, '27');
                                    if(!empty($Language))
                                    {
                                        echo 'Lifetime';
                                    }else{
                                        $Language=checkLicense($value->id, '45');
                                        if(!empty($Language))
                                        {
                                            $date= $Language->renewed_on;
                                            if(strtotime($date) < strtotime(date('Y-m-d')))
                                            {
                                                 $status='LAPSED';
                                            }
                                            echo $date;
                                        }else{
                                            echo 'N/A';
                                            $status='LAPSED';
                                        }
                                    }
                                @endphp 
                            </td>
                            <td>
                                {{$status}}
                            </td>    
                        </tr>
                    @endif
                @else
                <!--Fixed Wing-->
                    @if ($report_type == '1')
                        <!--IR, PPC, Route Check & Medical Details-->
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td class="text-left">{{ $value->fullName() }}</td>
                            <td>
                                @php 
                                $licence = \App\Models\PilotLicense::where(function($q) use ($value) {
                                    $q->where('license_id', 635)
                                      ->orWhere('license_id', 643)
                                      ->orWhere('license_id', 636);
                                })
                                ->where('is_applicable', 'yes')
                                ->where('user_id', $value->id)
                                ->orderBy('id', 'DESC')
                                ->first();
                                if(!empty($licence))
                                {
                                        echo getMasterName($licence->license_id).' '.$licence->number;
                                }
                                @endphp
                            </td>
                            <td>
                                @php 
                                $error=[];
                                $ac = \App\Models\AirCraft::groupBy('aircraft_type')->whereJsonContains('pilots',"$value->id")->pluck('aircraft_type')->toArray();
                                foreach($ac as $k=> $a)
                                { 
                                    if($k>0)
                                    {
                                     echo '<span style="display: block;border-top: 1px solid green;">';
                                    }
                                    echo getMasterName($a);
                                    
                                    if($k>0)
                                    {
                                       echo '</span>';
                                    }
                                }
                                @endphp
                            </td>
                            <td> 
                                <!--IR-->
                                @php  
                                $ac = \App\Models\AirCraft::groupBy('aircraft_type')->whereJsonContains('pilots',"$value->id")->pluck('aircraft_type')->toArray();
                                foreach($ac as $k=> $a)
                                {   
                                    if($k>0)
                                    {
                                     echo '<span style="display: block;border-top: 1px solid green;">';
                                    }
                                    
                                    $t1=checkTrainingValidityByType($value->id, $a,'637'); // HS 125
                                    $t2= checkTrainingValidityByType($value->id, $a,'1387'); // B200/B200GT
                                    $error[$a]['ir']=$t1!='N/A'?$t1:$t2;
                                    echo $t1!='N/A'?$t1:$t2;
                                    
                                    if($k>0)
                                    {
                                       echo '</span>';
                                    }
                                }
                                @endphp
                            </td>
                            <td>
                                <!--PPC-->
                                @php 
                                $ac = \App\Models\AirCraft::groupBy('aircraft_type')->whereJsonContains('pilots',"$value->id")->pluck('aircraft_type')->toArray();
                                foreach($ac as $k=> $a)
                                {   
                                    if($k>0)
                                    {
                                     echo '<span style="display: block;border-top: 1px solid green;">';
                                    }
                                    
                                    $t1=checkTrainingValidityByType($value->id, $a,'1381');// HS125
                                    $t2= checkTrainingValidityByType($value->id, $a,'1382');//B200/B200GT
                                    $error[$a]['ppc']=$t1!='N/A'?$t1:$t2;
                                    echo $t1!='N/A'?$t1:$t2;
                                    
                                    if($k>0)
                                    {
                                       echo '</span>';
                                    }
                                }
                                @endphp
                            </td>
                            <td>
                                <!--RC-->
                                @php 
                                $ac = \App\Models\AirCraft::groupBy('aircraft_type')->whereJsonContains('pilots',"$value->id")->pluck('aircraft_type')->toArray();
                                foreach($ac as $k=> $a)
                                {   
                                    if($k>0)
                                    {
                                     echo '<span style="display: block;border-top: 1px solid green;">';
                                    }
                                    
                                    $t1=checkTrainingValidityByType($value->id, $a,'46');// HS125
                                    $t2= checkTrainingValidityByType($value->id, $a,'1393');//B200/B200GT
                                    $error[$a]['rc']=$t1!='N/A'?$t1:$t2;
                                    echo $t1!='N/A'?$t1:$t2;
                                    
                                    if($k>0)
                                    {
                                       echo '</span>';
                                    }
                                }
                                @endphp
                            </td>
                            <td>
                                <!--Medical-->
                                {{ checkMedicalValidity($value->id, '24') }}
                            </td>
                            <td>
                                <!--Remark-->
                                @php 
                                $ac = \App\Models\AirCraft::groupBy('aircraft_type')->whereJsonContains('pilots',"$value->id")->pluck('aircraft_type')->toArray();
                                foreach($ac as $k=> $a)
                                {   $t='VALID';
                                    if($k>0)
                                    {
                                     echo '<span style="display: block;border-top: 1px solid green;">';
                                    }
                                    foreach($error[$a] as $val)
                                    {   
                                        if($val=='N/A')
                                        {
                                            $t='LAPSED';
                                        }
                                        if($val!='N/A'&&strtotime(date('d-m-Y'))>strtotime($val))
                                        {
                                            $t='LAPSED';
                                        }
                                    }
                                    if(checkMedicalValidity($value->id, '24')=='N/A')
                                    {
                                        $t='LAPSED';
                                    }
                                    if(checkMedicalValidity($value->id, '24')!='N/A'&&strtotime(date('d-m-Y'))>strtotime(checkMedicalValidity($value->id, '24')))
                                    {
                                        $t='LAPSED';
                                    }
                                    echo $t;
                                    if($k>0)
                                    {
                                       echo '</span>';
                                    }
                                }
                                @endphp
                            </td>    
                        </tr>
                    @else
                        <!--FRTOL, RTR & English Proficiency Details-->
                        <tr rowspan="2">
                            <td>{{ ++$key }}</td>
                            <td class="text-left">{{ $value->fullName() }}</td>
                            <td>
                                @php 
                                $status='VALID';
                                 $licence = \App\Models\PilotLicense::where(function($q) use ($value) {
                                    $q->where('license_id', 635)
                                      ->orWhere('license_id', 643)
                                      ->orWhere('license_id', 636);
                                })
                                ->where('is_applicable', 'yes')
                                ->where('user_id', $value->id)
                                ->orderBy('id', 'DESC')
                                ->first();
                                if(!empty($licence))
                                {
                                        echo getMasterName($licence->license_id).' '.$licence->number;
                                }
                                @endphp
                            </td>
                            <td>
                                @php 
                                $ac = \App\Models\AirCraft::groupBy('aircraft_type')->whereJsonContains('pilots',"$value->id")->pluck('aircraft_type')->toArray();
                                foreach($ac as $a)
                                { 
                                    echo getMasterName($a);
                                }
                                @endphp
                            </td>
                            <td>
                                <!--FRTOL-->
                                @php 
                                    $FRTOL=checkLicense($value->id, '634');
                                    if(!empty($FRTOL))
                                    {
                                        echo $FRTOL->number;
                                        echo '<br>';
                                        $date= $FRTOL->next_due;
                                        if(strtotime($date) < strtotime(date('Y-m-d')))
                                        {
                                             $status='LAPSED';
                                        }
                                        echo $date;
                                    }else{
                                         echo 'N/A';
                                         $status='LAPSED';
                                    }
                                @endphp
                            </td>
                            <td>
                                <!--RTR-->
                                @php 
                                    $RTR=checkLicense($value->id, '25');
                                    if(!empty($RTR))
                                    {
                                        echo $RTR->number;
                                        echo '<br>';
                                        $date= $RTR->next_due;
                                        if(strtotime($date) < strtotime(date('Y-m-d')))
                                        {
                                             $status='LAPSED';
                                        }
                                        echo $date;
                                        
                                    }else{
                                        echo 'N/A';
                                        $status='LAPSED';
                                    }
                                @endphp
                            </td>
                            <td>
                                <!--Language -->
                                @php 
                                    $Language=checkLicense($value->id, '27');
                                    if(!empty($Language))
                                    {
                                        echo 'Lifetime';
                                    }else{
                                        $Language=checkLicense($value->id, '45');
                                        if(!empty($Language))
                                        {
                                            $date= $Language->renewed_on;
                                            if(strtotime($date) < strtotime(date('Y-m-d')))
                                            {
                                                 $status='LAPSED';
                                            }
                                            echo $date;
                                        }else{
                                            echo 'N/A';
                                            $status='LAPSED';
                                        }
                                    }
                                @endphp 
                            </td>
                            <td>
                                {{$status}}
                            </td>    
                        </tr>
                    @endif
                @endif
            @endforeach
        </tbody>

    </table>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
</body>

</html>
