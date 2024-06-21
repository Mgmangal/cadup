<x-app-layout>
    <x-slot name="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('app.dashboard')}}">DASHBOARD</a></li>
            <li class="breadcrumb-item active">SFA RATE</li>
        </ul>
    </x-slot>
    <x-slot name="css">
        <link href="{{asset('assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" />
        <link href="{{asset('assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css')}}" rel="stylesheet" />
        <link href="{{asset('assets/plugins/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css')}}" rel="stylesheet" />
         <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css" />
    </x-slot>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <!-- Validation -->
    <x-errors class="mb-4" />
    <x-success class="mb-4" />
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3 class="card-title">SFA Rate List </h3>
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
                            <th>Apply Date</th>
                            <th>Fixed Wing Rate</th>
                            <th>Rotor Wing Rate</th>
                            <th>End Date</th>
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
                    <h5 class="modal-title" id="modalLabel">Manage SFA Rate</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{route('app.settings.sfarate.store')}}" method="POST" id="manageForm" class="">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="edit_id" id="edit_id" />
                        <div class="row ">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Apply Date</label>
                                    <input type="text" class="form-control dates" name="apply_date" value="" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">End Date</label>
                                    <input type="text" class="form-control dates" name="end_date" value="" />
                                </div>
                            </div>
                            
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Fixed Wing Rate</label>
                                    <input type="text" class="form-control" name="fixed_wing_rate" value="" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Rotor Wing Rate</label>
                                    <input type="text" class="form-control" name="roator_wing_rate" value="" />
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
        <script src="{{asset('assets/plugins/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js')}}"></script>
         <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script>
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
                        url: "{{route('app.settings.sfarate.list')}}",
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
                $('#manageForm').find('.is-invalid').removeClass('is-invalid');
                $('#manageForm').find('.invalid-feedback').hide();
                $('#manageForm')[0].reset();
                $('#edit_id').val('');
                $('#manageModal').modal('show');
            }
            $('#manageForm').submit(function(e) {
                e.preventDefault();
                $('#manageForm').find('.invalid-feedback').hide();
                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    dataType: 'json',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#manageForm')[0].reset();
                            $('#manageModal').modal('hide');
                            dataList();
                        } else {
                            $.each(response.message, function(fieldName, field) {
                                $('#manageForm').find('[name=' + fieldName + ']').addClass('is-invalid');
                                $('#manageForm').find('[name=' + fieldName + ']').after('<div class="invalid-feedback">' + field + '</div>');
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
                            $('#manageForm').find('[name=apply_date]').val(response.data.apply_date);
                            $('#manageForm').find('[name=fixed_wing_rate]').val(response.data.fixed_wing_rate);
                            $('#manageForm').find('[name=roator_wing_rate]').val(response.data.roator_wing_rate);
                            $('#manageForm').find('[name=end_date]').val(response.data.end_date);
                            $('#manageForm').find('[name=edit_id]').val(response.data.id);
                            $('#manageModal').modal('show');
                        }
                    }
                });
            }
             $(".dates").datetimepicker({
                timepicker: false,
                format: 'd-m-Y',
                formatDate: 'Y/m/d',
                autoclose: true,
                clearBtn: true,
                todayButton: true,
                // maxDate: new Date(new Date().getTime() + 5 * 24 * 60 * 60 * 1000),
                // onSelectDate: function(ct) {
                //     dataList();
                // }
            });
        </script>
    </x-slot>
</x-app-layout>
