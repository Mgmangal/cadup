<x-app-layout>
    <x-slot name="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('app.dashboard')}}">DASHBOARD</a></li>
            <li class="breadcrumb-item">REPORT </li>
            <li class="breadcrumb-item active">VIOLATIONS </li>
        </ul>
    </x-slot>
    <x-slot name="css">
        <link href="{{asset('assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css')}}"
            rel="stylesheet" />
        <link href="{{asset('assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css')}}"
            rel="stylesheet" />
        <link href="{{asset('assets/plugins/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css')}}"
            rel="stylesheet" />
    </x-slot>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <!-- Validation -->
    <x-errors class="mb-4" />
    <x-success class="mb-4" />
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3 class="card-title">Violation Report</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="datatableDefault" class="table text-nowrap w-100">
                    <thead>
                        <tr>
                            <th>S. No</th>
                            <th>FDT Start</th>
                            <th>FDT End</th>
                            <th>Date</th>
                            <th>Pilot</th>
                            <th>Message</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="manageViolation" tabindex="-1" aria-labelledby="manageViolationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="manageViolationModalLabel">Manage Violation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{route('app.fdtl.updateException')}}" id="updateViolationDetails" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="id">
                    <input type="hidden" name="is_exception" id="is_exception">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Violation</label>
                                    <select name="violations" id="violations" class="form-control">
                                        <option value="">Select</option>
                                        <option value="12 hrs">12 hrs</option>
                                        <option value="not more than 3 times">not more than 3 times</option>
                                        <option value="within last consecutive 28 days.">within last consecutive 28 days.</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Remark</label>
                                    <textarea name="remark" id="remark" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
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
        <script src="{{asset('assets/plugins/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js')}}">
        </script>
        <script>
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
                    url: "{{route('app.fdtl.voilations.report.list')}}",
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                },
                fnRowCallback: function(nRow, aData, iDisplayIndex) {
                    var oSettings = this.fnSettings();
                    $("td:eq(0)", nRow).html(oSettings._iDisplayStart + iDisplayIndex + 1);
                },
                "initComplete": function() {

                }
            });
        }
        dataList();

        function updateException(id, is_exception) {
            $('#manageViolation').modal('show');
            $('#id').val(id);
            $('#is_exception').val(is_exception);
        }

        $('#updateViolationDetails').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                dataType: 'json',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        success(response.message);
                        $('#manageViolation').modal('hide');
                        dataList();
                    }
                }
            });
        })

        function reUpdateException(id) {
            $.ajax({
                url: "{{route('app.fdtl.violation-update')}}",
                method: 'POST',
                dataType: 'json',
                data: {
                    "_token": "{{ csrf_token() }}",
                    id
                },
                success: function(response) {
                    if (response.success) {
                        $('#remark').html(response.data.remark);
                        $('#id').val(response.data.id);
                        $('#is_exception').val(response.data.is_exception);
                        $('#violations').val(response.data.violations);
                        $('#manageViolation').modal('show');
                    }
                }
            });
        }
        </script>
    </x-slot>
</x-app-layout>