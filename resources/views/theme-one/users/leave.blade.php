@extends('theme-one.layouts.app', ['title' => 'User', 'sub_title' => 'List'])
@section('css')
    <link href="{{ asset('assets/theme_one/lib/datatables.net-dt/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/theme_one/lib/datatables.net-responsive-dt/css/responsive.dataTables.min.css') }}"
        rel="stylesheet">
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">User List</h3>
        <a href="{{route('user.leave.create')}}" class="btn btn-primary">Add Leave</a>
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
                        <th>Crew</th>
                        <th>Leave Type</th>
                        <th>Dates</th>
                        <th>Doc</th>
                        <th>Status</th>
                        <!-- <th>Registered At</th> -->
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                   
                </tbody>
            </table>
        </div>
        
        <div class="modal fade" id="leaveModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel">Modal Title</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12" id="leave_details">
                                
                            </div>
                        </div>
                    </div>   
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        
    </div>
</div>
@endsection

@section('js')
    <script src="{{ asset('assets/theme_one/lib/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/theme_one/lib/datatables.net-dt/js/dataTables.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/theme_one/lib/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/theme_one/lib/datatables.net-responsive-dt/js/responsive.dataTables.min.js') }}"></script>

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
                        className: 'btn btn-default btn-sm'
                    },
                    {
                        extend: 'csv',
                        className: 'btn btn-default btn-sm'
                    }
                ],
                ajax: {
                    url: "{{route('user.leave.list')}}",
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

        $('.filters').on('change', function(){
            dataList();
        });
        
        function changeStatus(id,status)
        {
            $.ajax({
                    url: "{{route('user.leave.status')}}",
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
        function show(url)
        {
            $.ajax({
                    url: url,
                    type: 'get',
                    data: {},
                    dataType: 'json',
                    success: function(data) {
                        if (data.success) {
                            $('#leave_details').html(data.data);
                            $('#leaveModal').modal('show');
                        } 
                    }
                });
        }
    </script>
@endsection    