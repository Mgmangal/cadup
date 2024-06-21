<x-app-layout>
    <x-slot name="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('app.dashboard')}}">DASHBOARD</a></li>
            <li class="breadcrumb-item active">REPORT </li>
        </ul>
    </x-slot>
    <x-slot name="css">
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
            <h3 class="card-title">Report List</h3>
            <a href="{{route('app.adt.report.generate')}}" class="btn btn-primary btn-sm p-2">Generate Report</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="datatableDefault" class="table text-nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Generated On Date</th>
                            <th>For Date</th>
                            <th>Download Employees List</th>
                            <th>Download Test Report</th>
                            <th>Count</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel">Upload Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{route('app.adt.report.upload')}}" method="POST" enctype="multipart/form-data" id="uploadForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Upload File</label>
                                    <input type="hidden" name="id" id="id">
                                    <input type="file" name="report" class="form-control" value="">
                                </div>
                            </div>
                        </div>
                        <div class="row text-center mt-3">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-sm">Upload</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
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
        <script type="text/javascript">
            function uploadData(id) {

                $('#id').val(id);
                $('#uploadModal').modal('show');
            }

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
                        url: "{{route('app.adt.report.list')}}",
                        type: 'POST',
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                    },
                    "initComplete": function() {

                    }
                });
            }
            dataList();

            function printData(url) {
                var printWindow = window.open(url, '', 'width=600,height=600');
                printWindow.print();

                //Close window once print is finished
                printWindow.onafterprint = function() {
                    //   printWindow.close()
                };
            }

            $('#uploadForm').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                    $.ajax({
                        url: "{{route('app.adt.report.upload')}}",
                        type: 'POST',
                        dataType: 'JSON',
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function(data) {
                            if (data.success) {
                                success(data.message);
                                $('#uploadForm')[0].reset();
                                $('#id').val('');
                                $('#uploadModal').modal('hide');
                                dataList();
                            } else {
                                warning(data.message);
                            }
                            dataList();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                })
        </script>
    </x-slot>
</x-app-layout>