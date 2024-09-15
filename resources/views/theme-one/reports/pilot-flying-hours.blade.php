@extends('theme-one.layouts.app', ['title' => 'Reports', 'sub_title' => $sub_title])
@section('css')
<link href="{{ asset('assets/theme_one/lib/datatables.net-dt/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/theme_one/lib/datatables.net-responsive-dt/css/responsive.dataTables.min.css') }}"
    rel="stylesheet">
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Pilot Flying Hours</h3>
        <!--<a href="#" class="btn btn-primary btn-sm p-2">Add New</a>-->
    </div>
    <div class="card-body">
        <div class="row mb-3 justify-content-evenly">
            <div class="col-md-2">
                <div class="form-group">
                    <label for="from_date" class="form-label">From Date</label>
                    <input type="text" class="form-control filters dates" id="from_date" placeholder="DD-MM-YYYY"
                        autocomplete="off">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="to_date" class="form-label">To Date</label>
                    <input type="text" class="form-control filters dates" id="to_date" placeholder="DD-MM-YYYY"
                        autocomplete="off">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <div class="form-group">
                    <button class="btn btn-sm btn-info px-4 text-white" onclick="printReport();">Print</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Pilot Aircraft-wise Flying Summary</h3>
    </div>
    <div class="card-body">
        <div class="row mb-3 justify-content-evenly">
            <div class="col-md-2">
                <div class="form-group">
                    <label for="aircraft_from_date" class="form-label">From Date</label>
                    <input type="text" class="form-control dates" id="aircraft_from_date" placeholder="DD-MM-YYYY"
                        autocomplete="off">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="aircraft_to_date" class="form-label">To Date</label>
                    <input type="text" class="form-control dates" id="aircraft_to_date" placeholder="DD-MM-YYYY"
                        autocomplete="off">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <div class="form-group">
                    <button class="btn btn-sm btn-info px-4 text-white" onclick="printAircraftWiseSummary();">Print</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('assets/theme_one/lib/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/theme_one/lib/datatables.net-dt/js/dataTables.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/theme_one/lib/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/theme_one/lib/datatables.net-responsive-dt/js/responsive.dataTables.min.js') }}"></script>

<script>
    $(".dates").datepicker({
        timepicker: false,
        // format: 'dd-mm-yyyy',  // Update this line
        dateFormat: 'dd-mm-yy',  // Update this line
        autoclose: true,
        clearBtn: true,
        todayButton: true,
        // maxDate: new Date(new Date().getTime() + 5 * 24 * 60 * 60 * 1000),
    });
    function printReport()
    {
        let from_date = $('#from_date').val();
        let to_date = $('#to_date').val();
        if(!from_date){
            warning('Please Select From Date');
        }else if(!to_date){
            warning('Please Select To Date');
        } else {
            window.open("{{url('user/reports/pilot-flying-hours-print')}}"+'/'+from_date+'/'+to_date,'printDiv','height=900,width=900');
        }
    }

    function printAircraftWiseSummary()
    {
        let from_date = $('#aircraft_from_date').val();
        let to_date = $('#aircraft_to_date').val();
        if(!from_date){
            warning('Please Select From Date');
        } else if(!to_date){
            warning('Please Select To Date');
        } else {
            window.open("{{route('user.reports.aircraftWiseSummaryPrint')}}"+'/'+from_date+'/'+to_date,'printDiv','height=900,width=900');
        }

    }
</script>
@endsection
