@extends('theme-one.layouts.app', ['title' => 'Employees', 'sub_title' => $sub_title])
@section('css')
    <link href="{{ asset('assets/theme_one/lib/datatables.net-dt/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/theme_one/lib/datatables.net-responsive-dt/css/responsive.dataTables.min.css') }}"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">

@endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3 class="card-title">Employees {{ $sub_title }}</h3>
            <div class="form-group d-flex">
                <label for="user_status" class="p-2 pt-2"><b>Status</b> </label>
                <select name="status" id="user_status" class="form-control filters">
                    <option value="">All</option>
                    <option selected value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            @if (0)
                <a href="{{ route('user.users.create') }}" class="btn btn-primary">Add Employees</a>
            @endif
        </div>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="card-body">
            <div class="table-responsive">
                <table id="datatableDefault" class="table text-nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th></th>
                            <th>Emp ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Designation</th>
                            <th>Status</th>
                            <th>Registered On</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('assets/theme_one/lib/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/theme_one/lib/datatables.net-dt/js/dataTables.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/theme_one/lib/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/theme_one/lib/datatables.net-responsive-dt/js/responsive.dataTables.min.js') }}"></script>
    
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>

    <script>
        function dataList() {
            $('#datatableDefault').DataTable().destroy();
            $('#datatableDefault').DataTable({
                processing: true,
                serverSide: true,
                dom: "<'row mb-3'<'col-sm-4'l><'col-sm-8 text-end'<'d-flex justify-content-end'fB>>>t<'d-flex align-items-center'<'me-auto'i><'mb-0'p>>",
                lengthMenu: [20, 50, 100, 200, 500, 1000, 2000, 5000, 10000],
                responsive: false,
                columnDefs: [{
                    width: 200,
                    targets: 3
                }],
                fixedColumns: true,
                buttons: [{
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> Print',
                        className: 'btn btn-default btn-sm'
                    },
                    {
                        extend: 'csv',
                        text: '<i class="fas fa-file-csv"></i> CSV',
                        className: 'btn btn-default btn-sm'
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        className: 'btn btn-default btn-sm'
                    }
                ],
                ajax: {
                    url: "{{ route('user.users.report.list') }}",
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        pilot: $('#pilots').val(),
                        status: $('#user_status').val(),
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

        $('.filters').on('change', function(){
            dataList();
        });
        function changeStatus(id,status)
        {
            $.ajax({
                url: "{{route('user.users.status')}}",
                type: 'post',
                data: {id,status,'_token':'{{csrf_token()}}'},
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
    </script>
@endsection
