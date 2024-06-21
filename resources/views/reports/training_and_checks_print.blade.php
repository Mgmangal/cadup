<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
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
        .bodermn{
            border-top: 1px solid;
            border-bottom: 1px solid;
            display: block;
        }
    </style>
</head>

<body onload="window.print();">

    @php
        $title = 'THE TRAINING AND CHECKS OF CADUP PILOTS DUE BETWEEN 60 DAYS ARE AS UNDER:';
        $subtitle = strtoupper($aircraft) . ' : ' . date('F-Y', strtotime($date));
    @endphp

    <div class="row py-5 text-center">
        <div class="col-md-12">
            <h4><u>{{ $title }}</u></h4>
            <h4><u>{{ $subtitle }}</u></h4>
        </div>
    </div>
    <!--date-->
    <table class="table= table-bordered= text-center w-100" border="1px solid">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Pilot's Name</th>
                <th>License/Training</th>
                <th>Due Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pilots as $key => $value)
                @php 
                    $licence = \App\Models\PilotLicense::where('is_applicable', 'yes')
                    ->where('user_id', $value->id)->whereBetween('next_due', [date('Y-m-d',strtotime($date)), date('Y-m-d',strtotime($date. ' + 60 days'))])
                    ->orderBy('id', 'DESC')
                    ->groupBy('license_id')
                    ->get();
                    
                    $trainings = \App\Models\PilotTraining::where('is_applicable', 'yes')
                        ->where('user_id', $value->id)->whereBetween('next_due', [date('Y-m-d',strtotime($date)), date('Y-m-d',strtotime($date. ' + 60 days'))])
                        ->orderBy('id', 'DESC')
                        ->groupBy('training_id')
                        ->get();
                    $Groundtrainings = \App\Models\PilotGroundTraining::where('is_applicable', 'yes')
                        ->where('user_id', $value->id)->whereBetween('next_due', [date('Y-m-d',strtotime($date)), date('Y-m-d',strtotime($date. ' + 60 days'))])
                        ->orderBy('id', 'DESC')
                        ->groupBy('training_id')
                        ->get();
                    $medicals = \App\Models\PilotMedical::where('is_applicable', 'yes')
                        ->where('user_id', $value->id)->whereBetween('next_due', [date('Y-m-d',strtotime($date)), date('Y-m-d',strtotime($date. ' + 60 days'))])
                        ->orderBy('id', 'DESC')
                        ->groupBy('medical_id')
                        ->get();
                @endphp
                        
                <tr>
                    <td>{{ ++$key }}</td>
                    <td class="text-left">{{ $value->fullName() }}</td>
                    <td>
                        @foreach($licence as $k=> $licence)
                            
                             <span class="bodermn">
                           
                            {{getMasterName($licence->license_id)}}
                            
                             </span>
                           
                        @endforeach
                        
                        @foreach($trainings as  $k=>  $training)
                            
                             <span class="bodermn">
                            
                            {{getMasterName($training->training_id)}}
                           
                             </span>
                           
                        @endforeach
                        @foreach($Groundtrainings as  $k=>  $Groundtraining)
                            
                             <span class="bodermn">
                           
                            {{getMasterName($Groundtraining->training_id)}}
                           
                             </span>
                            
                        @endforeach
                        @foreach($medicals as  $k=>  $medical)
                           
                             <span class="bodermn">
                           
                            {{getMasterName($medical->medical_id)}}
                            
                             </span>
                            
                        @endforeach
                    </td>
                    <td>
                        @foreach($licence as  $k=>  $licence)
                            
                             <span class="bodermn">
                           
                            {{$licence->next_due}}
                            
                             </span>
                            
                        @endforeach
                        
                        @foreach($trainings as  $k=>  $training)
                            
                             <span class="bodermn">
                            
                            {{$training->next_due}}
                            
                             </span>
                           
                        @endforeach
                        @foreach($Groundtrainings as  $k=>  $Groundtraining)
                            
                             <span class="bodermn">
                           
                            {{$Groundtraining->next_due}}
                            
                             </span>
                           
                        @endforeach
                        @foreach($medicals as  $k=>  $medical)
                            
                             <span class="bodermn">
                           
                            {{$medical->next_due}}
                            
                             </span>
                           
                        @endforeach
                    </td>
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
