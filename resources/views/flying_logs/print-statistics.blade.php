<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Flying Statistics</title>
    <style>
        @media print{
            @page {
                /*size: landscape*/
            }
        }
        .table{
            color: black;
        }
        
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
    <table class="table= table-bordered= text-center w-100 mb-5" border="1px">
        <tr>
            <th colspan="2">
               <!--<h4>Flying Statistics Record</h4>-->
               <h4>Aircraft-wise  Flying Summary</h4>
            </th>
        </tr>
        @if($aircrafts->count()==1) 
        <tr>
            <th colspan="2">
               <!--<h4>Flying Statistics Record</h4>-->
               <h4>Aircraft : {{$aircrafts[0]->call_sign}} </h4>
            </th>
        </tr>
        @endif
        <tr>
            <th colspan="2" class="text-left">
              <h5>Date Period : {{date('d/m/Y',strtotime($from))}} to {{date('d/m/Y',strtotime($to))}}</h5>
            </th>
        </tr>
    </table>
    <!--</header>-->
    <table class="table= table-bordered= text-center w-100" border="1px">
        <thead>
           <tr>
                <th>Date</th>
                <th>From/To</th>
                <th>Chocks-Off / On</th>
                <th>Block Time</th>
                <th>Crew</th>
                <th>Mission Category</th>
                <th>Remark</th>
            </tr>
        </thead>
        <tbody>
            @php $totalBlokTime=array();@endphp
            @foreach($aircrafts as $key => $aircraft)
                 @php 
                    $data = \App\Models\FlyingLog::with(['pilot1', 'pilot2', 'aircraft'])->where('aircraft_id', $aircraft->id);
                    if(!empty($from)&&empty($to))
                    {
                        $data->where('departure_time','>=',date('Y-m-d', strtotime($from))); 
                    }
                    if(empty($from)&&!empty($to))
                    {
                        $data->where('departure_time','<=',date('Y-m-d', strtotime($to))); 
                    }
                    if(!empty($from)&&!empty($to))
                    {
                        $data->where(function($q) use($from, $to){
                            $q->whereBetween('departure_time', [date('Y-m-d', strtotime($from)), date('Y-m-d', strtotime($to))]);
                        }); 
                    }
                    if(!empty($flyingType))
                    {
                        $data->where('flying_type',$flyingType); 
                    }
                    $data->orderBy('departure_time', 'asc');
                    $result = $data->get();
                @endphp 
                
                @if($aircrafts->count()>1&& $result->count()>0)
                <tr>
                 <td colspan="7" class="text-left"><b>Aircraft Registration: {{$aircraft->call_sign}}</b></td>
                </tr>
                @endif
               
                @foreach($result as $value)
                    <tr>
                        <td>{{is_get_date_format($value->date)}}</td>
                        <td>{{$value->fron_sector}} / {{$value->to_sector}}</td>
                        <td>{{date('H:i',strtotime($value->departure_time))}} / {{ date('H:i',strtotime($value->arrival_time))}}</td>
                        <td>{{is_time_defrence($value->departure_time, $value->arrival_time)}}</td>
                        {{--  <td>{!!@$value->pilot1->salutation.' '.@$value->pilot1->name.'-'.$role[$value->pilot1_role].' /<br>'.@$value->pilot2->salutation . ' ' . @$value->pilot2->name.'-'.$role[$value->pilot2_role]!!}</td>  --}}
                        {{--  <td>{{$flying_type[$value->flying_type]}}</td>  --}}
                        <td>{!!@$value->pilot1->salutation.' '.@$value->pilot1->name.'-'.getMasterName($value->pilot1_role).' /<br>'.@$value->pilot2->salutation . ' ' . @$value->pilot2->name.'-'.getMasterName($value->pilot2_role)!!}</td>
                        <td>{{getMasterName($value->flying_type)}}</td>
                        <td>{{!empty($value->comment)?$value->comment:'N/A'}}</td>
                    </tr>
                    @php $totalBlokTime[$aircraft->call_sign][]=is_time_defrence($value->departure_time, $value->arrival_time);  @endphp
                @endforeach
            @endforeach
        </tbody>
    </table>
    
    <table class="table= table-bordered= text-center w-100 mt-3" border="1px">
        <tr>
            <th>Aircraft</th>
            <th>Total Block Time</th>
        </tr>
        @php $sum=array(); @endphp
        @foreach($totalBlokTime as $key => $value)
        <tr>
            <th>{{$key}}</th>
            <td>{{AddPlayTime($value)}}</td>
        </tr>
        @php $sum[]=AddPlayTime($value); @endphp
        @endforeach
        <tr>
            <th>Total</th>
            <th>{{AddPlayTime($sum)}}</th>
        </tr>
    </table>
    
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>