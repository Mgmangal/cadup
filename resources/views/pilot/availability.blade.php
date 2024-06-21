<x-app-layout>
    <x-slot name="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('app.dashboard')}}">DASHBOARD</a></li>
            <li class="breadcrumb-item active">CREW AVAILABILITY</li>
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
            <h3 class="card-title">Crew Availability List</h3>
            <!-- <a href="{{route('app.pilot.create')}}" class="btn btn-primary btn-sm p-2">Add Pilot</a> -->
        </div>
        <div class="card-body">
            <div class="row mb-3 ml-1">
                <div class="col-md-1 text-end">
                    <label for="date" class="form-label">Date</label>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="input-group">
                            <label class="input-group-text" for="date">
                                <i class="fa fa-calendar"></i>
                            </label>
                            <input type="text" class="form-control dates is_valid" id="date" name="date" placeholder="Please Enter Date" autocomplete="off" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table id="datatableDefault" class="table text-nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Balance FDTL</th>
                            <th>VIP Currency</th>
                            <th>Training Status</th>
                            <th>License Status</th>
                            <th>Medical Status</th>
                            <th>Leave Status</th>
                            <th>Crew Status</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <x-slot name="js">
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script>

        <script>
            $(".dates").datetimepicker({
                timepicker: false,
                format: 'd-m-Y',
                formatDate: 'Y/m/d',
                autoclose: true,
                clearBtn: true,
                todayButton: true,
                maxDate: new Date(new Date().getTime() + 5 * 24 * 60 * 60 * 1000),
                onSelectDate: function(ct) {
                    dataList();
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
                    columnDefs: [{
                        width: 200,
                        targets: 3
                    }],
                    fixedColumns: true,
                    buttons: [{
                            extend: 'print',
                            className: 'btn btn-default btn-sm'
                        },
                        {
                            extend: 'csv',
                            className: 'btn btn-default btn-sm'
                        }
                    ],
                    ajax: {
                        url: "{{route('app.pilot.availability.list')}}",
                        type: 'POST',
                        data: {
                            "_token": "{{ csrf_token() }}",from_date:$('#date').val()
                        },
                    },
                     fnRowCallback: function( nRow, aData, iDisplayIndex ) { 
                        var oSettings = this.fnSettings ();
                        $("td:eq(0)", nRow).html(oSettings._iDisplayStart+iDisplayIndex +1);
                    },
                    "initComplete": function() {

                    }
                });
            }
            dataList();

            function changeStatus(id, status) {
                $.ajax({
                    url: "{{route('app.pilot.status')}}",
                    type: 'post',
                    data: {
                        id,
                        status,
                        '_token': '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.success) {
                            swal("Success!", data.message, "success");
                        } else {
                            swal("Error!", data.message, "error");
                        }
                        dataList();
                    }
                });
            }
        </script>
    </x-slot>
</x-app-layout>