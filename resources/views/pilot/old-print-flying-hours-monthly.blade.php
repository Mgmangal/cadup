<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Pilot Flying Hours</title>
    <style>
        @media print {
            @page {
                size: landscape
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
        <tr>
            <th colspan="2">
                <h4> Pilot Flying Hours</h4>
            </th>
        </tr>
        <tr>
            <th colspan="2">
                <h5>{{ date('d/m/Y', strtotime($from)) }} to {{ date('d/m/Y', strtotime($to)) }}</h5>
            </th>
        </tr>
    </table>
    <!--</header>-->
    <table class="table table-bordered text-center w-100">
        <thead>
            <tr>
                <th>Crew</th>
                @foreach ($months as $key => $month)
                    <th>{{ date('M', strtotime($month)) }}</th>
                @endforeach
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalTime = [];
                $culmnsTime = [];
            @endphp
            @foreach ($users as $key => $value)
                <tr>
                    @php
                        $rowTime = [];
                    @endphp
                    <td  class="text-left">{{ $value->fullName() }}</td>
                    @foreach ($months as $key => $month)
                        @php
                            $time = get_Pilot_month_Flying_Hours($value->id, $month);
                            $rowTime[] = $time;
                            $culmnsTime[$key][] = $time;
                        @endphp
                        <td>{{ $time }}</td>

                    @endforeach
                    @php
                        $rttime = AddPlayTime($rowTime);
                        $totalTime[] = $rttime;
                    @endphp
                    <td>{{ $rttime }}</td>
                </tr>
            @endforeach

            <tr>
                <td colspan="1"><b>Total</b></td>
                @foreach ($culmnsTime as $key => $value)
                    <td>{{ AddPlayTime($value) }}</td>
                @endforeach
                <td colspan="1"><b>{{ AddPlayTime($totalTime) }}</b></td>
            </tr>
        
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
