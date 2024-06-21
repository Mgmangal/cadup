<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Details of Flying Test Done</title>
    <style>
        @media print {
            @page {
                /*size: landscape */
            }
        }

        .table {
            color: black;
        }
    </style>
</head>

<body onload="window.print();">

    @php
        $title = 'DETAILS OF FLYING TEST DONE - ' . date('F-Y', strtotime($date));
        $subtitle ='The details of Pilots (Fixed/Rotor Wing) who have undergone routine medical examination and other refresher courses & Tests for validity of their flying licenses are as under:-';
    @endphp

    <div class="row py-5 text-center">
        <div class="col-md-12">
            <h4><u>{{ $title }}</u></h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 px-5">
            <p>&nbsp;&nbsp;&nbsp;&nbsp;{{ $subtitle }}</p>
        </div>
    </div>
    <table class="table= table-bordered= text-center w-100" border="1px">
        <thead>
            <tr>
                <!--<th>S.No</th>-->
                <th>Name</th>
                <th>Course/Tests</th>
                <th>Remarks</th>
            </tr>

        </thead>
        <tbody>
            @php $i=0; @endphp
            @foreach($pilots as $pilot)
                @php 
                    $title='';
                    $ac='';
                    $training = \App\Models\PilotTraining::where('user_id', $pilot->id)->whereBetween('renewed_on', [$startOfMonth, $endOfMonth])->latest()->get();
                    foreach($training as $k=> $training)
                    {
                        $title.= $k>0?'/':'';
                        $title.= getMasterName($training->training_id);
                        $ac=$training->aircroft_registration;
                    }
                @endphp
                @if(!empty($title))
                <tr>
                    <!--<td>{{ ++$i }}</td>-->
                    <td class="text-left">{{ $pilot->fullName() }}</td>
                    <td> 
                        {{$title}} ({{$ac}})
                    </td>
                    <td>Revalidated</td>
                </tr>
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
