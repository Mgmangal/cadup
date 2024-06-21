<x-app-layout>
    <x-slot name="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('app.dashboard')}}">DASHBOARD</a></li>
            <li class="breadcrumb-item active">EXTERNAL FLYING-DETAILS</li>
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
            <h3 class="card-title">External Flying</h3>
            <!--<a href="{{route('app.flying-details.create')}}" class="btn btn-primary btn-sm p-2">Add New</a>-->
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="from_date" class="form-label">From Date</label>
                        <input type="text" class="form-control filters dates" id="from_date" placeholder="DD-MM-YYYY" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="to_date" class="form-label">To Date</label>
                        <input type="text" class="form-control filters dates" id="to_date" placeholder="DD-MM-YYYY" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="aircraft_cateogry" class="form-label">Aircraft Type</label>
                        <select name="aircraft_cateogry" id="aircraft_cateogry" class="form-control filters">
                            <option value="">Select</option>
                            <option value="Fixed Wing">Fixed Wing</option>
                            <option value="Rotor Wing">Rotor Wing</option>
                        </select>
                    </div>
                </div>
                <!--<div class="col-md-3">-->
                <!--    <div class="form-group">-->
                <!--        <label for="pilot" class="form-label">Pilots</label>-->
                <!--        <select class="form-control filters" id="pilot">-->
                <!--            <option value="">Select</option>-->
                <!--            @foreach($pilots as $pilot)-->
                <!--            <option value="{{$pilot->id}}">{{$pilot->salutation}} {{$pilot->name}}</option>-->
                <!--            @endforeach-->
                <!--        </select>-->
                <!--    </div>-->
                <!--</div>-->
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="flying_type" class="form-label">Flying Type</label>
                        <select class="form-control filters" id="flying_type">
                            <option value="">Select</option>
                            @foreach($flying_types as $key=> $flying_type)
                            <option value="{{$key}}">{{$flying_type}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <button class="btn btn-sm btn-info mt-4" onclick="printReport();">Print</button>
                    </div>
                </div>    
            </div>
            <div class="table-responsive">
                <table id="datatableDefault" class="table text-nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Aircraft</th>
                            <th>From/To</th>
                            <th>Chocks-Off /<br>Chocks-On</th>
                            <th>Block Time<br> (Hrs:Mins)</th>
                            <th>Crew</th>
                            <th>Flying Type</th>
                            <!--<th>Action</th>-->
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
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
            function dataList() {
                $('#datatableDefault').DataTable().destroy();
                $('#datatableDefault').DataTable({
                    processing: true,
                    serverSide: true,
                    dom: "<'row mb-3'<'col-sm-4'l><'col-sm-8 text-end'<'d-flex justify-content-end'fB>>>t<'d-flex align-items-center'<'me-auto'i><'mb-0'p>>",
                    lengthMenu: [20, 50, 100, 200, 500, 1000, 2000, 5000, 10000],
                    responsive: true,
                    
                    order: [[2, 'desc']],
                    // columnDefs: [{
                    //     width: 200,
                    //     targets: 3
                    // }],
                    fixedColumns: true,
                    buttons: [
                        // {
                        //     extend: 'print',
                        //     className: 'btn btn-default btn-sm',
                        //     exportOptions: {
                        //         columns: [0, 1, 2,3,4, 5,6,7]
                        //     }
                        // },
                        {
                            extend: 'csv',
                            className: 'btn btn-default btn-sm',
                            exportOptions: {
                                columns: [0, 1, 2,3,4, 5,6,7]
                            }
                        }
                    ],
                    ajax: {
                        url: "{{route('app.external.flying-details.list')}}",
                        type: 'POST',
                        data: {
                            "_token": "{{ csrf_token() }}",from_date:$('#from_date').val(),to_date:$('#to_date').val(),aircraft:$('#aircraft').val(),pilot:$('#pilot').val(),flying_type:$('#flying_type').val()
                        },
                    },
                    fnRowCallback: function( nRow, aData, iDisplayIndex ) { 
                        var oSettings = this.fnSettings ();
                        $("td:eq(0)", nRow).html(oSettings._iDisplayStart+iDisplayIndex +1);
                        $('td:eq(5)', nRow).css('text-align','center');
                        $('td:eq(8)', nRow).css('display','none');
                    },
                    "initComplete": function() {
                            
                    }
                });
            }
            dataList();
            
            $('.filters').on('change',function(){
                dataList();
            });
            
            function printReport()
            {   
                let from_date = $('#from_date').val();
                let to_date = $('#to_date').val();
                let aircraft = $('#aircraft_cateogry').val();
                let flying_type = $('#flying_type').val();
                window.open("{{url('admin/external-flying-details/statistics/print')}}"+'/'+from_date+'/'+to_date+'/'+aircraft+'/'+flying_type,'printDiv','height=900,width=900');
            }
        </script>
    </x-slot>
</x-app-layout>