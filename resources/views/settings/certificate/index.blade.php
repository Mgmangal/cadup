<x-app-layout>
    <x-slot name="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('app.dashboard')}}">DASHBOARD</a></li>
            <li class="breadcrumb-item active">Certificates</li>
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
            <h3 class="card-title">Certificates List </h3>
            <div>
                @can('Certificate Add')
                <a href="javascript:void(0);" class="btn btn-primary btn-sm p-2" onclick="addNew();">Add New</a>
                @endcan
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="datatableDefault" class="table text-nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Short Name</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Valid</th>
                            <th>Created On</th>
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
                    <h5 class="modal-title" id="modalLabel">Manage Certificates</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{route('app.settings.certificates.store')}}" method="POST" id="roleForm" class="">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label" for="name">Certificates Name</label>
                            <input type="hidden" name="edit_id" id="edit_id" />
                            <input type="text" class="form-control" name="name" placeholder="Enter Certificates Name" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="name">Certificates Short Name</label>
                            <input type="text" class="form-control" name="short_name" placeholder="Enter Certificates Short Name" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="sub_type">Type</label>
                            <select name="sub_type" id="sub_type" class="form-control">
                                <option value="license">License</option>
                                <option value="training">Training</option>
                                <option value="medical">Medical</option>
                                <option value="qualification">Qualification</option>
                                <option value="ground_training">Ground Training</option>
                            </select>
                        </div>
                        <div class="mb-3 form-check">
                            <label class="form-label" for="is_valid">Is Valid Life Time</label>
                            <input type="checkbox" class="form-check-input" name="is_valid" id="is_valid" value="lifetime"/>
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
        <script src="{{asset('assets/plugins/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js')}}"></script>
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
                        url: "{{route('app.settings.certificates.list')}}",
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
                $('#roleForm').find('.is-invalid').removeClass('is-invalid');
                $('#roleForm').find('.invalid-feedback').hide();
                $('#edit_id').val('');
                $('#roleForm')[0].reset();
                $('#manageModal').modal('show')
            }
            $('#roleForm').submit(function(e) {
                e.preventDefault();
                $('#roleForm').find('.invalid-feedback').hide();
                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    dataType: 'json',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#roleForm')[0].reset();
                            $('#manageModal').modal('hide');
                            dataList();
                        } else {
                            $.each(response.message, function(fieldName, field) {
                                $('#roleForm').find('[name=' + fieldName + ']').addClass('is-invalid');
                                $('#roleForm').find('[name=' + fieldName + ']').after('<div class="invalid-feedback">' + field + '</div>');
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
                            $('#roleForm').find('[name=sub_type]').val(response.data.sub_type);
                            $('#roleForm').find('[name=name]').val(response.data.name);
                            $('#roleForm').find('[name=short_name]').val(response.data.other_data);
                            $('#roleForm').find('[name=edit_id]').val(response.data.id);
                            $('#roleForm').find('[name=is_valid]').attr('checked',(response.data.more_data=='lifetime'?true:false));
                            $('#manageModal').modal('show');
                        }
                    }
                });
            }
        </script>
    </x-slot>
</x-app-layout>