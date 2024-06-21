<x-app-layout>
    <x-slot name="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('app.dashboard')}}">DASHBOARD</a></li>
            <li class="breadcrumb-item active">CVR</li>
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
            <h3 class="card-title">CVR List </h3>
            <div>
                <a href="javascript:void(0);" class="btn btn-primary btn-sm p-2" onclick="addNew();">Add New</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="datatableDefault" class="table text-nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Aircraft</th>
                            <th>Receive Date</th>
                            <th>Reach Out Date</th>
                            <th>Analyzed Date</th>
                            <th>CFS Verified Status</th>
                            <th>Created On</th>
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

    <div class="modal fade" id="manageModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Manage Job Function</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{route('app.cvr.store')}}" method="POST" id="manageForm" class="">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label" for="aircraft_id">Aircraft </label>
                            <input type="hidden" name="edit_id" id="edit_id" />
                            <select name="aircraft_id" id="aircraft_id" class="form-control">
                                <option value="">Select Aircraft </option>
                                @foreach($aircrafts as $aircraft)
                                <option value="{{$aircraft->id}}">{{$aircraft->call_sign}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="receive_date">Receive Date</label>
                            <input type="text" class="form-control dates" name="receive_date" id="receive_date" placeholder="DD-MM-YYYY" autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="read_out_date">Reach Out Date</label>
                            <input type="text" class="form-control dates" name="read_out_date" id="read_out_date" placeholder="DD-MM-YYYY" autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="analyzed_date">Analyzed Date</label>
                            <input type="text" class="form-control dates" name="analyzed_date" id="analyzed_date" placeholder="DD-MM-YYYY" autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="cfs_verified_status">CFS Verified Status</label>
                            <select name="cfs_verified_status" id="cfs_verified_status" class="form-control">
                                <option value="">Select</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="">Select</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
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
                        url: "{{route('app.cvr.list')}}",
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


            function addNew() {
                var form = $('#manageForm');
                clearError(form)
                form[0].reset();
                $('#edit_id').val('');
                $('#modalLabel').text('Add CVR');
                $('#manageModal').modal('show')
            }

            $('#manageForm').submit(function(e) {
                e.preventDefault();
                var data = new FormData(this);
                var form = $(this);
                clearError(form)
                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            success(response.message);
                            form[0].reset();
                            $('#manageModal').modal('hide');
                            dataList();
                        } else {
                            $.each(response.message, function(fieldName, field) {
                                form.find('[name=' + fieldName + ']').addClass('is-invalid');
                                form.find('[name=' + fieldName + ']').after('<div class="invalid-feedback">' + field + '</div>');
                            })
                        }

                    }
                })
            })

            function editRole(url) {
                $.ajax({
                    url: url,
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#manageForm').find('[name=aircraft_id]').val(response.data.aircraft_id);
                            $('#manageForm').find('[name=receive_date]').val(response.data.receive_date);
                            $('#manageForm').find('[name=read_out_date]').val(response.data.read_out_date);
                            $('#manageForm').find('[name=analyzed_date]').val(response.data.analyzed_date);
                            $('#manageForm').find('[name=cfs_verified_status]').val(response.data.cfs_verified_status);
                            $('#manageForm').find('[name=status]').val(response.data.status);
                            $('#manageForm').find('[name=edit_id]').val(response.data.id);
                            $('#modalLabel').text('Edit CVR');
                            $('#manageModal').modal('show');
                        }
                    }
                });
            }

        </script>
    </x-slot>
</x-app-layout>
