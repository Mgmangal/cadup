<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Pilot Aircraft-wise Flying Summary</title>
    <style>
        @media print {
            @page {
                size: landscape
            }
            .pagebreak {
                page-break-before: always;
            }
        }
        .table {
            color: black;
        }
    </style>
</head>

<body onload="window.print();">
    <!--<body>-->
    <!--<header>-->
    <table class="table table-bordered text-center w-100 ">
        <tr rowspan="2">
            <th colspan="2">
                <h4> Pilot Aircraft-wise Flying Summary</h4>
                <h5>{{ date('d/m/Y', strtotime($from)) }} to {{ date('d/m/Y', strtotime($to)) }}</h5>
            </th>
        </tr>
    </table>

    <table class="table table-bordered text-center w-100">
        <thead>
            <tr>
                <th>Pilot</th>
                @foreach($aircrafts as $key => $aircraft)
                    <th colspan="2">{{ $aircraft->call_sign }}</th>
                @endforeach
                <th>Total</th>
            </tr>
            <tr>
                <th></th>
                @foreach($aircrafts as $key => $aircraft)
                    <th>P1</th>
                    <th>P2</th>
                @endforeach
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr><td colspan="14" class="text-center"><b>Fixed Wing</b></td></tr>
            @foreach ($fixed_wing_pilots as $key => $value)
                @php
                    $tw=[];
                @endphp
            <tr>
                <td class="text-left">{{ $value->fullName() }}</td>
                @foreach($aircrafts as $key => $aircraft)
                @php
                    $p1=PilotsAircraftWiseFlyingSummary($value->id,'578',$aircraft->id,date('Y-m-d', strtotime($from)),date('Y-m-d', strtotime($to)));
                    $p2=PilotsAircraftWiseFlyingSummary($value->id,'579',$aircraft->id,date('Y-m-d', strtotime($from)),date('Y-m-d', strtotime($to)));
                    $tw[]=$p1;
                    $tw[]=$p2;
                @endphp
                <td class="">{{$p1!='00:00'?$p1:''}}</td>
                <td class="">{{$p2!='00:00'?$p2:''}}</td>
                @endforeach
                <td>{{AddPlayTime($tw)}}</td>
            </tr>
            @endforeach
        </tbody>
        <tbody>
            <tr><td colspan="14" class="text-center"><b>Rotor Wing</b></td></tr>
            @foreach ($rotor_wing_pilots as $key => $value)
                @php
                    $tw=[];
                @endphp
            <tr>
                <td class="text-left">{{ $value->fullName() }}</td>
                @foreach($aircrafts as $key => $aircraft)
                @php
                    $p1=PilotsAircraftWiseFlyingSummary($value->id,'578',$aircraft->id,date('Y-m-d', strtotime($from)),date('Y-m-d', strtotime($to)));
                    $p2=PilotsAircraftWiseFlyingSummary($value->id,'579',$aircraft->id,date('Y-m-d', strtotime($from)),date('Y-m-d', strtotime($to)));
                    $tw[]=$p1;
                    $tw[]=$p2;
                @endphp
                <td class="">{{$p1!='00:00'?$p1:''}}</td>
                <td class="">{{$p2!='00:00'?$p2:''}}</td>
                @endforeach
                <td>{{AddPlayTime($tw)}}</td>
            </tr>
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
