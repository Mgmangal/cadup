@extends('theme-one.layouts.app', ['title' => 'Reports', 'sub_title' => $sub_title])
@section('css')
<link href="{{ asset('assets/theme_one/lib/datatables.net-dt/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/theme_one/lib/datatables.net-responsive-dt/css/responsive.dataTables.min.css') }}"
    rel="stylesheet">
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">{{ $sub_title }}</h3>
        <!--<a href="#" class="btn btn-primary btn-sm p-2">Add New</a>-->
    </div>
    <div class="card-body">
        <div class="row mb-3 justify-content-evenly">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="testdate" class="form-label">Date</label>
                    <input type="text" class="form-control dates" id="testdate" placeholder="DD-MM-YYYY" autocomplete="off">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="aircraft_type" class="form-label">Aircraft Type</label>
                    <select name="aircraft_type" id="aircraft_type" class="form-control">
                        <option value="">Select</option>
                        <option value="Fixed Wing">Fixed Wing</option>
                        <option value="Rotor Wing">Rotor Wing</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <div class="form-group">
                    <button class="btn btn-sm btn-info px-4" onclick="printVipRecency();">Print</button>
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
    function printVipRecency()
    {
        let date = $('#testdate').val();
        let aircraft_type = $('#aircraft_type').val();
        if (!date) {
            info('Please Select Date');
        }else if(!aircraft_type){
                info('Please Select Aircraft Type');
        } else {
            window.open("{{ route('user.reports.vipRecencyPrint') }}" + '/' + date+'/'+aircraft_type, 'printDiv', 'height=900,width=900');
        }
    }
</script>
@endsection
