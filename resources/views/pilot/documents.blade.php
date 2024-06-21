<x-app-layout>
    <x-slot name="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('app.dashboard') }}">DASHBOARD</a></li>
            <li class="breadcrumb-item active">PILOT</li>
        </ul>
    </x-slot>
    <x-slot name="css">
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
            <h3 class="card-title">Pilot Document</h3>
            <a href="javascript:void(0);" onclick="addNew();" class="btn btn-primary btn-sm p-2">Add New</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="datatableDefault" class="table text-nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Doc</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="manageModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Manage Doc</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('app.pilot.documents.store') }}" method="POST" id="manageForm" class=""
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label" for="title">Title </label>
                            <input type="hidden" name="edit_id" id="edit_id" />
                            <input type="hidden" name="user_id" id="user_id" value="{{ $user_id }}" />
                            <input type="text" class="form-control" name="title" placeholder="Enter title" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="document">Doc</label>
                            <input type="file" class="form-control" name="document" id="document" />
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
        <script src="{{ asset('assets/plugins/datatables.net/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables.net-buttons/js/buttons.flash.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}">
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
                        url: "{{ route('app.pilot.documents.list') }}",
                        type: 'POST',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            user_id: "{{ $user_id }}"
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

            function addNew() {
                var form = $('#manageForm');
                clearError(form)
                form[0].reset();
                $('#edit_id').val('');
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
                            $('#manageForm').find('[name=title]').val(response.data.title);
                            $('#manageForm').find('[name=edit_id]').val(response.data.id);
                            $('#manageModal').modal('show');
                        }
                    }
                });
            }
        </script>
    </x-slot>
</x-app-layout>
