<x-app-layout>
    <x-slot name="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('app.dashboard')}}">DASHBOARD</a></li>
            <li class="breadcrumb-item ">PILOT </li>
            <li class="breadcrumb-item">LICENSE / CERTIFICATE </li>
            <li class="breadcrumb-item active">{{ $sub_title }} </li>
        </ul>
    </x-slot>
    <x-slot name="css">
        <link href="{{asset('assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}"
            rel="stylesheet">
        <link href="{{asset('assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css')}}"
            rel="stylesheet" />
        <link href="{{asset('assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css')}}"
            rel="stylesheet" />
        <link href="{{asset('assets/plugins/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css')}}"
            rel="stylesheet" />
        <style>
        .datepicker.datepicker-dropdown {
            z-index: 9999 !important
        }
        </style>
    </x-slot>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <!-- Validation -->
    <x-errors class="mb-4" />
    <x-success class="mb-4" />

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3 class="card-title">{{ $sub_title }} List</h3>
            <a href="javascript:history.go(-1)" class="btn btn-primary btn-sm p-2">Back</a>
        </div>
        <div class="card-body">
    
            <div class="table-responsive=">
                <table id="datatableLicenses" class="table text-nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Crew Name</th>
                            <th>Info </th>
                            <th>Renewed On</th>
                            <th>Extended Date</th>
                            <th>Next Due</th>
                            <th>Remaining Days</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
    
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
   <x-slot name="js">
        <script src="{{asset('assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables.net/js/jquery.dataTables.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables.net-bs5/js/dataTables.bootstrap5.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables.net-buttons/js/buttons.colVis.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables.net-buttons/js/buttons.flash.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js')}}">
        </script>
        <script>
            $(".dates").datepicker({
                timepicker: false,
                format: 'd-m-Y',
                formatDate: 'Y/m/d',
                autoclose: true,
                clearBtn: true,
                todayButton: true,
                // maxDate: new Date(new Date().getTime() + 5 * 24 * 60 * 60 * 1000),
            });
            
            function licenseDataList() {
                $('#datatableLicenses').DataTable().destroy();
                $('#datatableLicenses').DataTable({
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
                    }, {
                        extend: 'csv',
                        className: 'btn btn-default btn-sm'
                    }],
                    ajax: {
                        url: "{{ route('app.pilot.getCertificateLogList') }}",
                        type: 'POST',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            type: "{{$type}}",
                            user_id: "{{$user_id}}",
                            id:"{{$id}}",
                        },
                    },
                    "initComplete": function() {
            
                    }
                });
            }
            licenseDataList();
            
            $('.filters').on('change', function() {
                licenseDataList();
            });
            
          
        </script>
    
    </x-slot>
</x-app-layout>