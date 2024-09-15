<x-app-layout>
    <x-slot name="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('app.dashboard')}}">DASHBOARD</a></li>
            <li class="breadcrumb-item active">SFA </li>
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
    </x-slot>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <!-- Validation -->
    <x-errors class="mb-4" />
    <x-success class="mb-4" />

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3 class="card-title">SFA Reports</h3>
            <a href="{{route('app.sfa.generate')}}" class="btn btn-primary btn-md">Generate Report</a>
        </div>
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        <div class="card-body">
            <div class="row mb-3">

                <div class="col-sm-2">
                    <div class="form-group">
                        <lable for="pilots">Crew</lable>
                        <select class="form-control app_datetime filter" id="pilots" name="pilots" form="sfa-form"
                            required>
                            <option value="">Select</option>
                            @foreach($pilots as $pilot)
                            <option value="{{$pilot->id}}">{{$pilot->salutation.' '.$pilot->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <lable for="from_date">From Date</lable>
                        <input type="text" readonly form="sfa-form" name="from_date"
                            class="form-control datepicker filter" id="from_date" required>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <lable for="to_date">To Date</lable>
                        <input type="text" readonly form="sfa-form" name="to_date"
                            class="form-control datepicker filter" id="to_date" required>
                    </div>
                </div>
            </div>


            <div class="table-responsive">
                <table id="datatableDefault" class="table text-nowrap w-100">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Crew</th>
                            <th>From Date</th>
                            <th>To Date</th>
                            <th>Amount (₹)</th>
                            <th>Status</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                    </tfoot>
                </table>
            </div>
        </div>


        <div class="modal fade" id="forwardModel" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel">Forword </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{route('app.sfa.forward')}}" method="POST" id="forwardForm" class="">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <input type="hidden" name="sfa_id" id="sfa_id">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <lable for="status">Status</lable>
                                        <select class="form-control" id="status" name="status" required>
                                            <option value="">Select</option>
                                            <option value="Rejected">Rejected</option>
                                            <option value="Forwarded">Forwarded</option>
                                            <option value="Paid">Paid</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <lable for="to_section">Forwor Section</lable>
                                        <select class="form-control" id="to_section" name="to_section" onchange="getUserBySection(this, 'to_user')">
                                            <option value="">Select</option>
                                            @foreach($sections as $section)
                                            <option value="{{$section->id}}">{{$section->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <lable for="to_user">Forwor To</lable>
                                        <select class="form-control" id="to_user" name="to_user" required>
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <lable for="pilots">Discription</lable>
                                        <textarea class="form-control" name="description" id="description"></textarea>
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


    </div>

    <x-slot name="js">
        <script src="https://cdn.ckeditor.com/4.17.1/standard/ckeditor.js"></script>
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
            function forwords(sfa_id) {
                $('#forwardForm').find('[name=sfa_id]').val(sfa_id);
                $('#forwardModel').modal('show');
            }
            function getUserBySection(e, select_id) {
                var id = $(e).val();
                $.ajax({
                    url: "{{ route('home.user.by.section') }}",
                    method: "post",
                    dataType: "json",
                    data: {
                        _token: "{{ csrf_token() }}",
                        'id': id
                    },
                    success: function(data) {
                        $('#' + select_id).html(data);

                    }
                });

            }
            $('#forwardForm').submit(function(e) {
                e.preventDefault();
                clearError($('#forwardForm'));
                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    dataType: 'json',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#forwardForm')[0].reset();
                            $('#forwardModel').modal('hide');
                            dataList();
                        } else {
                            $.each(response.message, function(fieldName, field) {
                                $('#forwardForm').find('[name=' + fieldName + ']').addClass('is-invalid');
                                $('#forwardForm').find('[name=' + fieldName + ']').after('<div class="invalid-feedback">' + field + '</div>');
                            })
                        }

                    }
                })
            });
        $('.datepicker').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy',
            calendarWeeks: false,
            zIndexOffset: 9999,
            orientation: "bottom"
        });

        function dataList() {
            $('#datatableDefault').DataTable().destroy();
            var pilot = $('#pilots').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            $('#datatableDefault').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                paging: false,
                info: false,
                order: [
                    [2, 'desc']
                ],
                orderable: false,
                lengthMenu: [20, 50, 100, 200, 500, 1000, 2000, 5000, 10000],
                responsive: true,
                fixedColumns: true,
                "columnDefs": [{
                        "orderable": false,
                        "targets": [0, 5]
                    } // Disable order on first columns
                ],
                ajax: {
                    url: "{{route('app.user.sfa.list')}}",
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        pilot,
                        from_date,
                        to_date
                    },
                },
                fnRowCallback: function(nRow, aData, iDisplayIndex) {
                    var oSettings = this.fnSettings();
                    $("td:eq(0)", nRow).html(oSettings._iDisplayStart + iDisplayIndex + 1);
                },
                "initComplete": function() {
                },
                drawCallback: function(settings) {
                },
            });
        }
        $('.filter').on('change', function() {
            dataList();
        });
        dataList();
        </script>
    </x-slot>
</x-app-layout>