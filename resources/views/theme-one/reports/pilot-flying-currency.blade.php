@extends('theme-one.layouts.app',['title' => 'Dashboard','sub_title'=>'Curency'])
@section('css')

@endsection

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Pilot Flying Currency</h3>
    </div>
    <div class="card-body">
        <div class="row mb-3 justify-content-evenly">
            <div class="col-md-2">
                <div class="form-group">
                    <label for="date" class="form-label">Date</label>
                    <input type="text" class="form-control dates" id="date" placeholder="DD-MM-YYYY" autocomplete="off">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="aircraft_cateogry" class="form-label">Aircraft Type</label>
                    <select name="aircraft_cateogry" id="aircraft_cateogry" class="form-control">
                        <option value="">Select</option>
                        <option value="Fixed Wing">Fixed Wing</option>
                        <option value="Rotor Wing">Rotor Wing</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="report_type" class="form-label">Report Type</label>
                    <select name="report_type" id="report_type" class="form-control">
                        <option value="">Select</option>
                        <option value="1">IR, PPC, Route Check & Medical Details</option>
                        <option value="2">FRTOL, RTR & English Proficiency Details</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <button class="btn btn-sm btn-info mt-4 py-2 px-4" onclick="printReport();">Print</button>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="card mt-3">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">The Training and Checks of Cadup Pilots Due Between 60 Days Are as Under</h3>
    </div>
    <div class="card-body">
        <div class="row mb-3 justify-content-evenly">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="check_date" class="form-label">Date</label>
                    <input type="text" class="form-control dates" id="check_date" placeholder="DD-MM-YYYY"
                        autocomplete="off">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="check_aircraft_cateogry" class="form-label">Aircraft Type</label>
                    <select name="check_aircraft_cateogry" id="check_aircraft_cateogry" class="form-control">
                        <option value="">Select</option>
                        <option value="Fixed Wing">Fixed Wing</option>
                        <option value="Rotor Wing">Rotor Wing</option>
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <button class="btn btn-sm btn-info mt-4 py-2 px-4" onclick="printCheckReport();">Print</button>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="card mt-3">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Details of Flying Test Done</h3>
    </div>
    <div class="card-body">
        <div class="row mb-3 justify-content-evenly">
            <div class="col-md-7">
                <div class="form-group">
                    <label for="testdate" class="form-label">Date</label>
                    <input type="text" class="form-control dates" id="testdate" placeholder="DD-MM-YYYY"
                        autocomplete="off">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <button class="btn btn-sm btn-info mt-4 py-2 px-4" onclick="printTestReport();">Print</button>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@section('js')
<script>
$(".dates").datepicker({
    timepicker: false,
    format: 'd-m-Y',
    dateFormat: 'dd-mm-yy',
    autoclose: true,
    clearBtn: true,
    todayButton: true,
});

function printReport() {
    let date = $('#date').val();
    let aircraft = $('#aircraft_cateogry').val();
    let report_type = $('#report_type').val();
    if (!date) {
        info('Please Select Date');
    } else if (!aircraft) {
        info('Please Select Aircraft Type');
    } else if (!report_type) {
        info('Please Select Report Type');
    } else {
        window.open("{{route('user.reports.pilotFlyingCurrencyPrint')}}" + '/' + date + '/' + aircraft + '/' +
            report_type, 'printDiv', 'height=900,width=900');
    }
}

function printCheckReport() {
    let date = $('#check_date').val();
    let aircraft = $('#check_aircraft_cateogry').val();
    if (!date) {
        info('Please Select Date');
    } else if (!aircraft) {
        info('Please Select Aircraft Type');
    } else {
        window.open("{{route('user.reports.trainingChecksPrint')}}" + '/' + date + '/' + aircraft, 'printDiv',
            'height=900,width=900');
    }
}

function printTestReport() {
    let date = $('#testdate').val();
    if (!date) {
        info('Please Select Date');
    } else {
        window.open("{{ route('user.reports.FlyingTestDetailsPrint') }}" + '/' + date, 'printDiv',
            'height=900,width=900');
    }
}
</script>
@endsection