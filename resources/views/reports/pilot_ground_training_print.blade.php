<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Pilot Ground Training</title>
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
    <!--<body>-->
    <!--<header>-->
    {{--  <table class="table table-bordered text-center w-100 ">
        <tr>
            <th colspan="2">
                <h4> Pilot Ground Training</h4>
            </th>
        </tr>
        <tr>
            <th colspan="2">
                <h5>{{ date('d/m/Y', strtotime($date)) }}</h5>
            </th>
        </tr>
    </table>  --}}
    <div class="row py-5 text-center">
        @php
            if ($aircraft == 'Fixed Wing') {
                $title = 'Pilot Ground Training';
            } else {
                $title = 'Pilot Ground Training/RHC Check-';
            }
        @endphp
        <div class="col-md-12">
            <h4><u>{{ $title }}</u></h4>
        </div>
        <div class="col-md-12">
            <h4><u>{{ date('F-Y', strtotime($date)) }}</u></h4>
        </div>
        <div class="col-md-12">
            <h4><b><u>{{ $aircraft }}</u></b></h4>
        </div>
    </div>
    <!--</header>-->
    <table class="table= table-bordered= text-center w-100" border="1 px solid;">
        <thead>
            <tr>
                <th colspan="1">S.No</th>
                <th colspan="1">Name</th>
                <th colspan="7">Ground Training</th>
                <th colspan="2"></th>
            </tr>
            @if ($aircraft == 'Fixed Wing')
                <tr>
                    <th></th>
                    <th></th>
                    <th>CRM</th>
                    <th>Monsoon</th>
                    <th>DGR</th>
                    <th>AVSEC</th>
                    <th>SEP</th>
                    <th>SMS</th>
                    <th>Ground Refresher</th>
                    <th>RHS Check</th>
                    <th>RVSM</th>
                </tr>
            @else
                <tr>
                    <th rowspan="2"></th>
                    <th rowspan="2"></th>
                    <th rowspan="2">CRM</th>
                    <th rowspan="2">Monsoon</th>
                    <th rowspan="2">DGR</th>
                    <th rowspan="2">AVSEC</th>
                    <th rowspan="2">SEP</th>
                    <th rowspan="2">SMS</th>
                    <th colspan="2">Ground Refresher</th>
                </tr>
                <tr>
                   <th rowspan="2">Bell-412</th>
                    <th rowspan="2">A-109S</th>
                </tr>
            @endif
        </thead>
        <tbody>

            @foreach ($pilots as $key => $value)
                <tr>
                    <td>{{ ++$key }}</td>
                    <td class="text-left">{{ $value->fullName() }}</td>
                    <td>{{ checkGroundTrainingValidity($value->id, '16') }}</td>
                    <td>{{ checkGroundTrainingValidity($value->id, '19') }}</td>
                    <td>{{ checkGroundTrainingValidity($value->id, '26') }}</td>
                    <td>{{ checkGroundTrainingValidity($value->id, '20') }}</td>
                    <td>{{ checkGroundTrainingValidity($value->id, '638') }}</td>
                    <td>{{ checkGroundTrainingValidity($value->id, '23') }}</td>
                    <td>{{ checkGroundTrainingValidity($value->id, '637') }}</td>
                    <td>{{ checkGroundTrainingValidity($value->id, '17') }}</td>
                    @if ($aircraft == 'Fixed Wing')
                        <td>{{ checkGroundTrainingValidity($value->id, '29') }}</td>
                    @else
                        <td></td>
                    @endif
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
