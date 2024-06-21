<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags --> 
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Pilot VIP Recency</title>
    <style>
        @media print {
            @page {
                /*size: landscape */
            }
        }
        .table {
            color: black;
        }
        .table tr td {
            padding: 1px;
        }
    </style>
</head>

<body onload="window.print();">
<!--<body>-->

    @php 
        $title = $aircraft_type.' Pilot VIP Recency as on ' . date('d F Y', strtotime($date));
        
        // Define start and end dates
        $start_date = date('Y-m-d',strtotime($date.'-30 days'));
        $end_date = date('Y-m-d',strtotime($date));
        
        // Convert start and end dates to timestamps
        $start_timestamp = strtotime($start_date);
        $end_timestamp = strtotime($end_date);
        $date_range = array();

        // Generate dates using foreach loop
        for ($timestamp = $start_timestamp; $timestamp <= $end_timestamp; $timestamp += 86400) {
            $date_range[] = date('Y-m-d', $timestamp);
        }
        $date_range=array_reverse($date_range);
        
        $logs=App\Models\FlyingLog::select('flying_logs.*')->whereBetween('flying_logs.date', [$start_date, $end_date])->join('air_crafts', 'air_crafts.id', '=', 'flying_logs.aircraft_id')->groupBy('flying_logs.date')->where('air_crafts.aircraft_cateogry',$aircraft_type)->orderBy('flying_logs.date', 'desc')->get();
    @endphp

    <div class="row py-5 text-center">
        <div class="col-md-12">
            <h4><u>{{ $title }}</u></h4>
        </div>
    </div>
    @foreach($ac_types as $ac_type)
     @php   $pilots=getACTypePilots($ac_type->id); @endphp 
     @if(!$pilots->isEmpty())
    <table class="table= table-bordered= text-center w-100" border="1px">
        <thead>
            <tr>
                <th colspan="{{ count($logs)+1}}">Required Hrs for VIP Recency/Currency for {{$ac_type->name}} Pilots</th>
            </tr>
            <tr style="font-size: 12px;">
                <th class="text-center align-middle">Date</th>
                @foreach($pilots as $k => $pilot)
                <th>{{$pilot->fullName()}}</th>
                @php 
                    $show_time[$k] = true;
                    $time[$k] = 0; // Ensure this is initialized as a number.
                @endphp
                @endforeach
            </tr>
        </thead> 
        <tbody>
            @foreach($logs as $log)
                <tr>
                    <td>{{ date('d-m-Y', strtotime($log->date)) }}</td>
                    @php
                        $totalMinutes = 0;
                    @endphp
                    @foreach($pilots as $k => $pilot)
                        @php 
                            $blockTime = getBlockTimePilotDateWise($pilot->id, $log->date);
                            $blockTimeMinutes = minutes($blockTime);
                            $totalMinutes += $blockTimeMinutes;
                        @endphp
                        <td>
                            {{ $blockTime != '00:00' && $totalMinutes <= 300 ? $blockTime : '-' }}
                        </td>
                    @endforeach
                </tr>

            @endforeach
        </tbody>
    </table>

    
    <table class="table= table-bordered= text-center w-100 mt-3  mb-5" border="1px">
        <thead>
           
            <tr style="font-size: 12px;">
                <th class="text-left">Pilots Name</th>
               
                <th class="text-left"colspan="{{count($logs)}}">Due Date</th>
                
            </tr>
        </thead>
        <tbody>
            @foreach ($pilots as $key => $pilot)
                <tr  style="font-size: 12px;">
                    <td class="text-left">{{ $pilot->fullName() }}</td>
                    @foreach ($logs as $log)
                        @php 
                            $rtime=getRequiredBlockTimePilotDateWise($pilot->id,date('Y-m-d',strtotime($log->date)),date('Y-m-d',strtotime($log->date.'+30 days')));
                            $mints=300-minutes($rtime);
                            $time= $mints>0?date('d-m-Y',strtotime($log->date)).'<br>'.colculate_days_hours_mints($mints):'';
                        @endphp 
                    <td>{!!$time!!}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
        
    </table>
    
    @endif
    @endforeach
    
    
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
