<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>External Flying Detail</title>
    <style>
        @media print {
            @page {
                /*size: landscape*/
            }
        }

        .table {
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
    <table class="table table-bordered text-center w-100 ">
        <tr>
            <th colspan="2">
                <h4> External Flying Detail</h4>
            </th>
        </tr>
        <tr>
            <th colspan="2">
                <h5> Period : {{ date('d/m/Y', strtotime($from)) }} to {{ date('d/m/Y', strtotime($to)) }}
                </h5>
            </th>
        </tr>
    </table>
    <!--</header>-->
    <table class="table table-bordered text-center w-100">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Date</th>
                <th>A/C Type</th>
                <th>A/C Registration</th>
                <th>Sector</th>
                <th>Chocks-Off/<br>Chocks-On</th>
                <th>Flight Time</th>
                <th>Examiner/<br>Instructor</th>
                <th>P1 U/S</th>
                <th>Remarks</th>
                <th>Signature</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalTimeInSeconds = 0;
            @endphp
            @foreach ($results as $key => $value)
                @php
                    $departureTime = strtotime($value->departure_time);
                    $arrivalTime = strtotime($value->arrival_time);
                    $timeDifference = $arrivalTime - $departureTime;
                    $totalTimeInSeconds += $timeDifference;
                @endphp
                <tr>
                    <td>{{ ++$key }}</td>
                    <td>{{ is_get_date_format($value->date) }}</td>
                    <td>{{ getMasterName($value->aircraft_type) }}</td>
                    <td>{{ $value->aircraft_id }}</td>
                    <td>{{ $value->fron_sector }} /<br>{{ $value->to_sector }}</td>
                    <td>{{ date('H:i', strtotime($value->departure_time)) }}
                        /<br>{{ date('H:i', strtotime($value->arrival_time)) }}</td>
                    <td>{{ is_time_defrence($value->departure_time, $value->arrival_time) }}</td>
                    <!--<td>{{ getMasterName($value->flying_type) }}</td> . '-' . getMasterName($value->pilot1_role) . ' '. '-' . getMasterName($value->pilot2_role) -->
                    <td>{!! @$value->pilot1->salutation . ' ' . @$value->pilot1->name !!}</td>
                    <td>{!! @$value->pilot2_id !!}</td>
                    <td></td>
                    <td></td>
                </tr>
            @endforeach
            @php
                // Convert total time from seconds to HH:mm format
                $totalHours = floor($totalTimeInSeconds / 3600);
                $totalMinutes = floor(($totalTimeInSeconds % 3600) / 60);
                $totalTimeFormatted = sprintf('%02d:%02d', $totalHours, $totalMinutes);
            @endphp
            <tfoot>
                <tr>
                    <td colspan="6"><b>Grand Total</b></td>
                    <td colspan="5"><b>{{ $totalTimeFormatted }}</b></td>
                </tr>
            </tfoot>
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
