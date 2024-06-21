<x-app-layout>
    <x-slot name="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('app.dashboard')}}">DASHBOARD</a></li>
            <li class="breadcrumb-item active">STAMP TICKETS</li>
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
            <h3 class="card-title">Stamp Ticket List </h3>
            <div>
                <a href="javascript:void(0);" class="btn btn-dark btn-sm p-2" onclick="assignStampTicket();">Assign Stamp Ticket</a>
                <a href="javascript:void(0);" class="btn btn-primary btn-sm p-2" onclick="addNew();">Add New</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="datatableDefault" class="table text-nowrap= w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Stamp Type</th>
                            <th>Transaction Type</th>
                            <th>Quantity</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="stampModel" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Add Stamp Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{route('app.stamp_ticket.store')}}" method="POST" id="manageForm" class="">
                    @csrf
                    <div class="modal-body">
                        <div class="row" id="stamp-result">

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
    <div class="modal fade" id="assignstampModel" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Assign Stamp Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{route('app.stamp_ticket.assign')}}" method="POST" id="assignStampForm" class="">
                    @csrf
                    <div class="modal-body">
                        <div class="row" id="assign-result">

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
                        targets: 3,
                    }],
                    order: [[0, 'desc']],
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
                        url: "{{route('app.stamp_ticket.list')}}",
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
                $.ajax({
                    type: 'POST',
                    url: "{{ route('app.stamp_ticket.getAddTicketForm') }}",
                    dataType: "json",
                    data: {
                        _token: "{{ csrf_token() }}",
                    },
                    success: function(data) {
                        if (data.status == 'ok') {
                            console.log(data.result);
                            $('#stamp-result').html(data.html);
                            $('#stampModel').modal('show');
                        } else {
                            alert(data.result);
                        }
                    }
                });
            }
            $('#manageForm').submit(function(e) {
                e.preventDefault();
                clearError($('#manageForm'));
                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    dataType: 'json',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#manageForm')[0].reset();
                            $('#stampModel').modal('hide');
                            dataList();
                        } else {
                            $.each(response.message, function(fieldName, field) {
                                $('#manageForm').find('[name=' + fieldName + ']').addClass('is-invalid');
                                $('#manageForm').find('[name=' + fieldName + ']').after('<div class="invalid-feedback">' + field + '</div>');
                            })
                        }

                    }
                })
            });
        </script>
        <script>
            function assignStampTicket() {
                $.ajax({
                    type: 'POST',
                    url: "{{ route('app.stamp_ticket.getAssignTicketForm') }}",
                    dataType: "json",
                    data: {
                        _token: "{{ csrf_token() }}",
                    },
                    success: function(data) {
                        if (data.status == 'ok') {
                            console.log(data.result);
                            $('#assign-result').html(data.html);
                            $('#assignstampModel').modal('show');
                        } else {
                            alert(data.result);
                        }
                    }
                });
            }

            $('#assignStampForm').submit(function(e) {
                e.preventDefault();
                clearError($('#assignStampForm'));
                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    dataType: 'json',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#assignStampForm')[0].reset();
                            $('#assignstampModel').modal('hide');
                            dataList();
                        } else {
                            $.each(response.message, function(fieldName, field) {
                                $('#assignStampForm').find('[name=' + fieldName + ']').addClass('is-invalid');
                                $('#assignStampForm').find('[name=' + fieldName + ']').after('<div class="invalid-feedback">' + field + '</div>');
                            })
                        }

                    }
                })
            });
        </script>
        </script>
    </x-slot>
</x-app-layout>
