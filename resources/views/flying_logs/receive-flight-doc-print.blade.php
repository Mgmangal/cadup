<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Post Flight Document Report</title>
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
    <table class="table table-bordered text-center w-100">
        <thead>
            <tr rowspan="2">
                <th colspan="8"><h4> Post Flight Document Report</h4></th>
            </tr>
            <tr rowspan="2">
                <th colspan="8"><h5>{{ date('d/m/Y', strtotime($from)) }} to {{ date('d/m/Y', strtotime($to)) }}</h5></th>
            </tr>
            <tr>
                <th>Bunch Number</th>
                <th>Date</th>
                <th>Aircraft</th>
                <th>Sector From/To</th>
                <th>Pilots</th>
                <th>Day Officer</th>
                <th>Flight Doc List</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $key => $value)
                <tr>
                    <td>{{ $value->bunch_no }}</td>
                    <td>{{ is_get_date_format($value->dates) }}</td>
                    <td>
                        @if (!empty($value->flying_logs))
                            @php
                                $logs = \App\Models\FlyingLog::whereIn('id', array_unique($value->flying_logs))
                                        ->with('aircraft')
                                        ->get();
                            @endphp
                            @forelse ($logs as $flying_log)
                                {{ $flying_log->aircraft->call_sign }} @if (!$loop->last)<br> @endif
                            @empty
                                N/A
                            @endforelse
                        @endif
                    </td>
                    <td>
                        @if (!empty($value->flying_logs))
                            @php
                                $logs = \App\Models\FlyingLog::whereIn('id', array_unique($value->flying_logs))
                                        ->with('aircraft')
                                        ->get();
                            @endphp
                            @forelse ($logs as $flying_log)
                                {{ $flying_log->fron_sector }} /{{ $flying_log->to_sector }}@if (!$loop->last)<br> @endif
                            @empty
                                N/A
                            @endforelse
                        @endif
                    </td>
                    <td>
                        @if (!empty($value->flying_logs))
                            @php
                                $logs = \App\Models\FlyingLog::whereIn('id', array_unique($value->flying_logs))
                                        ->with('aircraft')
                                        ->get();
                            @endphp
                            @forelse ($logs as $flying_log)
                                {{ $flying_log->pilot1->name }}<br>{{ $flying_log->pilot2->name }}
                            @empty
                                N/A
                            @endforelse
                        @endif
                    </td>
                    <td>{{ getMasterName($value->day_officers) }}</td>
                    <td>
                        @if (!empty($value->documents))
                            @foreach($value->documents as $id => $file)
                                {{ getMasterName($id) }}@if (!$loop->last), @endif
                            @endforeach
                        @endif
                    </td>
                    <td>{{ ($value->remark)?$value->remark:'-' }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="2"><b>Total Bunches</b></td>
                <td colspan="6"><b>{{ count($users) }}</b></td>
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
