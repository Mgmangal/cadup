<x-app-layout>
    <x-slot name="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('app.dashboard')}}">DASHBOARD</a></li>
            <!-- <li class="breadcrumb-item active">STARTER PAGE</li> -->
        </ul>
    </x-slot>
    <x-slot name="css">

    </x-slot>

    <div class="row">
        <div class="col-xl-12">
            <div class="row">
                <div class="col-sm-3">
                    <div class="card mb-3 overflow-hidden fs-13px border-0 bg-gradient-custom-orange" style="min-height: 100px;">
                        <div class="card-body position-relative ">
                            <h5 class="text-white text-opacity-80 mb-3 fs-25px">Total Employees</h5>
                            <h3 class="text-white mt-n1 fs-40px text-end">{{$total_user}}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card mb-3 overflow-hidden fs-13px border-0 bg-gradient-custom-teal" style="min-height: 100px;">
                        <div class="card-body position-relative">
                            <h5 class="text-white text-opacity-80 mb-3 fs-25px">Total Aircraft</h5>
                            <h3 class="text-white mt-n1 fs-40px text-end">{{$total_air_croft}}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="card mb-3 overflow-hidden fs-13px border-0 bg-gradient-custom-pink" style="min-height: 100px;">
                        <div class="card-body position-relative ">
                            <h5 class="text-white text-opacity-80 mb-3 fs-25px">Available Pilots</h5>
                            <h3 class="text-white mt-n1 fs-40px text-end">{{$active_pilot}}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card mb-3 overflow-hidden fs-13px border-0 bg-gradient-custom-indigo" style="min-height: 100px;">
                        <div class="card-body position-relative">
                            <h5 class="text-white text-opacity-80 mb-3 fs-25px">Serviceable Aircraft</h5>
                            <h3 class="text-white mt-n1 fs-40px text-end">{{$active_air_croft}}</h3>
                        </div>
                    </div>
                </div>



                @if(0)
                <div class="col-sm-3">
                    <div class="card mb-3 overflow-hidden fs-13px border-0 bg-gradient-custom-pink" style="min-height: 100px;">
                        <!-- <div class="card-img-overlay mb-n4 me-n4 d-flex" style="bottom: 0; top: auto;">
                            <img src="{{asset('assets/img/icon/email.svg')}}" alt="" class="ms-auto d-block mb-n3" style="max-height: 105px" />
                        </div> -->
                        <div class="card-body position-relative">
                            <h5 class="text-white text-opacity-80 mb-3 fs-25px">Total Designation </h5>
                            <h3 class="text-white mt-n1 fs-40px text-end">{{$total_designation}}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card mb-3 overflow-hidden fs-13px border-0 bg-gradient-custom-indigo" style="min-height: 100px;">
                        <!-- <div class="card-img-overlay mb-n4 me-n4 d-flex" style="bottom: 0; top: auto;">
                            <img src="{{asset('assets/img/icon/browser.svg')}}" alt="" class="ms-auto d-block mb-n3" style="max-height: 105px" />
                        </div> -->
                        <div class="card-body position-relative">
                            <h5 class="text-white text-opacity-80 mb-3 fs-25px">Total Sections</h5>
                            <h3 class="text-white mt-n1 fs-40px text-end">{{$total_section}}</h3>
                        </div>

                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-xl-12 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex mb-3">
                        <div class="flex-grow-1">
                            <h5 class="mb-1">Flying Analytics</h5>
                            <div class="fs-13px"></div>
                        </div>
                        <a href="#" data-bs-toggle="dropdown" class="text-muted"><i class="fa fa-redo"></i></a>
                    </div>
                    <div id="chart"></div>
                </div>
            </div>
        </div>

    </div>


    <div class="row">


    </div>







    <x-slot name="js">
        @php 
        $viewData=array();
        $viewDate=array();
        foreach($chartData as $key => $value){
         
            $viewData[] = $value->views;
            $viewDate[] = is_get_date_format($value->date);
        }
        @endphp
        <script src="{{asset('assets/plugins/apexcharts/dist/apexcharts.min.js')}}" type="text/javascript"></script>
        <script>
            let numData={!!json_encode($viewData)!!};
            let numDate={!!json_encode($viewDate)!!};
            var options = {
            series: [{
                name: "Sector",
                data: numData
            }],
            chart: {
            height: 350,
            type: 'line',
            zoom: {
                enabled: false
            }
            },
            dataLabels: {
            enabled: false
            },
            stroke: {
            curve: 'straight'
            },
            title: {
            text: 'Flying statistics in Last 30 days',
            align: 'left'
            },
            grid: {
            row: {
                colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                opacity: 0.5
            },
            },
            xaxis: {
            categories: numDate,
            }
            };

            var chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();
        
        </script>
        
        <!-- <script src="{{asset('assets/js/demo/dashboard.demo.js')}}" type="text/javascript"></script> -->
    </x-slot>
</x-app-layout>