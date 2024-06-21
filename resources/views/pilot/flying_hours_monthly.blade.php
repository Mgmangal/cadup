<x-app-layout>
    <x-slot name="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('app.dashboard')}}">DASHBOARD</a></li>
            <li class="breadcrumb-item active">PILOT FLYING HOURS</li>
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
            <h3 class="card-title">Pilot Flying Hours</h3>
            <!--<a href="{{route('app.flying-details.create')}}" class="btn btn-primary btn-sm p-2">Add New</a>-->
        </div>
        <div class="card-body">
            <div class="row mb-3 justify-content-evenly">
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="from_date" class="form-label">From Date</label>
                        <input type="text" class="form-control filters dates" id="from_date" placeholder="DD-MM-YYYY" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="to_date" class="form-label">To Date</label> {{ date('m Y',strtotime(date('2021-12-30'))) }}  {{ date('m Y',strtotime(date('Y-m-d'))) }}
                        <input type="text" class="form-control filters dates" id="to_date" placeholder="DD-MM-YYYY" autocomplete="off">
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
            <h3 class="card-title">Pilot Aircraft-wise Flying Summary</h3>    
        </div>
        <div class="card-body">
            <div class="row mb-3 justify-content-evenly">
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="aircraft_from_date" class="form-label">From Date</label>
                        <input type="text" class="form-control dates" id="aircraft_from_date" placeholder="DD-MM-YYYY" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="aircraft_to_date" class="form-label">To Date</label> {{ date('m Y',strtotime(date('2021-12-30'))) }}  {{ date('m Y',strtotime(date('Y-m-d'))) }}
                        <input type="text" class="form-control dates" id="aircraft_to_date" placeholder="DD-MM-YYYY" autocomplete="off">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <button class="btn btn-sm btn-info mt-4 py-2 px-4" onclick="printAircraftWiseSummary();">Print</button>
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
                let from_date = $('#from_date').val();
                let to_date = $('#to_date').val();
                if(!from_date){
                    info('Please Select From Date');
                }else if(!to_date){
                    info('Please Select To Date');
                } else {
                    window.open("{{url('admin/pilot/flying-hours/print')}}"+'/'+from_date+'/'+to_date,'printDiv','height=900,width=900');
                }
            }

            function printAircraftWiseSummary()
            {
                let from_date = $('#aircraft_from_date').val();
                let to_date = $('#aircraft_to_date').val();
                if(!from_date){
                    info('Please Select From Date');
                } else if(!to_date){
                    info('Please Select To Date');
                } else {
                    window.open("{{route('app.reports.printAircraftWiseSummary')}}"+'/'+from_date+'/'+to_date,'printDiv','height=900,width=900');
                }

            }
        </script>
    </x-slot>
</x-app-layout>