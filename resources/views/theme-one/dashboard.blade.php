@extends('theme-one.layouts.app',['title' => 'Dashboard','sub_title'=>''])
@section('css')

@endsection

@section('content')
<div class="row row-xs">
    <div class="col-sm-6 col-lg-3">
        <div class="card card-body">
            <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">Total Licenses</h6>
            <div class="d-flex d-lg-block d-xl-flex align-items-end">
                <div class="d-xl-flex align-items-end p-2">
                    <h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1">11</h3>
                    <p class="tx-11 tx-color-03 mg-b-0">
                        <span class="tx-medium tx-success d-inline-flex align-items-center">
                            Active
                        </span>
                    </p>
                </div>
                <div class="d-xl-flex align-items-end p-2">
                    <h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1">11</h3>
                    <p class="tx-11 tx-color-03 mg-b-0">
                        <span class="tx-medium tx-warning d-inline-flex align-items-center">
                            Need Renuewal
                        </span>
                    </p>
                </div>
                <div class="d-xl-flex align-items-end p-2">
                    <h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1">11</h3>
                    <p class="tx-11 tx-color-03 mg-b-0">
                        <span class="tx-medium tx-danger d-inline-flex align-items-center">
                            Lapsed
                        </span>
                    </p>
                </div>
            </div>
            <!-- <div class="chart-three">
                <div id="flotChart3" class="flot-chart ht-30"></div>
            </div> -->
            <!-- chart-three -->
        </div>
    </div><!-- col -->
    <div class="col-sm-6 col-lg-3">
        <div class="card card-body">
            <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">Total Training</h6>
            <div class="d-flex d-lg-block d-xl-flex align-items-end">
                <div class="d-xl-flex align-items-end p-2">
                    <h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1">11</h3>
                    <p class="tx-11 tx-color-03 mg-b-0">
                        <span class="tx-medium tx-success d-inline-flex align-items-center">
                            Active
                        </span>
                    </p>
                </div>
                <div class="d-xl-flex align-items-end p-2">
                    <h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1">11</h3>
                    <p class="tx-11 tx-color-03 mg-b-0">
                        <span class="tx-medium tx-warning d-inline-flex align-items-center">
                            Need Renuewal
                        </span>
                    </p>
                </div>
                <div class="d-xl-flex align-items-end p-2">
                    <h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1">11</h3>
                    <p class="tx-11 tx-color-03 mg-b-0">
                        <span class="tx-medium tx-danger d-inline-flex align-items-center">
                            Lapsed
                        </span>
                    </p>
                </div>
            </div>
            <!-- <div class="chart-three">
                <div id="flotChart3" class="flot-chart ht-30"></div>
            </div> -->
            <!-- chart-three -->
        </div>
    </div><!-- col -->
    <div class="col-sm-6 col-lg-3">
        <div class="card card-body">
            <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">Total Ground Training</h6>
            <div class="d-flex d-lg-block d-xl-flex align-items-end">
                <div class="d-xl-flex align-items-end p-2">
                    <h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1">11</h3>
                    <p class="tx-11 tx-color-03 mg-b-0">
                        <span class="tx-medium tx-success d-inline-flex align-items-center">
                            Active
                        </span>
                    </p>
                </div>
                <div class="d-xl-flex align-items-end p-2">
                    <h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1">11</h3>
                    <p class="tx-11 tx-color-03 mg-b-0">
                        <span class="tx-medium tx-warning d-inline-flex align-items-center">
                            Need Renuewal
                        </span>
                    </p>
                </div>
                <div class="d-xl-flex align-items-end p-2">
                    <h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1">11</h3>
                    <p class="tx-11 tx-color-03 mg-b-0">
                        <span class="tx-medium tx-danger d-inline-flex align-items-center">
                            Lapsed
                        </span>
                    </p>
                </div>
            </div>
            <!-- <div class="chart-three">
                <div id="flotChart3" class="flot-chart ht-30"></div>
            </div> -->
            <!-- chart-three -->
        </div>
    </div><!-- col -->
    <div class="col-sm-6 col-lg-3">
        <div class="card card-body">
            <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">Total Medical</h6>
            <div class="d-flex d-lg-block d-xl-flex align-items-end">
                <div class="d-xl-flex align-items-end p-2">
                    <h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1">11</h3>
                    <p class="tx-11 tx-color-03 mg-b-0">
                        <span class="tx-medium tx-success d-inline-flex align-items-center">
                            Active
                        </span>
                    </p>
                </div>
                <div class="d-xl-flex align-items-end p-2">
                    <h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1">11</h3>
                    <p class="tx-11 tx-color-03 mg-b-0">
                        <span class="tx-medium tx-warning d-inline-flex align-items-center">
                            Need Renuewal
                        </span>
                    </p>
                </div>
                <div class="d-xl-flex align-items-end p-2">
                    <h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1">11</h3>
                    <p class="tx-11 tx-color-03 mg-b-0">
                        <span class="tx-medium tx-danger d-inline-flex align-items-center">
                            Lapsed
                        </span>
                    </p>
                </div>
            </div>
            <!-- <div class="chart-three">
                <div id="flotChart3" class="flot-chart ht-30"></div>
            </div> -->
            <!-- chart-three -->
        </div>
    </div><!-- col -->
</div>
<div class="row row-xs pt-3 mt-3">
    <div class="col-md-3 chart-three p-4">
        <canvas id="flotCharts" class="flot-chart"></canvas >
    </div>
    <div class="col-md-6 chart-three p-4">
        <canvas id="flotCharts2" class="flot-chart"></canvas >
    </div>
</div>


@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script type="text/javascript">
    var ctx = document.getElementById('flotCharts').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Total Flying In 60 Days', 'MY Flying In 60 Days'],
            datasets: [{
                label: '# of Votes',
                data: [12, 9,],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                ]
            }]
        }
    });
    var ctx = document.getElementById('flotCharts2').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Total Flying In 60 Days', 'MY Flying In 60 Days'],
            datasets: [{
                label: '# of Votes',
                data: [12, 9,],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                ]
            }]
        }
    })
</script>
@endsection