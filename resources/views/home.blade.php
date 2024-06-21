@extends('layouts.app',['title' => 'Dashboard'])
@section('css')

@endsection


@section('content')
<div class="row">
    <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
        <div class="card-box noradius noborder bg-default">
            <i class="fa fa-file-text-o float-right text-white"></i>
            <h6 class="text-white text-uppercase m-b-20">Orders</h6>
            <h1 class="m-b-20 text-white counter">1,587</h1>
            <span class="text-white">15 New Orders</span>
        </div>
    </div>

    <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
        <div class="card-box noradius noborder bg-warning">
            <i class="fa fa-bar-chart float-right text-white"></i>
            <h6 class="text-white text-uppercase m-b-20">Visitors</h6>
            <h1 class="m-b-20 text-white counter">250</h1>
            <span class="text-white">Bounce rate: 25%</span>
        </div>
    </div>

    <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
        <div class="card-box noradius noborder bg-info">
            <i class="fa fa-user-o float-right text-white"></i>
            <h6 class="text-white text-uppercase m-b-20">Users</h6>
            <h1 class="m-b-20 text-white counter">120</h1>
            <span class="text-white">25 New Users</span>
        </div>
    </div>

    <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
        <div class="card-box noradius noborder bg-danger">
            <i class="fa fa-bell-o float-right text-white"></i>
            <h6 class="text-white text-uppercase m-b-20">Alerts</h6>
            <h1 class="m-b-20 text-white counter">58</h1>
            <span class="text-white">5 New Alerts</span>
        </div>
    </div>
</div>

<div class="row">

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
        <div class="card mb-3">
            <div class="card-header">
                <h3><i class="fa fa-line-chart"></i> Items Sold Amount</h3>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus non luctus metus.
                Vivamus fermentum ultricies orci sit amet sollicitudin.
            </div>

            <div class="card-body">
                <canvas id="lineChart"></canvas>
            </div>
            <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
        </div><!-- end card-->
    </div>

    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-3">
        <div class="card mb-3">
            <div class="card-header">
                <h3><i class="fa fa-bar-chart-o"></i> Colour Analytics</h3>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus non luctus metus.
                Vivamus fermentum ultricies orci sit amet sollicitudin.
            </div>

            <div class="card-body">
                <canvas id="pieChart"></canvas>
            </div>
            <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
        </div><!-- end card-->
    </div>

    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-3">
        <div class="card mb-3">
            <div class="card-header">
                <h3><i class="fa fa-bar-chart-o"></i> Colour Analytics 2</h3>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus non luctus metus.
                Vivamus fermentum ultricies orci sit amet sollicitudin.
            </div>

            <div class="card-body">
                <canvas id="doughnutChart"></canvas>
            </div>
            <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
        </div><!-- end card-->
    </div>

</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        // data-tables
        $('#example1').DataTable();

        // counter-up
        $('.counter').counterUp({
            delay: 10,
            time: 600
        });
    });
</script>

<script>
    var ctx1 = document.getElementById("lineChart").getContext('2d');
    var lineChart = new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets: [{
                label: 'Dataset 1',
                backgroundColor: '#3EB9DC',
                data: [10, 14, 6, 7, 13, 9, 13, 16, 11, 8, 12, 9]
            }, {
                label: 'Dataset 2',
                backgroundColor: '#EBEFF3',
                data: [12, 14, 6, 7, 13, 6, 13, 16, 10, 8, 11, 12]
            }]

        },
        options: {
            tooltips: {
                mode: 'index',
                intersect: false
            },
            responsive: true,
            scales: {
                xAxes: [{
                    stacked: true,
                }],
                yAxes: [{
                    stacked: true
                }]
            }
        }
    });


    var ctx2 = document.getElementById("pieChart").getContext('2d');
    var pieChart = new Chart(ctx2, {
        type: 'pie',
        data: {
            datasets: [{
                data: [12, 19, 3, 5, 2, 3],
                backgroundColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                label: 'Dataset 1'
            }],
            labels: [
                "Red",
                "Orange",
                "Yellow",
                "Green",
                "Blue"
            ]
        },
        options: {
            responsive: true
        }

    });


    var ctx3 = document.getElementById("doughnutChart").getContext('2d');
    var doughnutChart = new Chart(ctx3, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [12, 19, 3, 5, 2, 3],
                backgroundColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                label: 'Dataset 1'
            }],
            labels: [
                "Red",
                "Orange",
                "Yellow",
                "Green",
                "Blue"
            ]
        },
        options: {
            responsive: true
        }

    });
</script>
@endsection