<x-app-layout>
    <x-slot name="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('app.dashboard') }}">DASHBOARD</a></li>
            <li class="breadcrumb-item active">LICENSE TRAINING & MEDICAL MONITORING</li>
        </ul>
    </x-slot>
    <x-slot name="css">
        <link href="{{asset('assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
        <link href="{{ asset('assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}"
            rel="stylesheet" />
        <link href="{{ asset('assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}"
            rel="stylesheet" />
        <link href="{{ asset('assets/plugins/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css') }}"
            rel="stylesheet" />
    </x-slot>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <!-- Validation -->
    <x-errors class="mb-4" />
    <x-success class="mb-4" />
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3 class="card-title">License Training & Medical Monitoring List</h3>
            <!--@if (auth()->user()->can('Staff Add'))
-->
            <!--<a href="{{ route('app.pilot.create') }}" class="btn btn-primary btn-sm p-2">Add Pilot</a>-->
            <!--
@endif-->
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 bg-light mb-3">
                    <p class="m-2"><b>License</b></p>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="form-group">
                        <label for="dates" class="form-label">Date<span class="text-danger">*</span></label>
                        <input type="text" class="form-control datepicker" id="dates" name="dates" placeholder="Please Enter Date">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="table-responsive">
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

                <div class="col-md-12 bg-light mt-3 mb-3">
                    <p class="m-2"><b>Training</b></p>
                </div>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table id="datatableTraining" class="table text-nowrap w-100">
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

                <div class="col-md-12 bg-light mt-3 mb-3">
                    <p class="m-2"><b>Medical</b></p>
                </div>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table id="datatableMedical" class="table text-nowrap w-100">
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

                <div class="col-md-12 bg-light mt-3 mb-3">
                    <p class="m-2"><b>Qualification</b></p>
                </div>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table id="datatableQualification" class="table text-nowrap w-100">
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
                
                <div class="col-md-12 bg-light mt-3 mb-3">
                    <p class="m-2"><b>Ground Training</b></p>
                </div>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table id="datatableGroundTraining" class="table text-nowrap w-100">
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
        </div>

    </div>
    </div>




    <x-slot name="js">
        <script src="{{asset('assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
        <script src="{{ asset('assets/plugins/datatables.net/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables.net-buttons/js/buttons.flash.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
        <script>
            $('.datepicker').datepicker({
                autoclose: true,
                format: 'dd-mm-yyyy',
                calendarWeeks: false,
                zIndexOffset: 9999,
                orientation: "bottom",
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
                        url: "{{ route('app.ltm.monitoringLicenseList') }}",
                        type: 'POST',
                        data: {
                            "_token": "{{ csrf_token() }}",dates:$('.datepicker').val()
                        },
                    },
                    "initComplete": function() {

                    }
                });
            }
            licenseDataList();

            function trainingdataList() {
                $('#datatableTraining').DataTable().destroy();
                $('#datatableTraining').DataTable({
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
                        url: "{{ route('app.ltm.monitoringTrainingList') }}",
                        type: 'POST',
                        data: {
                            "_token": "{{ csrf_token() }}",dates:$('.datepicker').val()
                        },
                    },
                    "initComplete": function() {

                    }
                });
            }
            trainingdataList();

            function medicalDataList() {
                $('#datatableMedical').DataTable().destroy();
                $('#datatableMedical').DataTable({
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
                        url: "{{ route('app.ltm.monitoringMedicalList') }}",
                        type: 'POST',
                        data: {
                            "_token": "{{ csrf_token() }}",dates:$('.datepicker').val()
                        },
                    },
                    "initComplete": function() {

                    }
                });
            }
            medicalDataList();

            function qualificationdataList() {
                $('#datatableQualification').DataTable().destroy();
                $('#datatableQualification').DataTable({
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
                        url: "{{ route('app.ltm.monitoringQualificationList') }}",
                        type: 'POST',
                        data: {
                            "_token": "{{ csrf_token() }}",dates:$('.datepicker').val()
                        },
                    },
                    "initComplete": function() {

                    }
                });
            }
            qualificationdataList();

            function groundtrainingdataList() {
                $('#datatableGroundTraining').DataTable().destroy();
                $('#datatableGroundTraining').DataTable({
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
                        url: "{{ route('app.ltm.monitoringGroundTrainingList') }}",
                        type: 'POST',
                        data: {
                            "_token": "{{ csrf_token() }}",dates:$('.datepicker').val()
                        },
                    },
                    "initComplete": function() {

                    }
                });
            }
            groundtrainingdataList();

            function changeStatus(id, status) {
                $.ajax({
                    url: "{{ route('app.pilot.status') }}",
                    type: 'post',
                    data: {
                        id,
                        status,
                        '_token': '{{ csrf_token() }}'
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
            
            $('.datepicker').on('change',function(){
                licenseDataList();
                trainingdataList();
                medicalDataList();
                qualificationdataList();
                groundtrainingdataList();
            });
            
        </script>
    </x-slot>
</x-app-layout>
