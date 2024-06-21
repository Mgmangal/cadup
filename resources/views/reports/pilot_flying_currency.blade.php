<x-app-layout>
    <x-slot name="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('app.dashboard')}}">DASHBOARD</a></li>
            <li class="breadcrumb-item active">PILOT FLYING CURRENCY</li>
        </ul>
    </x-slot>
    <x-slot name="css">
         <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css" />
        <link href="{{asset('assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" />
        <link href="{{asset('assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css')}}" rel="stylesheet" />
        <link href="{{asset('assets/plugins/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css')}}" rel="stylesheet" />
    </x-slot>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <!-- Validation -->
    <x-errors class="mb-4" />
    <x-success class="mb-4" />
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
                        <input type="text" class="form-control dates" id="check_date" placeholder="DD-MM-YYYY" autocomplete="off">
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
                        <input type="text" class="form-control dates" id="testdate" placeholder="DD-MM-YYYY" autocomplete="off">
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


    <x-slot name="js">
         <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script>
        <script src="{{asset('assets/plugins/datatables.net/js/jquery.dataTables.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables.net-bs5/js/dataTables.bootstrap5.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables.net-buttons/js/buttons.colVis.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables.net-buttons/js/buttons.flash.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js')}}"></script>
        <script>
         $(".dates").datetimepicker({
                timepicker: false,
                format: 'd-m-Y',
                formatDate:'Y/m/d',
                autoclose: true,
                clearBtn: true,
                todayButton: true,               
                // maxDate: new Date(new Date().getTime() + 5 * 24 * 60 * 60 * 1000),
                onSelectDate: function(ct) {
                }
            });
            
            function printReport()
            {
                let date = $('#date').val();
                let aircraft = $('#aircraft_cateogry').val();
                let report_type = $('#report_type').val();
                if (!date) {
                    info('Please Select Date');
                } else if (!aircraft) {
                    info('Please Select Aircraft Type');
                } else if (!report_type) {
                    info('Please Select Report Type');
                }else {
                    window.open("{{route('app.reports.pilotFlyingCurrencyPrint')}}"+'/'+date+'/'+aircraft+'/'+report_type,'printDiv','height=900,width=900');
                }
            }

            function printCheckReport()
            {
                let date = $('#check_date').val();
                let aircraft = $('#check_aircraft_cateogry').val();
                if (!date) {
                    info('Please Select Date');
                } else if (!aircraft) {
                    info('Please Select Aircraft Type');
                } else {
                    window.open("{{route('app.reports.trainingChecksPrint')}}"+'/'+date+'/'+aircraft,'printDiv','height=900,width=900');
                }
            }

            function printTestReport()
            {
                let date = $('#testdate').val();
                if (!date) {
                    info('Please Select Date');
                } else {
                    window.open("{{ route('app.reports.FlyingTestDetailsPrint') }}" + '/' + date, 'printDiv', 'height=900,width=900');
                }
            }
        </script>
    </x-slot>
</x-app-layout>