@extends('theme-one.layouts.app',['title' => 'Dashboard','sub_title'=>''])
@section('css')

@endsection

@section('content')
<div class="row row-xs">
    <div class="col-md-9">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body p-2">
                        <div class="row">
                            <div class="col-8">
                                <div class="p-2">
                                    <h6 class="tx-uppercase tx-14 tx-spacing-1 tx-color-02 tx-semibold m-0">Total Flight
                                    </h6>
                                    <div class="d-flex d-lg-block d-xl-flex align-items-end">
                                        <div class="d-xl-flex align-items-end p-2">
                                            <h3 class="tx-normal tx-rubik lh-1">{{$TotalFlight}}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <img src="{{asset('images/img1.jpeg')}}" class="img-fluid w-100 p-1 border rounded"
                                    alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body p-2">
                        <div class="row">
                            <div class="col-8">
                                <div class="p-2">
                                    <h6 class="tx-uppercase tx-14 tx-spacing-1 tx-color-02 tx-semibold m-0">Total Flying
                                        Hours
                                    </h6>
                                    <div class="d-flex d-lg-block d-xl-flex align-items-end">
                                        <div class="d-xl-flex align-items-end p-2">
                                            <h3 class="tx-normal tx-rubik lh-1">{{!empty($TotalFlyingHours)?$TotalFlyingHours:0}}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <img src="{{asset('images/img2.jpeg')}}" class="img-fluid w-100 p-1 border rounded"
                                    alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body p-2">
                        <div class="form-group">
                            <div class="input-group mt-3">
                                <div class="input-group-prepend">
                                    <div class="input-group-text ">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-search">
                                            <circle cx="11" cy="11" r="8"></circle>
                                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                        </svg>
                                    </div>
                                </div>
                                <input type="text" class="form-control" placeholder="Search">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row pt-4">
            <div class="col-sm-6 col-lg-3">
                <div class="card card-body p-2 text-center">
                    <h6 class="tx-uppercase tx-16 tx-spacing-1 tx-color-02 tx-semibold mg-b-8"> License</h6>
                    <div class="d-flex d-lg-block d-xl-flex align-items-end">
                        <div class="align-items-end p-2">
                            <p class="tx-14 tx-color-03 mg-b-0">
                                <span class="tx-medium tx-success d-inline-flex align-items-center">
                                    Active
                                </span>
                            </p>
                            <h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1">{{$TotalLicensesActive}}</h3>
                        </div>
                        <div class="align-items-end p-2">
                            <p class="tx-14 tx-color-03 mg-b-0">
                                <span class="tx-medium tx-warning d-inline-flex align-items-center ">
                                    Renewal
                                </span>
                            </p>
                            <h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1">{{$TotalLicensesRenuwal}}</h3>
                        </div>
                        <div class="align-items-end p-2">
                            <p class="tx-14 tx-color-03 mg-b-0">
                                <span class="tx-medium tx-danger d-inline-flex align-items-center">
                                    Lapsed
                                </span>
                            </p>
                            <h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1">{{$TotalLicensesLapsed}}</h3>
                        </div>
                    </div>
                </div>
            </div><!-- col -->
            <div class="col-sm-6 col-lg-3">
                <div class="card card-body p-2 text-center">
                    <h6 class="tx-uppercase tx-16 tx-spacing-1 tx-color-02 tx-semibold mg-b-8"> Training</h6>
                    <div class="d-flex d-lg-block d-xl-flex align-items-end">
                        <div class="align-items-end p-2">
                            <p class="tx-14 tx-color-03 mg-b-0">
                                <span class="tx-medium tx-success d-inline-flex align-items-center">
                                    Active
                                </span>
                            </p>
                            <h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1">{{$TotalTrainingActive}}</h3>
                        </div>
                        <div class="align-items-end p-2">
                            <p class="tx-14 tx-color-03 mg-b-0">
                                <span class="tx-medium tx-warning d-inline-flex align-items-center">
                                    Renewal
                                </span>
                            </p>
                            <h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1">{{$TotalTrainingRenuwal}}</h3>
                        </div>
                        <div class="align-items-end p-2">
                            <p class="tx-14 tx-color-03 mg-b-0">
                                <span class="tx-medium tx-danger d-inline-flex align-items-center">
                                    Lapsed
                                </span>
                            </p>
                            <h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1">{{$TotalTrainingLapsed}}</h3>
                        </div>
                    </div>
                </div>
            </div><!-- col -->
            <div class="col-sm-6 col-lg-3">
                <div class="card card-body p-2 text-center">
                    <h6 class="tx-uppercase tx-16 tx-spacing-1 tx-color-02 tx-semibold mg-b-8"> Ground Training
                    </h6>
                    <div class="d-flex d-lg-block d-xl-flex align-items-end">
                        <div class="align-items-end p-2">
                            <p class="tx-14 tx-color-03 mg-b-0">
                                <span class="tx-medium tx-success d-inline-flex align-items-center">
                                    Active
                                </span>
                            </p>
                            <h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1">{{$TotalGroundTrainingActive}}</h3>
                        </div>
                        <div class="align-items-end p-2">
                            <p class="tx-14 tx-color-03 mg-b-0">
                                <span class="tx-medium tx-warning d-inline-flex align-items-center">
                                    Renewal
                                </span>
                            </p>
                            <h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1">{{$TotalGroundTrainingRenuwal}}</h3>
                        </div>
                        <div class=" align-items-end p-2">
                            <p class="tx-14 tx-color-03 mg-b-0">
                                <span class="tx-medium tx-danger d-inline-flex align-items-center">
                                    Lapsed
                                </span>
                            </p>
                            <h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1">{{$TotalGroundTrainingLapsed}}</h3>
                        </div>
                    </div>
                </div>
            </div><!-- col -->
            <div class="col-sm-6 col-lg-3">
                <div class="card card-body p-2 text-center">
                    <h6 class="tx-uppercase tx-16 tx-spacing-1 tx-color-02 tx-semibold mg-b-8"> Medical</h6>
                    <div class="d-flex d-lg-block d-xl-flex align-items-end">
                        <div class="align-items-end p-2">
                            <p class="tx-14 tx-color-03 mg-b-0">
                                <span class="tx-medium tx-success d-inline-flex align-items-center">
                                    Active
                                </span>
                            </p>
                            <h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1">{{$TotalMedicalActive}}</h3>
                        </div>
                        <div class="align-items-end p-2">
                            <p class="tx-14 tx-color-03 mg-b-0">
                                <span class="tx-medium tx-warning d-inline-flex align-items-center">
                                    Renewal
                                </span>
                            </p>
                            <h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1">{{$TotalMedicalRenuwal}}</h3>
                        </div>
                        <div class="align-items-end p-2">
                            <p class="tx-14 tx-color-03 mg-b-0">
                                <span class="tx-medium tx-danger d-inline-flex align-items-center">
                                    Lapsed
                                </span>
                            </p>
                            <h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1">{{$TotalMedicalLapsed}}</h3>
                        </div>
                    </div>
                </div>
            </div><!-- col -->
        </div>
        <div class="row pt-4">
            <div class="col-md-12">
                <div class="card ">
                    <div class="card-header p-2">
                        <h6 class="card-title">Last Flight Schedule</h6>
                    </div>
                    <div class="card-body p-2">
                        <div class="progress-bar bg-success border-2 rounded-pill p-2 m-2">
                            <p class="tx-16 tx-white mb-0"> {{!empty($RecentFlightSchedule)?$RecentFlightSchedule->aircraft->call_sign:'NA'}}</p>
                        </div>
                        <div class="row p-1">
                            <div class="col-md-4 text-center">
                                <p class="m-0">{{!empty($RecentFlightSchedule)?date('d-m-Y', strtotime($RecentFlightSchedule->departure_time)):'NA'}} </p>
                                <div class="align-items-center border p-2 bg-success rounded">
                                    <div class="align-items-end">
                                        <img src="{{asset('images/img3.jpeg')}}" alt="" style="width:50px;height:50px;"
                                            class="rounded">
                                        <h3 class="mb-0 p-2 tx-white" style="float:right;width:80%;">{{!empty($RecentFlightSchedule)?$RecentFlightSchedule->fron_sector:'NA'}}</h3>
                                    </div>
                                </div>
                                <p>{{!empty($RecentFlightSchedule)?date('H:i', strtotime($RecentFlightSchedule->departure_time)):'NA'}}</p>
                            </div>
                            <div class="col-md-4 text-center">
                                <img src="{{asset('images/one-to-location.png')}}" alt="" class="w-25">
                                <p>{{!empty($RecentFlightSchedule)?colculate_days_hours_mints($RecentFlightSchedule->total_mints):'NA'}}</p>
                            </div>
                            <div class="col-md-4">
                                <p class="m-0">{{!empty($RecentFlightSchedule)?date('d-m-Y', strtotime($RecentFlightSchedule->arrival_time)):'NA'}}</p>
                                <div class="align-items-center border p-2 bg-success rounded">
                                    <div class="align-items-end">
                                        <img src="{{asset('images/img4.jpeg')}}" alt=""
                                            style="width:50px;height:50px;float:right;" class="rounded full-right">
                                        <h3 class="mb-0 p-2 tx-white">{{!empty($RecentFlightSchedule)?$RecentFlightSchedule->to_sector:'NA'}}</h3>
                                    </div>
                                </div>
                                <p>{{!empty($RecentFlightSchedule)?date('H:i', strtotime($RecentFlightSchedule->arrival_time)):'NA'}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 hv-100">
        <div class="card">
            <div class="card-header p-2">
                <h6 class="card-title">Aircraft Status</h6>
            </div>
            <div class="card-body p-2">
                <div class="row=">
                    <div class="col-md-12 d-flex p-1 m-1">
                        <i class="fas fa-plane fa-2x border p-3 border-success rounded"></i>
                        <div class="w-100 text-center ">
                            <div class="d-flex w-100">
                                <p class="tx-16 tx-bold m-2 w-50">VT-UPM</p>
                                <span class="tx-16 tx-bold m-2 w-50">Airworthy</span>
                            </div>
                            <span>Last Maintenance: 10-4-2024</span>
                        </div>
                    </div>
                    <hr class="p-0">
                    <div class="col-md-12 d-flex p-1 m-1">
                        <i class="fas fa-plane fa-2x border p-3 border-success rounded"></i>
                        <div class="w-100 text-center ">
                            <div class="d-flex w-100">
                                <p class="tx-16 tx-bold m-2 w-50">VT-UPM</p>
                                <span class="tx-16 tx-bold m-2 w-50">Airworthy</span>
                            </div>
                            <span>Last Maintenance: 10-4-2024</span>
                        </div>
                    </div>
                    <hr class="p-0">
                    <div class="col-md-12 d-flex p-1 m-1">
                        <i class="fas fa-plane fa-2x border p-3 border-success rounded"></i>
                        <div class="w-100 text-center ">
                            <div class="d-flex w-100">
                                <p class="tx-16 tx-bold m-2 w-50">VT-UPM</p>
                                <span class="tx-16 tx-bold m-2 w-50">Airworthy</span>
                            </div>
                            <span>Last Maintenance: 10-4-2024</span>
                        </div>
                    </div>
                    <hr class="p-0">
                    <div class="col-md-12 d-flex p-1 m-1">
                        <i class="fas fa-plane fa-2x border p-3 border-success rounded"></i>
                        <div class="w-100 text-center ">
                            <div class="d-flex w-100">
                                <p class="tx-16 tx-bold m-2 w-50">VT-UPM</p>
                                <span class="tx-16 tx-bold m-2 w-50">Airworthy</span>
                            </div>
                            <span>Last Maintenance: 10-4-2024</span>
                        </div>
                    </div>
                    <hr class="p-0">
                    <div class="col-md-12 d-flex p-1 m-1">
                        <i class="fas fa-plane fa-2x border p-3 border-success rounded"></i>
                        <div class="w-100 text-center ">
                            <div class="d-flex w-100">
                                <p class="tx-16 tx-bold m-2 w-50">VT-UPM</p>
                                <span class="tx-16 tx-bold m-2 w-50">Airworthy</span>
                            </div>
                            <span>Last Maintenance: 10-4-2024</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 pt-4">
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header p-2">
                        <h6 class="card-title">Recent Flight Schedule</h6>
                    </div>
                    <div class="card-body p-2">
                        <div class="row">
                            <div class="col-md-12 p-2 m-1">
                                <canvas id="flotCharts"  class="flot-chart" style="height: 200px;width: 100%;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header p-2">
                        <h6 class="card-title">Weather Report</h6>
                    </div>
                    <div class="card-body p-2">
                        <div class="row">
                            <div class="col-md-12 p-2 m-1">
                            <div id="ww_53a2d02d9b84e" style="height:335px; max-width:99%;" v='1.3' loc='auto' a='{"t":"horizontal","lang":"en","sl_lpl":1,"ids":["wl3394"],"font":"Arial","sl_ics":"one_a","sl_sot":"celsius","cl_bkg":"image","cl_font":"#FFFFFF","cl_cloud":"#FFFFFF","cl_persp":"#81D4FA","cl_sun":"#FFC107","cl_moon":"#FFC107","cl_thund":"#FF5722"}'>More forecasts: <a href="https://oneweather.org/fuerteventura/august/" id="ww_53a2d02d9b84e_u" target="_blank">oneweather.org</a></div><script async src="https://app2.weatherwidget.org/js/?id=ww_53a2d02d9b84e"></script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 pt-4">

    </div>
</div>




<div class="row row-xs">


</div>
<div class="row row-xs p-1">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header p-2">
                <h6 class="card-title">Performance Metrics</h6>
            </div>
            <div class="card-body p-2">
                <div class="row">
                    @php
                    $barChartDate=array();
                    $barChartValue=array();
                    foreach($barChartData as $key => $value){
                        $barChartDate[] = date('d-m-Y', strtotime($value->date));
                        $barChartValue[]=$value->total;
                    }
                    @endphp
                    <div class="col-md-12 chart-three p-2">
                        <canvas id="flotCharts2" class="flot-chart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script type="text/javascript">
var ctx = document.getElementById('flotCharts').getContext('2d');
ctx.width = 400;  // Set your desired width
ctx.height = 300; // Set your desired height
var myChart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: [],
        height: 300,
        datasets: [{
            label: '# of Votes',
            data: [1, 2, 9, 10, 9, 7],
            backgroundColor: [
                'rgba(255, 159, 64, 0.7)',
                'rgba(255, 99, 132, 0.7)',
                'rgba(54, 162, 235, 0.7)',
                'rgba(255, 206, 86, 0.7)',
                'rgba(75, 192, 192, 0.7)',
                'rgba(153, 102, 255, 0.7)',

            ]
        }]
    }
});
var ctx = document.getElementById('flotCharts2').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!!json_encode($barChartDate)!!},
        datasets: [{
            label: 'Flying',
            data: '{{json_encode($barChartValue)}}',
        }],
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    }
})
</script>
@endsection
